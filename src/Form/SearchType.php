<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, [
                'attr' => [
                    'placeholder' => "Recherche votre véhicule"
                ],
                "required"=>false
            ])
            ->add("kilometrage", RangeType::class, [
                'attr' => [
                    'min' => 10000,
                    'max' => 50000
                ],
            ])
            ->add("prix", RangeType::class, [
                'attr' => [
                    'min' => 100,
                    'max' => 10000
                ],
            ])
            ->add("annee", RangeType::class, [
                'attr' => [
                    'min' => 2000,
                    'max' => date("Y")
                ],
            ])
            ->add("search", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Permet de configuer par défaut 
            // notre formulaire avec la method GET
            'method' => 'POST'
        ]);
    }
}
