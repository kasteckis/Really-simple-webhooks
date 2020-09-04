<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        return $this->render('home/index.html.twig', [
            'user' => $currentUser
        ]);
    }
}
