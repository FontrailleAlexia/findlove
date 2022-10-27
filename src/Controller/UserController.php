<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * Création d'un utilisateur
     */
    #[Route('/api/register', name: 'register')]
    public function register(UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder, Request $request, ValidatorInterface $validator)
    {
        $userData = $request->request->all();

        $errors = $validator->validate($userData);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = new User();
        //$user = $this->getUser();

        //$user->setUser($user);
        $user->setFirstname($userData['firstname']);
        $user->setEmail($userData['email']);
        $user->setLastname($userData['lastname']);
        $user->setBirthdate(new \DateTime($userData['birthdate']));
        //$user->setBirthdate($userData['birthdate']);
        $user->setGender($userData['gender']);
        //$user->setStudy($userData['study']);
        $user->setCity($userData['city']);
        //$user->setWork($userData['work']);
        $user->setPassword($userData['password']);
        $user->setSearch($userData['search']);
        $user->setRoles(['ROLE_USER']);

        $password = $user->getPassword();
        // This is where we encode the User password (found in $ user)
        $encodedPassword = $passwordEncoder->hashPassword($user, $password);
        // We reassign the password encoded in the User
        $user->setPassword($encodedPassword);

        $user->setCreatedAt(new \DateTimeImmutable());


        // retrieves an instance of UploadedFile identified by picture
        $uploadedFile = $request->files->get('avatar');

        if ($uploadedFile) {
            $newFilename = $uploaderHelper->uploadImage($uploadedFile);
            $user->setAvatar($newFilename);
        }
        // We save the user
        $entityManager->persist($user);
        $entityManager->flush();


        // We redirect to api_user_read
        return $this->json([
            'user' => $user,
        ], Response::HTTP_CREATED, [], ['groups' => 'user_read']);
    }
}
