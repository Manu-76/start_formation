<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-compte')]
class ProfileController extends AbstractController
{
    #[Route('/mes-infos', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/addfavori', name: 'add_favori', methods: ['POST'])]
    public function addFavori(Request $request, LivreRepository $livreRepository, EntityManagerInterface $em): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser(); // On récupère le user connecté

        $livreId = $request->request->get("id"); // On récupère l'id du livre envoyé par ajax
        $livre = $livreRepository->find($livreId); // On récupère le livre
        $user->addLivre($livre); // On ajoute le livre en favori de l'utilisateur

        $em->persist($user);
        $em->flush();
        return new Response("ok"); // On retourne une réponse
    }

    #[Route('/deletefavori', name: 'delete_favori', methods: ['POST'])]
    public function deleteFavori(Request $request, LivreRepository $livreRepository, EntityManagerInterface $em): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser(); // On récupère l'utilisateur connecté

        $livreId = $request->request->get("id"); // On récupère l'id du livre envoyé par AJAX
        $livre = $livreRepository->find($livreId); // On récupère le livre en question

        if ($livre) {
            $user->removeLivre($livre); // On supprime le livre des favoris de l'utilisateur
            $em->persist($user);
            $em->flush();
            return new Response("ok"); // Retourne une réponse en cas de succès
        }

        return new Response("error", 404); // Retourne une erreur si le livre n'est pas trouvé
    }
}
