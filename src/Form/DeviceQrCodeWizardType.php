<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceQrCodeWizardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('device', EntityType::class, [
                'class' => Device::class,
                'label' => 'device_qr_code.device',
                'choice_label' => fn (Device $device) => $device->getName(),
                'query_builder' => function (DeviceRepository $repository): QueryBuilder {
                    return $repository->createQueryBuilder('d')
                        ->addOrderBy('d.name', 'DESC')
                    ;
                },
                'multiple' => false,
            ])
            ->add('password', TextType::class, [
                'label' => 'device_qr_code.password',
                'help' => 'device_qr_code.password_help',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'forms',
        ]);
    }
}
