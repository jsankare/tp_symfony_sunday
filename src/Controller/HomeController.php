<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $readmePath = $this->getParameter('kernel.project_dir') . '/README.md';
        $readmeContent = file_exists($readmePath) ? file_get_contents($readmePath) : '';

        return $this->render('home/index.html.twig', [
            'readme_content' => $readmeContent,
        ]);
    }
}
