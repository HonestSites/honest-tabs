<?php

  namespace App\Form;

  use App\Entity\Category;
  use App\Entity\LinkCollection;
  use App\Lib\AppSession;
  use App\Service\AuthenticationManager;
  use Doctrine\ORM\EntityRepository;
  use Doctrine\ORM\QueryBuilder;
  use Symfony\Bridge\Doctrine\Form\Type\EntityType;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;

  class LinkCollectionType extends AbstractType
  {
    public function __construct(private readonly AuthenticationManager $authManager)
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

      $organizationId = AppSession::getSessionData('activeOrgId');

      $builder
        ->add('collectionName', null, [
          'attr' => ['class' => 'form-control'],
        ])
        ->add('category', EntityType::class, [
          'attr' => ['class' => 'form-control'],
          'class' => Category::class,
          'choice_label' => 'categoryName',
          'query_builder' => function (EntityRepository $er) use ($organizationId): QueryBuilder {
            return $er->createQueryBuilder('c')
              ->leftJoin('App\Entity\Organization', 'o', \Doctrine\ORM\Query\Expr\Join::WITH, 'c.organization = o.id')
              ->where('o.owner = :owner')
              ->andWhere('c.organization = :organizationId')
              ->orderBy('c.categoryName', 'ASC')
              ->setParameter('owner', $this->authManager->getUser())
              ->setParameter('organizationId', $organizationId);
          },
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
