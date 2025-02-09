<?php

  namespace App\Form;

  use App\Entity\Category;
  use App\Entity\Organization;
  use Symfony\Bridge\Doctrine\Form\Type\EntityType;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;

  class CategoryType extends AbstractType
  {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $builder
        ->add('categoryName', null, [
          'attr' => ['class' => 'form-control'],
        ])
        ->add('organization', EntityType::class, [
          'attr' => ['class' => 'form-control'],
          'class' => Organization::class,
          'choice_label' => 'organizationName',
        ])
        ->add('active', null, [
          'attr' => ['class' => 'form-check-input'],
        ])
        ->add('allow_sharing', null, [
          'attr' => ['class' => 'form-check-input'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
      $resolver->setDefaults([
        'data_class' => Category::class,
      ]);
    }
  }
