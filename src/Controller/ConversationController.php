<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Infrastructure\Mercure\Events\MercureEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/api")
 */
class ConversationController extends AbstractController
{
    /**
     * @Route("/convs/new/{id}", name="conversation_new", methods={"POST"})
     * ROUTE OK
     * Permet de créer une nouvelle conversation
     */
    public function new(User $user, ConversationRepository $convRepo, EntityManagerInterface $em, Security $security): JsonResponse
    {
        if ($user === $this->getUser()) {
            return $this->json(['msg' => 'You can not create conversation with yourself.'], 400);
        }
        /**
         * $this->getUser = Utilisateur courant (expéditeur)
         * $user = Destinataire
         */
        //dd($this->getUser(), $user);

        $conv = null;
        $userConvs = $convRepo->findConvsOfUser($this->getUser());

        foreach ($userConvs as $cv) {
            if ($cv->getUsers()->contains($user)) {
                $conv = $cv;
                break;
            }
        }

        if ($conv) {
            return $this->json([
                'id' => $conv->getId(),
                'alreadyExists' => true,
            ]);
        }

        $conv = new Conversation();
        $conv->addUser($this->getUser())
            ->addUser($user)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setOwnerId($this->getUser()->getId());
        $em->persist($conv);
        $em->flush();

        return $this->json([
            'id' => $conv->getId(),
            'alreadyExists' => false,
        ]);
    }

    /**
     * @Route("/convs", name="conversations", methods={"GET"})
     * Permet d'afficher les conversations de l'utilisateur connecté
     * ROUTE OK
     */
    public function convs(Request $request,  ConversationRepository $convRepo): Response
    {
        $currentPage = $request->query->getInt('page', 1);
        $max = $request->query->getInt('max', 15);

        if ($max > 15) {
            $max = 15;
        }

        $offset = $currentPage * $max - $max;

        $convs = $convRepo->findConvsOfUser($this->getUser(), $max, $offset);

        $userConvs = [];
        foreach ($convs as $conv) {
            $c = [];
            $c['id'] = $conv->getId();
            $c['ownerId'] = $conv->getOwnerId();
            $c['msg'] = $conv->getLastMessage() !== null ? $conv->getLastMessage()->getContenu() : 'Start Chat Now';
            $c['date'] = $conv->getLastMessage() !== null ? $conv->getLastMessage()->getUpdatedAt() : $conv->getUpdatedAt();

            foreach ($conv->getUsers() as $user) {
                if ($user != $this->getUser()) {
                    $c['user'] = [
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'firstname' => $user->getFirstname(),
                        'avatar' => $user->getAvatar(),
                        'created_at' => $user->getCreatedAt()
                    ];
                }
            }
            $userConvs[] = $c;
        }
        /*
        return $this->json([
            'data' => $userConvs,
            'count' => (int) $convRepo->countConvsOfUser($this->getUser()),
            'groups' => 'conv_show'
        ],[],[]);
        */
     
        
        return $this->json([
            'conversations' => $userConvs,
            'count' => (int) $convRepo->countConvsOfUser($this->getUser()),
            
        ],200,[],['groups' => 'conv_show']);
        
    }

    /**
     * @Route("/convs/{id}", name="conversation_show", methods={"GET"})
     * Permet d'afficher une conversation selon l'ID de l'utilisateur connecté
     * ROUTE OK
     */
    public function conv(Conversation $conv): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        return $this->json($conv, 200, [], [
            'groups' => 'conv_show'
        ]);
    }

    /**
     * @Route("/convs/{id}/delete", name="conversation_delte", methods={"DELETE"})
     * Permet de supprimer une conversation
     * ROUTE OK
     */
    //#[Route("/convs/{id}/delete", name:"conversation_delte", methods:'DELETE')]
    public function delete(Conversation $conv, EntityManagerInterface $em, EventDispatcherInterface $dispatcher): JsonResponse
    {
        // only the owner can delete a conversation not every one.
        // if deleted, delete it also from the other participiant
        $this->denyAccessUnlessGranted('CONV_DELETE', $conv);
        $targets = [];

        foreach ($conv->getUsers() as $user) {
            $targets[] = "/convs/{$user->getId()}";
        }

        $dispatcher->dispatch(
            new MercureEvent($targets, [
                'id' => $conv->getId(),
                'isDeleted' => true,
            ])
        );


        try {

            $em->remove($conv);
            $em->flush();
        } catch (\Exception $e) {
            dd($e);
            return $this->json(['error' => 'Unexpected Error'], 500);
        }

        return $this->json([], 204);
    }
}
