<?php


namespace App\Manager;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UsersManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveUser(FormInterface $form, UserInterface $user)
    {
        /** @var User $user */
        $user = $form->getData();
        if (!$user->getId()) {
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
