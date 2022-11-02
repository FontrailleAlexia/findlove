<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Conversation;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Infrastructure\Mercure\Events\MercureEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/api", name="messages_")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/convs/{conv}/msgs", name="of_conv", methods={"GET"})
     * Permet de voir les messages selon l'Id de la conversation
     * ROUTE OK
     */
    public function getMessages(Request $request, Conversation $conv, MessageRepository $msgRepo): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        $currentPage = $request->query->getInt('page', 1);
        $max = $request->query->getInt('max', 15);
        if ($max > 15) {
            $max = 15;
        }
        $offset = $currentPage * $max - $max;

        $msgs = $msgRepo->findLastMessages($conv, $max, $offset);

        return  $this->json([
            'data' => array_reverse($msgs),
            'count' => (int) $msgRepo->countMessages($conv),
        ], 200, [], [
            'groups' => [
                'msg'
            ],
        ]);
    }

    /**
     * @Route("/convs/{id}/msgs/new", name="new", methods={"POST"})
     * Permet de créer un message dans une conversation ou l'on en est le propriétaire
     * {id} c'est l'id de la conversation que l'utilisateur a crée
     * A VOIR : Ne prends pas en compte la clef
     */
    public function new(Conversation $conv, SerializerInterface $serializer, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        // Retrieve the content of the request, i.e. the JSON
        $jsonContent = $request->getContent();

        // We deserialize this JSON into a message entity, thanks to the Serializer
        // We transform the JSON into an object of type App\Entity\Message
        $message = $serializer->deserialize($jsonContent, Message::class, 'json');
        //dd($message);

        if (!$request->getContent()) {
            $this->json([], 400);
        }
        //dd($request->getContent());
        //$message = new Message();
        $message
            ->setUser($this->getUser())
          
            ->setConversation($conv)
            ->setCreatedAt(new \DateTimeImmutable());

        //dd($message);
        $conv->setLastMessage($message);

        $em->persist($message);
        $em->persist($conv);
        $em->flush();

        return $this->json($message, 200, [], [
            'groups' => 'msg'
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/messages/{id}/delete", name="delete", methods={"DELETE"})
     * Permet de supprimer un message
     * ROUTE OK
     */
    public function delete(Message $message, EntityManagerInterface $em, EventDispatcherInterface $dispatcher): JsonResponse
    {
        /**
         * @todo Notify the conversation that the last message is updated.
         */
        $this->denyAccessUnlessGranted('DELETE_MSG', $message, 'Only the admins or the message owner can delete it.');

        $dispatcher->dispatch(
            new MercureEvent(["/msgs/{$message->getConversation()->getId()}"], [
                'id' => $message->getId(),
                'isDeleted' => true,
            ])
        );

        $em->remove($message);
        $em->flush();

        return $this->json([], 204);
    }

    /**
     * @Route("/messages/{id}/update", name="edit", methods={"PUT"})
     * A VOIR : Ne prends pas en compte la clef
     */
    public function edit(Request $request, Message $message, EntityManagerInterface $em): JsonResponse
    {
        /**
         * @todo Notify the conversation that the last message is updated.
         */
        $this->denyAccessUnlessGranted('EDIT_MSG', $message, 'Only the admins or the message owner can edit it.');
        $contenu = $request->getContent();

        if (empty($contenu)) {
            return $this->json(['msg' => 'Content Required'], 400);
        }

        $message->setContenu($contenu);

        $em->persist($message);
        $em->flush();

        return $this->json($message, 200, [], [
            'groups' => 'msg',
        ]);
    }
}
