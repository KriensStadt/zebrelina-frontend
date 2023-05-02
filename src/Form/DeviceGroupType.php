<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Device;
use App\Entity\DeviceGroup;
use App\Repository\DeviceRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class DeviceGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DeviceGroup $device */
        $device = $options['data'];

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'device_group.name',
                'attr' => [
                    'placeholder' => 'device_group.name',
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
                    'label' => 'device_group.password',
                    'attr' => [
                        'placeholder' => 'device_group.password'
                    ],
                ],
                'second_options' => [
                    'label' => 'device_group.repeat_password',
                    'attr' => [
                        'placeholder' => 'device_group.repeat_password'
                    ],
                ],
            ])
            ->add('devices', EntityType::class, [
                'class' => Device::class,
                'label' => 'device_group.devices',
                'help' => 'device_group.devices_help',
                'choice_label' => fn (Device $device) => $device->getName(),
                'query_builder' => function (DeviceRepository $repository): QueryBuilder {
                    return $repository->createQueryBuilder('d')
                        ->addOrderBy('d.name', 'DESC')
                    ;
                },
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeviceGroup::class,
            'translation_domain' => 'forms',
        ]);
    }
}
