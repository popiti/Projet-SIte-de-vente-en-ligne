<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MonProfile extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login',TextType::class,[
                'label'=>'Login',
                'attr'=>['placeholder'=>'Entrez son login']
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label'=>'Password',
                'mapped' => false,
                'required'=>false,
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->add('nom',TextType::class,[
                'label'=>'Nom',
                'required'=>false,
                'attr'=>['placeholder'=>'Entrez son nom']
            ])
            ->add('prenom',TextType::class, [
                'label'=>'Prenom',
                'required'=>false,
                'attr'=>['placeholder'=>'Entrez son prenom']
            ])
            ->add('birthdate',BirthdayType::class,[
                'label'=>'Date de naissance',
                'format'=>'dd/MM/yyyy',
                'attr'=>['placeholder'=>'Entrez sa date de naissance']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
