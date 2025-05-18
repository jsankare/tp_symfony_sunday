<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Testimony;
use App\Entity\Client;
use App\Entity\Projet;
use App\Entity\Livrable;
use App\Form\ClientType;
use App\Form\ProjetType;
use App\Form\LivrableType;
use App\Repository\UserRepository;
use App\Repository\TestimonyRepository;
use App\Repository\ClientRepository;
use App\Repository\ProjetRepository;
use App\Repository\LivrableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(
        UserRepository $userRepository,
        TestimonyRepository $testimonyRepository,
        ClientRepository $clientRepository,
        ProjetRepository $projetRepository,
        LivrableRepository $livrableRepository
    ): Response {
        return $this->render('admin/index.html.twig', [
            'user' => $this->getUser(),
            'userCount' => $userRepository->count([]),
            'testimonyCount' => $testimonyRepository->count([]),
            'clientCount' => $clientRepository->count([]),
            'projetCount' => $projetRepository->count([]),
            'livrableCount' => $livrableRepository->count([]),
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/{id}/ban', name: 'app_admin_user_ban', methods: ['POST'])]
    public function banUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setRoles(['ROLE_BANNED']);
        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur banni avec succès.');
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/users/{id}/unban', name: 'app_admin_user_unban', methods: ['POST'])]
    public function unbanUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setRoles(['ROLE_USER']);
        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur débanni avec succès.');
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/testimonials', name: 'app_admin_testimonials')]
    public function testimonials(TestimonyRepository $testimonyRepository): Response
    {
        return $this->render('admin/testimonials.html.twig', [
            'testimonials' => $testimonyRepository->findAll(),
        ]);
    }

    #[Route('/clients', name: 'app_admin_clients')]
    public function clients(ClientRepository $clientRepository): Response
    {
        return $this->render('admin/clients.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/clients/new', name: 'app_admin_client_new', methods: ['GET', 'POST'])]
    public function newClient(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();
            $this->addFlash('success', 'Client créé avec succès.');
            return $this->redirectToRoute('app_admin_clients');
        }

        return $this->render('admin/client_form.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/clients/{id}/edit', name: 'app_admin_client_edit', methods: ['GET', 'POST'])]
    public function editClient(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Client modifié avec succès.');
            return $this->redirectToRoute('app_admin_clients');
        }

        return $this->render('admin/client_form.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/clients/{id}', name: 'app_admin_client_delete', methods: ['POST'])]
    public function deleteClient(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {

            foreach ($client->getTestimonies() as $testimony) {
                $entityManager->remove($testimony);
            }

            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_clients');
    }

    #[Route('/testimonials/{id}/delete', name: 'app_admin_testimonial_delete', methods: ['POST'])]
    public function deleteTestimonial(Request $request, Testimony $testimony, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_testimony_' . $testimony->getId(), $request->request->get('_token'))) {
            $entityManager->remove($testimony);
            $entityManager->flush();
            $this->addFlash('success', 'Témoignage supprimé avec succès.');
        }
        return $this->redirectToRoute('app_admin_testimonials');
    }

    #[Route('/projets', name: 'app_admin_projets')]
    public function projets(ProjetRepository $projetRepository): Response
    {
        return $this->render('admin/projets.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }

    #[Route('/projets/new', name: 'app_admin_projet_new', methods: ['GET', 'POST'])]
    public function newProjet(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projet);
            $entityManager->flush();
            $this->addFlash('success', 'Projet créé avec succès.');
            return $this->redirectToRoute('app_admin_projets');
        }

        return $this->render('admin/projet_form.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/projets/{id}/edit', name: 'app_admin_projet_edit', methods: ['GET', 'POST'])]
    public function editProjet(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Projet modifié avec succès.');
            return $this->redirectToRoute('app_admin_projets');
        }

        return $this->render('admin/projet_form.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/projets/{id}', name: 'app_admin_projet_delete', methods: ['POST'])]
    public function deleteProjet(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
            $this->addFlash('success', 'Projet supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_projets');
    }

    #[Route('/livrables', name: 'app_admin_livrables')]
    public function livrables(LivrableRepository $livrableRepository): Response
    {
        return $this->render('admin/livrables.html.twig', [
            'livrables' => $livrableRepository->findAll(),
        ]);
    }

    #[Route('/livrables/new', name: 'app_admin_livrable_new', methods: ['GET', 'POST'])]
    public function newLivrable(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livrable = new Livrable();
        $form = $this->createForm(LivrableType::class, $livrable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livrable);
            $entityManager->flush();
            $this->addFlash('success', 'Livrable créé avec succès.');
            return $this->redirectToRoute('app_admin_livrables');
        }

        return $this->render('admin/livrable_form.html.twig', [
            'livrable' => $livrable,
            'form' => $form,
        ]);
    }

    #[Route('/livrables/{id}/edit', name: 'app_admin_livrable_edit', methods: ['GET', 'POST'])]
    public function editLivrable(Request $request, Livrable $livrable, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivrableType::class, $livrable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Livrable modifié avec succès.');
            return $this->redirectToRoute('app_admin_livrables');
        }

        return $this->render('admin/livrable_form.html.twig', [
            'livrable' => $livrable,
            'form' => $form,
        ]);
    }

    #[Route('/livrables/{id}', name: 'app_admin_livrable_delete', methods: ['POST'])]
    public function deleteLivrable(Request $request, Livrable $livrable, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livrable->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livrable);
            $entityManager->flush();
            $this->addFlash('success', 'Livrable supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_livrables');
    }
}
