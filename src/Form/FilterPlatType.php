<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterPlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vegetarien', CheckboxType::class, [
                'required' => false,
                'label' => 'Végétarien'
            ])
            ->add('vegan', CheckboxType::class, [
                'required' => false,
                'label' => 'Vegan'
            ])
            ->add('pescetarien', CheckboxType::class, [
                'required' => false,
                'label' => 'Pescetarien'
            ])
            ->add('soja', CheckboxType::class, [
                'required' => false,
                'label' => 'Soja'
            ])
            ->add('poisson', CheckboxType::class, [
                'required' => false,
                'label' => 'Poisson'
            ])
            ->add('fruits_coques', CheckboxType::class, [
                'required' => false,
                'label' => 'Fruits à coques'
            ])
            ->add('gluten', CheckboxType::class, [
                'required' => false,
                'label' => 'Gluten'
            ])
            ->add('mollusques', CheckboxType::class, [
                'required' => false,
                'label' => 'Mollusques'
            ])
            ->add('celeri', CheckboxType::class, [
                'required' => false,
                'label' => 'Céléri'
            ])
            ->add('crustaces', CheckboxType::class, [
                'required' => false,
                'label' => 'Crustacés'
            ])
            ->add('oeuf', CheckboxType::class, [
                'required' => false,
                'label' => 'Oeufs'
            ])
            ->add('arachide', CheckboxType::class, [
                'required' => false,
                'label' => 'Arachide'
            ])
            ->add('lupin', CheckboxType::class, [
                'required' => false,
                'label' => 'Lupin'
            ])
            ->add('moutarde', CheckboxType::class, [
                'required' => false,
                'label' => 'Moutarde'
            ])
            ->add('produits_laitiers', CheckboxType::class, [
                'required' => false,
                'label' => 'Produits laitiers'
            ])
            ->add('valider', SubmitType::class)
        ;
    }
}