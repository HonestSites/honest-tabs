<?php

  namespace App\Form;

  use App\Entity\Category;
  use App\Entity\Organization;
  use App\Service\AuthenticationManager;
  use Doctrine\ORM\EntityRepository;
  use Doctrine\ORM\QueryBuilder;
  use Symfony\Bridge\Doctrine\Form\Type\EntityType;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;

  class CategoryType extends AbstractType
  {

    public function __construct(private readonly AuthenticationManager $authManager)
    {}

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
          'query_builder' => function (EntityRepository $er): QueryBuilder {
            return $er->createQueryBuilder('o')
              ->where('o.active = :active')
              ->andWhere('o.owner = :owner')
              ->setParameter('active', true)
              ->setParameter('owner', $this->authManager->getUser())
              ->orderBy('o.organizationName', 'ASC');
          },
        ])
        ->add('active', null, [
          'attr' => ['class' => 'form-check-input', 'checked' => 'checked'],
        ])
        ->add('allow_sharing', null, [
          'attr' => ['class' => 'form-check-input', 'checked' => 'checked'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
      $resolver->setDefaults([
        'data_class' => Category::class,
      ]);
    }
  }
