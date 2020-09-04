<?php

namespace App\Form;

use App\DTO\PasswordChangeDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPaswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(PasswordChangeDTO::PREVIOUS_PASSWORD, PasswordType::class)
            ->add(PasswordChangeDTO::NEW_PASSWORD, PasswordType::class)
            ->add(PasswordChangeDTO::REPEAT_NEW_PASSWORD, PasswordType::class)
            ->add('Change', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
