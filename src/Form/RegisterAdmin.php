<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterAdmin extends AbstractType
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
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('nom',TextType::class,[
                'label'=>'Nom',
                'attr'=>[
                    'placeholder'=>'Entrez son nom',
                    'pattern'=>'[a-z A-z]*'],
            ])
            ->add('prenom',TextType::class, [
                'label'=>'Prenom',
                'attr'=>[
                    'placeholder'=>'Entrez son prenom',
                    'pattern'=>'[a-z A-z]*'],
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