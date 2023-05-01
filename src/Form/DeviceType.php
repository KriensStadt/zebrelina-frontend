<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Device;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class DeviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Device $device */
        $device = $options['data'];

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'device.name',
                'attr' => [
                    'placeholder' => 'device.name',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => !$device->getName(),
                'constraints' => [
                    new Length(max: 256),
                ],
                'first_options' => [
                    'label' => 'device.password',
                    'attr' => [
                        'placeholder' => 'device.password'
                    ],
                ],
                'second_options' => [
                    'label' => 'device.repeat_password',
                    'attr' => [
                        'placeholder' => 'device.repeat_password'
                    ],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Device::class,
            'translation_domain' => 'forms',
        ]);
    }
}
