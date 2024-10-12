<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $uphi)
    { 
    }

    public function load(ObjectManager $manager): void
    {
        // ADMIN
        //  On instancie un utilisateur
        $user = new User();
        //  On renseigne la propriété email à l'aide du setter
        $user->setEmail('admin@admin.fr');
        //  Gestion du mdp
        $plainPassword = "password";  // le mdp en clair que l'on veut encoder
        $encodedPassword = $this->uphi->hashPassword($user, $plainPassword);  // On encode le password avec l'encoder mémorisé lors de l'injection dans le constructeur
        $user->setPassword($encodedPassword);  // On renseigne la propriété password de l'utilisateur avec le setter
        $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]); // On affecte un role à l'utilisateur
        $user->setVerified(1); // On met la propriété isVerified à 1 pour les utilisateurs aient le droit de se connecter sans passer par le process du mail de confirmation
        //on mémorise l'instance d'utilisateur afin de l'ajouter ultérieurement dans la base de données
        $manager->persist($user);

        // SIMPLE USER
        $user = new User();
        $user->setEmail('user@user.fr');
        $plainPassword = "password"; 
        $encodedPassword = $this->uphi->hashPassword($user, $plainPassword);  
        $user->setPassword($encodedPassword);
        $user->setRoles(["ROLE_USER"]); // On affecte un role à l'utilisateur
        $user->setVerified(1);
        $manager->persist($user);

        $manager->flush();
    }
}
