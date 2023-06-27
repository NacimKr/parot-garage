<?php

namespace App\Form;

use App\Entity\Hours;
use App\Entity\Week;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Regex;

class HoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('days', EntityType::class, [
                "class" => Week::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                "mapped" => false,
                'attr' => [
                    'disabled' => true
                ]
            ])
            ->add('matin_open',null,[
                'constraints' => [new Regex([
                    'pattern' => '/^([01]\d|2[0-3]):([0-5]\d)$/'
                    ])],
            ])
            ->add('matin_close',null)
            ->add('aprem_open',null)
            ->add('aprem_close',null)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hours::class,
        ]);
    }
}
