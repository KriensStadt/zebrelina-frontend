<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Comment;
use App\Entity\CommentType as CommentTypeEntity;
use App\Repository\CommentTypeRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentType', EntityType::class, [
                'class' => CommentTypeEntity::class,
                'label' => 'comment.comment_type',
                'help' => 'comment.comment_type_help',
                'choice_label' => fn (CommentTypeEntity $type) => $type->getName(),
                'query_builder' => function (CommentTypeRepository $repository): QueryBuilder {
                    return $repository->createQueryBuilder('d')
                        ->addOrderBy('d.name', 'ASC')
                    ;
                },
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'label' => 'comment.content',
                'help' => 'comment.content_help',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'translation_domain' => 'forms',
        ]);
    }
}
