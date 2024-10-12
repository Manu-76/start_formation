<?php

namespace App\Controller;

use App\Entity\ProduitDansPanier;
use App\Entity\User;
use App\Repository\LivreRepository;
use App\Repository\ProduitDansPanierRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-panier')]
class CartController extends AbstractController
{
    // Affichage du panier du user
    #[Route('/liste', name: 'app_panier_index')]
    public function index(): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->render('cart/index.html.twig', [
            'user' => $user
        ]);
    }

    // Ajouter un livre dans le panier
    #[Route('/{slug}/ajouter_un_produit_depuis_homepage', name: 'app_cart_add')]
    public function add(int $slug, EntityManagerInterface $em, LivreRepository $livreRepository, ProduitDansPanierRepository $pdpRepository, PanierService $panierService): Response
    {
        // // Ici on commente pour utiliser le PanierService en dessous
        // // On peut récupérer l'user connecté grace a l'annotation php
        // /**
        //  * @var User $user
        //  */
        // $user = $this->getUser();

        // // On récupère le livre choisi pour ajout dans le panier par l'intermédiaire de son id envoyé en param de route en terme de slug
        // $livre = $livreRepository->find($slug);

        // Vérification si le livre existe
        // if (!$livre) {
        //     $this->addFlash('error', 'Le livre n\'existe pas !');
        //     return $this->redirectToRoute('app_homepage');  // Le livre n'existe pas
        // }

        // // On cherche si le livre existe deja en bdd pour ce user
        // $produitDansPanier = $pdpRepository->findOneBy(['user' => $user, 'livre' => $livre]);

        // // Si il existe, on incremente de 1 et maj du total panier
        // if($produitDansPanier) {
        //     $produitDansPanier->setQuantite($produitDansPanier->getQuantite() + 1)->setMontantTotal($produitDansPanier->getMontantTotal() + $livre->getTarif());
        // } else {
        //     $produitDansPanier = new ProduitDansPanier();
        //     $produitDansPanier->setQuantite(1)->setLivre($livre)->setMontantTotal($livre->getTarif())->setUser($user);
        // }

        // $user->setTotalPanier($user->getTotalPanier() + $livre->getTarif());
        // $em->persist($produitDansPanier);
        // $em->flush();

        // Appel au service pour ajouter un livre au panier
        $livreAjoute = $panierService->ajoutAuPanier($slug);

        // Si le livre n'existe pas, ajouter un message d'erreur
        if (!$livreAjoute) {
            $this->addFlash('error', 'Le livre n\'existe pas !');
            return $this->redirectToRoute('app_homepage'); // Redirection vers la page du panier ou une autre page
        }

        $this->addFlash('success', 'Livre ajouté au panier!');
        return $this->redirectToRoute('app_homepage');
    }

    // Pour supprimer une ligne du panier (tout l'objet dans le panier)
    #[Route('/retirer/{slug}/un_produit', name: 'app_cart_remove')]
    public function remove(int $slug, PanierService $panierService) : Response
    {
        // Appel au service pour retirer un livre
        $produitExiste = $panierService->retirerUnLivre($slug);

        // Si le produit n'existe pas, ajouter un message d'erreur et rediriger vers la page du panier
        if (!$produitExiste) {
            $this->addFlash('danger', 'L\'article n\'existe pas dans votre panier !');
        } else {
            $this->addFlash('success', 'Livre retiré du panier !');
        }

        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/ajouter/{slug}/une_quantite', name: 'app_cart_plus')]
    public function augmenterUnLivre(int $slug, PanierService $panierService) : Response
    {
        $objetExistedansPanier = $panierService->ajouterUneQuantite($slug);

        // Si le produit n'existe pas, ajouter un message d'erreur
        if (!$objetExistedansPanier) {
            $this->addFlash('error', 'L\'article n\'existe pas dans votre panier !');
        } else {
            $this->addFlash('success', 'Quantité de livre mise à jour !');
        }

        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/diminuer/{slug}/une_quantite', name: 'app_cart_minus')]
    public function diminuerUnLivre(int $slug, PanierService $panierService) : Response
    {
        $objetExistedansPanier = $panierService->retirerUneQuantite($slug);

        // Si le produit n'existe pas, ajouter un message d'erreur
        if (!$objetExistedansPanier) {
            $this->addFlash('error', 'L\'article n\'existe pas dans votre panier !');
        } else {
            $this->addFlash('success', 'Quantité de livre mise à jour !');
        }

        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/vider', name: 'app_delete_all_cart')]
    public function deleteAllCart(ProduitDansPanierRepository $produitDansPanierRepository, EntityManagerInterface $em) : Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $objetsDansPanier = $produitDansPanierRepository->findBy(['user' => $user]);

        if($objetsDansPanier) {
            foreach($objetsDansPanier as $objetDansPanier) {
                $em->remove($objetDansPanier);
            }
        }

        // Réinitialiser le total du panier de l'utilisateur
        $user->setTotalPanier(0);
        
        // Sauvegarder les suppressions en base de données
        $em->flush();

        // Ajouter un message de succès pour confirmer la suppression
        $this->addFlash('success', 'Votre panier a été vidé avec succès.');
        return $this->redirectToRoute('app_panier_index');
    }
}
