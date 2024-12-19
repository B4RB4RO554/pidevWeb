<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', null, [
            'label' => false,
            'constraints' => [
                new Assert\NotBlank(['message' => 'La email ne peut pas être vide.']),
            ],
        ])
        ->add('nom', null, [
            'label' => false,
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le nom ne peut pas être vide.']),
            ],
        ])
        ->add('prenom', null, [
            'label' => false,
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le prenom ne peut pas être vide.']),
            ],
        ])
        ->add('numTel', null, [
            'label' => false,
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le numero de telephone ne peut pas être vide.']),
                new Assert\Type(['type' => 'numeric', 'message' => 'Le numero de telephone doit être numérique.']),
            ],
            'invalid_message' => 'Le numero de telephone doit être numérique.'

        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Edit',
            'attr' => ['class' => 'click-btn btn btn-default eddit'],
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
