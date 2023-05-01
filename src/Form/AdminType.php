<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'admin.username',
                'attr' => [
                    'placeholder' => 'admin.username',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Length(max: 256),
                ],
                'first_options' => [
                    'label' => 'admin.password',
                    'attr' => [
                        'placeholder' => 'admin.password'
                    ],
                ],
                'second_options' => [
                    'label' => 'admin.repeat_password',
                    'attr' => [
                        'placeholder' => 'admin.repeat_password'
                    ],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
            'translation_domain' => 'forms',
        ]);
    }
}
