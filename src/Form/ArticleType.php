<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,array(
                    'label'=>'Nom du produit',
                    'attr'=>
                    ['pattern'=>'[a-z A-z]*'],
            ))
            ->add('quantite',IntegerType::class,array(
                    'label'=>'Quantite',
                    'attr'=>
                    [
                        'min'=>1
                    ],
            ))
            ->add('prix',IntegerType::class,array(
                    'label'=>'Prix Unitaire',
                'attr'=>
                    [
                        'min'=>1
                    ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
