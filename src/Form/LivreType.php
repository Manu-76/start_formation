<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Une fois Vich ok, on insére cette prop
            ->add('imageFile', FileType::class, [
                'label' => 'Photo du livre',
                'required' => false
            ])
            // On met les types de form et attributs
            ->add('titre')
            ->add('resume')
            // le builder a déjà mis le type de form ainsi que les attributs
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                // On peut effectuer une petite méthode pour afficher le prénom et le nom
                'choice_label' => function (Auteur $auteur) {
                    return $auteur->getPrenom() . ' ' . $auteur->getNom();
                }
                // On indique comme choix de column le nom de l'auteur et nom l'id
                // 'choice_label' => 'nom',
            ])
            ->add('tarif', NumberType::class, [
                'label' => 'Prix du livre',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
