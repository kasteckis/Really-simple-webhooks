<?php


namespace App\Manager;


use App\Entity\Job;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JobsManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveJob(FormInterface $form, UserInterface $user)
    {
        /** @var Job $job */
        $job = $form->getData();
        $job->setCreatedBy($user);
        $this->entityManager->persist($job);
        $this->entityManager->flush();
    }
}
