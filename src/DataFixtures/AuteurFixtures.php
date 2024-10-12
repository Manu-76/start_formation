<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuteurFixtures extends Fixture
{
    // ====================================================== //
    // ===================== PROPRIETES ===================== //
    // ====================================================== //
    public const F_THILLIEZ = 'franck-thilliez';
    public const M_CHATTAM = 'maxime-chattam';
    public const B_PHILIPPON = 'benoit-philippon';
    public const G_RRMARTIN = 'georges-martin';

    //  Mise en place d'une reference pour les auteurs afin de pouvoir les utiliser dans la fixture des livres.

    public function load(ObjectManager $manager): void
    {
        $autor = new Auteur();
        $autor->setPrenom('Franck');
        $autor->setNom('Thilliez');
        $manager->persist($autor);
        $this->addReference(self::F_THILLIEZ, $autor);

        $autor = new Auteur();
        $autor->setPrenom('Maxime');
        $autor->setNom('Chattam');
        $manager->persist($autor);
        $this->addReference(self::M_CHATTAM, $autor);
        
        $autor = new Auteur();
        $autor->setPrenom('Benoit');
        $autor->setNom('Philippon');
        $manager->persist($autor);
        $this->addReference(self::B_PHILIPPON, $autor);

        $autor = new Auteur();
        $autor->setPrenom('George');
        $autor->setNom('R.R.Martin');
        $manager->persist($autor);
        $this->addReference(self::G_RRMARTIN, $autor);

        $manager->flush();
    }
}
