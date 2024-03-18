<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('parent_category', ChoiceType::class, [
                'choices' => $options['categories'],
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'name',
                'data' => $options['parentCategoryData'],
                'required' => false,
            ])
            ->add('updatedAt')
            ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'categories' => null,
            'parentCategoryData' => [],
        ]);
    }
}
