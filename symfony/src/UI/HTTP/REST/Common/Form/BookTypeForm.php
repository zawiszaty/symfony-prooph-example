<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\Common\Form;

use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Book\Query\Projections\BookView;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Common\Validator\UniqueValueInEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class BookTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ])
            ->add('category', EntityType::class, [
                'class' => CategoryView::class,
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ])
            ->add('author', EntityType::class, [
                'class' => AuthorView::class,
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'constraints' => [
                new UniqueValueInEntity([
                    'field' => 'name',
                    'entityClass' => BookView::class,
                    'message' => 'Ten nazwa jest już zajęta',
                ]),
            ],
        ]);
    }
}
