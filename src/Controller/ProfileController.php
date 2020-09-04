<?php

namespace App\Controller;

use App\DTO\PasswordChangeDTO;
use App\Entity\User;
use App\Form\UserPaswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $currentUser,
        ]);
    }

    /**
     * @Route("/profile/change/password", name="profile_change_password")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $form = $this->createForm(UserPaswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordChangeData = new PasswordChangeDTO($form->getData());
            if ($passwordChangeData->validate($currentUser->getPassword())) {
                $currentUser->setPassword(password_hash($passwordChangeData->getNewPassword(),PASSWORD_DEFAULT));
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Password was changed!');
                return $this->redirectToRoute('profile');
            } else {
                $this->addFlash('danger', 'Validation error!');
            }
        }

        return $this->render('profile/change/password.html.twig', [
            'user' => $currentUser,
            'form' => $form->createView()
        ]);
    }
}
