<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'email',
                'attr' => ['placeholder' => 'Email'],
            ])
            ->add('nom', null, [
                'label' => 'nom',
                'attr' => ['placeholder' => 'Nom'],
            ])
            ->add('prenom', null, [
                'label' => 'prenom',
                'attr' => ['placeholder' => 'Prenom'],
            ])
            ->add('numTel', null, [
                'label' => 'numTel',
                'attr' => ['placeholder' => 'NumTel'],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Client' => 'ROLE_CLIENT',
                    'Artist' => 'ROLE_ARTIST',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
//            ->add('role', ChoiceType::class, [
//                'choices' => [
//                    'Client' => 'ROLE_CLIENT',
//                    'Artist' => 'ROLE_ARTIST',
//                    'Admin' => 'ROLE_ADMIN',
//                ],
//                'expanded' => false,
//                'multiple' => false,
//                'choice_attr' => function ($choice, $key, $value) {
//                    // Define images for each role
//                    $imageMap = [
//                        'Client' => 'roles-icons/icon-admin.png',
//                        'Artist' => 'roles-icons/icon-artist.png',
//                        'Admin' => 'roles-icons/icon-admin.png',
//                    ];
//
//                    // Check if the role exists in the image map
//                    if (array_key_exists($value, $imageMap)) {
//                        // If it exists, return the data-image attribute with the corresponding image path
//                        return ['data-image' => $imageMap[$value]];
//                    }
//
//                    // If the role is not found in the image map, return an empty array
//                    return [];
//                },
//            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password','placeholder' => 'Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
