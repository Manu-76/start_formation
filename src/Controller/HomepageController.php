<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    // #[Route('/homepage', name: 'app_homepage')]
    public function index(LivreRepository $livreRepository): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->render('homepage/index.html.twig', [
            'livres' => $livreRepository->findAll(),
            'user' => $user
        ]);
        // On commente en 2eme apres avoir fini livres
        // return $this->render('homepage/index.html.twig', [
        //     'message' => 'Bienvenue dans notre premier projet Symfony! ',
        // ]);
        // return $this->render('homepage/index.html.twig', [
        //     'controller_name' => 'HomepageController',
        // ]);
    }

    #[Route('/search', name:'search', methods: ['GET'])]
    public function search(Request $request, LivreRepository $livreRepository): Response
    {
        $value = $request->query->get("search");
        // dd($value);

        $elementsFound = $livreRepository->searchItems($value);

        return $this->render('homepage/index.html.twig', [
            'livres' => $elementsFound
        ]);
    }
}
