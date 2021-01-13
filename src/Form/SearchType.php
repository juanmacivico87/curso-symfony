<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchInput', TextType::class, [
                'label'         => null,
                'attr'          => [
                    'placeholder'   => 'Search...',
                    'class'         => 'form-control mr-sm-2',
                ],
            ])
            ->add('searchButton', SubmitType::class, [
                'label' => 'Search',
                'attr'  => [
                    'class' => 'btn-outline-primary my-2 my-sm-0',
                ],
            ])
        ;
    }
}
