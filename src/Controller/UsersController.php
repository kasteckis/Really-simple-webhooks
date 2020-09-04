<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Manager\UsersManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    private UsersManager $usersManager;

    public function __construct(UsersManager $usersManager)
    {
        $this->usersManager = $usersManager;
    }

    /**
     * @Route("/users", name="users")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/users/add", name="users_add")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->usersManager->saveUser($form, $this->getUser());
            return $this->redirectToRoute('users');
        }

        return $this->render('users/add_edit.html.twig', [
            'form' => $form->createView(),
            'edit' => false
        ]);
    }

    /**
     * @Route("/users/edit/{user}", name="users_edit")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->usersManager->saveUser($form, $this->getUser());

            return $this->redirectToRoute('users');
        }

        return $this->render('users/add_edit.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
        ]);
    }

    /**
     * @Route("/users/delete/{user}", name="users_delete")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user)
    {
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('users');
    }
}
