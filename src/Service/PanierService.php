<?php 

namespace App\Service;

use Error;
use App\Entity\User;
use App\Entity\ProduitDansPanier;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\ProduitDansPanierRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PanierService{

    public function __construct(private EntityManagerInterface $em, private LivreRepository $livreRepository, private ProduitDansPanierRepository $pdpRepository, private TokenStorageInterface $tokenStorageInterface)
    {
    }

    public function ajoutAuPanier($slug): bool
    {
        /**
         * @var User $user
         */
        $user = $this->tokenStorageInterface->getToken()->getUser();
        $livre = $this->livreRepository->find($slug);

        // Vérification si le livre existe
        if (!$livre) {
            return false; // Le livre n'existe pas
        }

        // Chercher si le produit est déjà dans le panier de l'utilisateur
        $produitDansPanier = $this->pdpRepository->findOneBy(['user' => $user, 'livre' => $livre]);
        
        if ($produitDansPanier) {
            // Si le produit est déjà dans le panier, on incrémente la quantité et le montant total
            $produitDansPanier->setQuantite($produitDansPanier->getQuantite() + 1)->setMontantTotal($produitDansPanier->getMontantTotal() + $livre->getTarif());
        } else {
            // Sinon, on crée un nouveau produit dans le panier
            $produitDansPanier = new ProduitDansPanier();
            $produitDansPanier->setQuantite(1)->setLivre($livre)->setMontantTotal($livre->getTarif())->setUser($user);
        }

        // Mise à jour du total du panier pour l'utilisateur
        $user->setTotalPanier($user->getTotalPanier() + $livre->getTarif());

        // Persistance et sauvegarde
        $this->em->persist($produitDansPanier);
        $this->em->flush();

        return true; // Le livre a été ajouté au panier avec succès
    }

    // Pour supprimer une ligne du panier, cad retirer tout l'objet
    public function retirerUnLivre($slug): bool
    {
        /**
         * @var User $user
         */
        $user = $this->tokenStorageInterface->getToken()->getUser();
        $produitDansPanier = $this->pdpRepository->findOneBy(['user' => $user, 'id' => $slug]);

        // Si le produit existe dans le panier, on le supprime et on retourne true
        if ($produitDansPanier) {
            $user->setTotalPanier($user->getTotalPanier() - $produitDansPanier->getMontantTotal());
            $this->em->remove($produitDansPanier);
            $this->em->flush();
            return true;
        }

        // Si le produit n'existe pas, on retourne false
        return false;
    }

    public function retirerUneQuantite($slug) {
        /**
         * @var User $user
         */
        $user = $this->tokenStorageInterface->getToken()->getUser();

        // Chercher si le produit est bien dans le panier de l'utilisateur
        $produitDansPanier = $this->pdpRepository->findOneBy(['user' => $user, 'id' => $slug]);

        if(!$produitDansPanier){
            return false;
        }

        // On récupere le livre associé a cet objetdansPanier
        $livre = $produitDansPanier->getLivre();

        if($produitDansPanier->getQuantite() > 1) {
            // Si le produit existe dans le panier et si sa quantité est > 1, on décrémente la quantité et le montant total
            $produitDansPanier->setQuantite($produitDansPanier->getQuantite() - 1)->setMontantTotal($produitDansPanier->getMontantTotal() - $livre->getTarif());
        } else {
            // Si la quantité est = 1, on supprimer la ligne, toutl'objet
            $this->em->remove($produitDansPanier);
        }

        $user->setTotalPanier($user->getTotalPanier() - $livre->getTarif());
        $this->em->flush();

        return true;
    }

    public function ajouterUneQuantite($slug) {
        /**
         * @var User $user
         */
        $user = $this->tokenStorageInterface->getToken()->getUser();

        // Chercher si le produit est bien dans le panier de l'utilisateur
        $produitDansPanier = $this->pdpRepository->findOneBy(['user' => $user, 'id' => $slug]);

        if(!$produitDansPanier){
            return false;
        }

        $produitDansPanier->setQuantite($produitDansPanier->getQuantite() + 1)->setMontantTotal($produitDansPanier->getMontantTotal() + $produitDansPanier->getLivre()->getTarif());

        $user->setTotalPanier($user->getTotalPanier() + $produitDansPanier->getLivre()->getTarif());
        $this->em->flush();

        return true;
    }
}