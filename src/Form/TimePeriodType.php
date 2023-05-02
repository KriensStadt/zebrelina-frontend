<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\TimePeriod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TimePeriodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'time_period.name',
                'attr' => [
                    'placeholder' => 'time_period.name',
                ],
            ])
            ->add('periodStart', DateType::class, [
                'required' => true,
                'label' => 'time_period.period_start',
                'widget' => 'single_text',
            ])
            ->add('periodEnd', DateType::class, [
                'required' => true,
                'label' => 'time_period.period_end',
                'widget' => 'single_text',

                'constraints' => [
                    new Callback(function ($object, ExecutionContextInterface $context) {
                        /** @var FormInterface $root */
                        $root = $context->getRoot();

                        /** @var TimePeriod $timePeriod */
                        $timePeriod = $root->getData();

                        $startDate = $timePeriod->getPeriodStart();
                        $endDate = $timePeriod->getPeriodEnd();

                        if ($endDate < $startDate) {
                            $context
                                ->buildViolation('End date must be before start date')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label' => 'time_period.active',
            ])
            ->add('autoClose', CheckboxType::class, [
                'required' => false,
                'label' => 'time_period.auto_close',
                'help' => 'time_period.auto_close_help',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TimePeriod::class,
            'translation_domain' => 'forms',
        ]);
    }
}
