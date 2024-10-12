<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $book = new Livre();
        $book->setTitre('Il était deux fois');
        $book->setResume('En 2008, Julie, dix-sept ans, disparaît en ne laissant comme trace que son vélo posé contre un arbre. Le drame agite Sagas, petite ville au cœur des montagnes, et percute de plein fouet le père de la jeune fille, le lieutenant de gendarmerie Gabriel Moscato. Ce dernier se lance alors dans une enquête aussi désespérée qu\'effrénée. Jusqu\'à ce jour où ses pas le mènent à l\'hôtel de la Falaise... Là, le propriétaire lui donne accès à son registre et lui propose de le consulter dans la chambre 29, au deuxième étage. Mais exténué par un mois de vaines recherches, il finit par s\'endormir avant d\'être brusquement réveillé en pleine nuit par des impacts sourds contre sa fenêtre...');
        $book->setImageName('deux_fois.jpg');
        $book->setAuteur($this->getReference(AuteurFixtures::F_THILLIEZ));
        $manager->persist($book);

        $book = new Livre();
        $book->setTitre('Luca');
        $book->setResume('Existe-t-il encore un jardin secret que nous ne livrions pas aux machines ? " Partout, il y a la terreur. Celle d\'une jeune femme dans une chambre d\'hôtel sordide, ventre loué à prix d\'or pour couple en mal d\'enfant, et qui s\'évapore comme elle était arrivée. Partout, il y a la terreur. Celle d\'un corps mutilé qui gît au fond d\'une fosse creusée dans la forêt. Partout, il y a la terreur. Celle d\'un homme qui connaît le jour et l\'heure de sa mort. Et puis il y a une lettre, comme un manifeste, et qui annonce le pire. S\'engage alors, pour l\'équipe du commandant Sharko, une sinistre course contre la montre. C\'était écrit : l\'enfer ne fait que commencer.');
        $book->setImageName('luca.jpg');
        $book->setAuteur($this->getReference(AuteurFixtures::F_THILLIEZ));
        $manager->persist($book);

        $book = new Livre();
        $book->setTitre('L\'âme du mal');
        $book->setResume('Abandonnés au fond de la forêt ou de hangars vétustes, des cadavres comme on n\'en a jamais vu, mutilés de façon rituelle, porteurs de messages cabalistiques semblables à ceux que laissait derrière lui le bourreau de Portland, avant qu\'une balle dans la tête ne vienne à bout de sa carrière... Le tueur serait-il revenu d\'outre-tombe ? S\'agit-il d\'une secte particulière qui prélève toujours les mêmes morceaux du corps de ses victimes pour d\'étranges cérémonies ?');
        $book->setImageName('ame-du-Mal_.webp');
        $book->setAuteur($this->getReference(AuteurFixtures::M_CHATTAM));
        $manager->persist($book);

        $book = new Livre();
        $book->setTitre('Le coma des mortels');
        $book->setResume('Qui est Pierre ? Et d’ailleurs, se nomme-t-il vraiment Pierre ? Un rêveur ? Un affabulateur ? Un assassin ? Une chose est certaine, on meurt beaucoup autour de lui. Et rarement de mort naturelle.');
        $book->setImageName('Le-Coma-des-mortels.jpg');
        $book->setAuteur($this->getReference(AuteurFixtures::M_CHATTAM));
        $manager->persist($book);

        $book = new Livre();
        $book->setTitre('A Game Of Thrones');
        $book->setResume('Jon Snow, le fils bâtard d\'Eddard s\'engage dans la Garde de Nuit, un ordre chargé de défendre le Mur, frontière nord du royaume, contre les invasions des peuples barbares vivant au-delà, surnommés les « Sauvageons ». Il rejoint Cbookunoir, un des nombreux châteaux du Mur, où il se lie d\'amitié avec Samwell Tarly.');
        $book->setImageName('A game_of_trhones.jpg');
        $book->setAuteur($this->getReference(AuteurFixtures::G_RRMARTIN));
        $manager->persist($book);

        $book = new Livre();
        $book->setTitre('Cabossé');
        $book->setResume('Quand Roy est né, il s’appelait Raymond. C’était à Clermont. Il y a quarante-deux ans. Il avait une sale tronche. Bâti comme un Minotaure, il s’est taillé son chemin dans sa chienne de vie à coups de poing : une vie de boxeur ratée et d’homme de main à peine plus glorieuse. Jusqu’au jour où il rencontre Guillemette, une luciole fêlée qui succombe à son charme, malgré son visage de "tomate écrasée"…');
        $book->setImageName('cabossé.jpg');
        $book->setAuteur($this->getReference(AuteurFixtures::B_PHILIPPON));
        $manager->persist($book);

        $manager->flush();
    }
}
