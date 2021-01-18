<?php

namespace App\Form;

use App\Entity\Bookmark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class BookmarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
            ->add('category')
            ->add('tag', Select2EntityType::class, [
                'multiple'              => true,
                'remote_route'          => 'app_tags_search',
                'class'                 => 'App\Entity\Tag',
                'primary_key'           => 'id',
                'text_property'         => 'name',
                'minimum_input_length'  => 3,
                'delay'                 => 3,
                'cache'                 => false,
                'placeholder'           => 'Select tags',
                'allow_add'             => [
                    'enabled'       => true,
                    'new_tag_text'  => '(new)',
                    'tag_separators' => '[","]',
                ],
            ])
            ->add('isFavourite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bookmark::class,
        ]);
    }
}
