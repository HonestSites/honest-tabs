<?php

namespace App\Form;

use App\Entity\Link;
use App\Entity\LinkCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $collectionData = $options['data']->getLinkCollection();

    $builder
      ->add('title', null, [
        'attr' => ['class' => 'form-control'],
      ])
      ->add('description', null, [
        'attr' => ['class' => 'form-control'],
      ])
      ->add('baseUrl', null, [
        'attr' => ['class' => 'form-control'],
        'label' => 'URL',
      ])
      ->add('active', null, [
        'attr' => ['class' => 'form-check-input', 'checked' => 'checked'],
      ])
      ->add('allowSharing', null, [
        'attr' => ['class' => 'form-check-input', 'checked' => 'checked'],
      ])
      ->add('linkCollection', EntityType::class, [
        'attr' => ['class' => 'form-control'],
        'class' => LinkCollection::class,
        'choice_label' => 'collectionName',
        'query_builder' => function (EntityRepository $er) use ($collectionData): QueryBuilder {
          return $er->createQueryBuilder('lc')
            ->where('lc.active = :active')
            ->andWhere('lc.id = :collectionId')
            ->setParameter('active', true)
            ->setParameter('collectionId', $collectionData ? $collectionData->getId() : null)
            ->orderBy('lc.collectionName', 'ASC');
        },
      ])
      ->add('siteUsername', TextType::class, [
        'attr' => ['class' => 'form-control'],
      ])
      ->add('sitePassword', PasswordType::class, [
        'attr' => ['class' => 'form-control'],
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Link::class,
    ]);
  }
}
