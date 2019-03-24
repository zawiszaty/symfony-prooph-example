<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: zawiszaty
 * Date: 24.03.19
 * Time: 08:49.
 */

namespace App\UI\HTTP\REST\Common\Form;

use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Common\Validator\UniqueValueInEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class CategoryTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'constraints' => [
                new UniqueValueInEntity([
                    'field' => 'name',
                    'entityClass' => CategoryView::class,
                    'message' => 'Ten nazwa jest już zajęta',
                ]),
            ],
        ]);
    }
}
