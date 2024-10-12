<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // On spécifie plus précisement le type de champs et on peut y passer pleins d'options
            ->add('prenom', TextType::class, [
                'label' => 'Entrez le prénom de l\'auteur',
                'required' => false    
            ])
            ->add('nom', TextType::class, [
                'label' => 'Entrez le nom de l\'auteur ou le pseudo si pas de prénom',
                // required est sur true par défaut, pas nécessaire de l'indiquer ici
            ])
            // ->add('prenom')
            // ->add('nom')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
