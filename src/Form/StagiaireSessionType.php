<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiaireSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('stagiaire', EntityType::class, [
            'class' => Stagiaire::class,
            'choice_label' => 'nom',
            'label' => 'Choisir un stagiaire'
        ])
        ->add('session', EntityType::class, [
            'class' => Session::class,
            'choice_label' => 'nom',
            'label' => 'Choisir une session'
        ])
        ->add('Valider', SubmitType::class, [
            'label' => 'Ajouter le stagiaire à la session',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
