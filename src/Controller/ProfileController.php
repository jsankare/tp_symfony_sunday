<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si l'utilisateur est banni
        if (in_array('ROLE_BANNED', $user->getRoles())) {
            throw new AccessDeniedException('Votre compte a été banni. Veuillez contacter l\'administrateur.');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'isAdmin' => in_array('ROLE_ADMIN', $user->getRoles()),
            'isUser' => in_array('ROLE_USER', $user->getRoles()),
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('profile/admin.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
