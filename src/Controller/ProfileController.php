<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Repository\UserRepository;
use App\Repository\TestimonyRepository;
use App\Repository\ClientRepository;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (in_array('ROLE_BANNED', $user->getRoles())) {
            throw new AccessDeniedException('Votre compte a été banni. Veuillez contacter l\'administrateur.');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'isAdmin' => in_array('ROLE_ADMIN', $user->getRoles()),
            'isUser' => in_array('ROLE_USER', $user->getRoles()),
        ]);
    }

    #[Route('/admin', name: 'app_profile_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(
        UserRepository $userRepository,
        TestimonyRepository $testimonyRepository,
        ClientRepository $clientRepository
    ): Response
    {
        return $this->render('admin/index.html.twig', [
            'user' => $this->getUser(),
            'userCount' => $userRepository->count([]),
            'testimonyCount' => $testimonyRepository->count([]),
            'clientCount' => $clientRepository->count([]),
        ]);
    }
}
