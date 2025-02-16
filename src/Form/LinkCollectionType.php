<?php

  namespace App\Form;

  use App\Entity\Category;
  use App\Entity\LinkCollection;
  use Symfony\Bridge\Doctrine\Form\Type\EntityType;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;

  class LinkCollectionType extends AbstractType
  {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $builder
        ->add('collectionName', null, [
          'attr' => ['class' => 'form-control'],
        ])
        ->add('category', EntityType::class, [
          'attr' => ['class' => 'form-control'],
          'class' => Category::class,
          'choice_label' => 'categoryName',
        ])
        ->add('active', null, [
          'attr' => ['class' => 'form-check-input', 'checked' => 'checked'],
        ])
        ->add('allowSharing', null, [
          'attr' => ['class' => 'form-check-input', 'checked' => 'checked'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
      $resolver->setDefaults([
        'data_class' => LinkCollection::class,
      ]);
    }
  }
