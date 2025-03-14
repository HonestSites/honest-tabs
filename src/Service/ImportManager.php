<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Link;
use App\Entity\LinkCollection;
use App\Entity\Organization;
use App\Repository\CategoryRepository;
use App\Repository\LinkCollectionRepository;
use App\Repository\LinkRepository;
use App\Repository\OrganizationRepository;

class ImportManager
{
  private $organization;
  private $category;
  private $linkCollection;

  public function __construct(
    private readonly OrganizationRepository   $organizationRepository,
    private readonly CategoryRepository       $categoryRepository,
    private readonly LinkCollectionRepository $linkCollectionRepository,
    private readonly LinkRepository           $linkRepository,
    private readonly AuthenticationManager    $authenticationManager,
    private readonly PasswordManager          $passwordManager
  )
  {

  }

  public function importBitWarden($data)
  {
    try {

      if (isset($data['fileContents'])) {
        $fileContent = json_decode($data['fileContents'], true);
        $this->getOrg('IMPORT');
        $this->getCat('IMPORT');
        $this->getCollection('BIT-WARDEN');

        foreach ($fileContent['items'] as $item) {
          $objLink = new Link();
          $objLink->setLinkCollection($this->linkCollection);
          $objLink->setTitle($item['name']);
          $objLink->setDescription($item['notes']);
          $objLink->setBaseUrl($this->getUrl($item));
          $objLink->setActive(true);
          $objLink->setAllowSharing(false);
          $objLink->setSiteUsername($this->getUserName($item));
          $objLink->setSitePassword($this->getPassword($item));

          if ($objLink->getSitePassword()) {
            $encData = $this->passwordManager->encryptPassword($objLink->getSitePassword());
            $objLink->setSitePassword($encData['cipherText']);
            $objLink->setEncData($encData);
          }

          $this->linkRepository->save($objLink);
        }
      }
    } catch (\Exception $e) {
      dd($e->getMessage());
    }
  }

  private function getPassword($item)
  {
    $returnPassword = null;
    if (isset($item['login']) && isset($item['login']['password'])) {
      $returnPassword = $item['login']['password'];
    }
    return $returnPassword;
  }

  private function getUserName($item)
  {
    $returnUsername = null;
    if (isset($item['login']) && isset($item['login']['username'])) {
      $returnUsername = $item['login']['username'];
    }
    return $returnUsername;
  }


  private function getUrl($item)
  {
    $returnUri = '';
    if (isset($item['login']) && isset($item['login']['uris']) && isset($item['login']['uris'][0])) {
      $returnUri = $item['login']['uris'][0]['uri'];
    }
    return $returnUri;
  }

  private function getCollection($collectionName)
  {
    if ($this->organization) {
      $collection = $this->linkCollectionRepository->findOneBy(['category' => $this->category, 'collectionName' => $collectionName]);
      if (!$collection) {
        $collection = new LinkCollection();
        $collection->setCollectionName($collectionName);
        $collection->setCategory($this->category);
        $collection->setAllowSharing(false);
        $collection->setActive(true);
        $this->linkCollectionRepository->save($collection);
      }

      $this->linkCollection = $collection;
    }
  }

  private function getCat($catName)
  {
    if ($this->organization) {
      $cat = $this->categoryRepository->findOneBy(['organization' => $this->organization, 'categoryName' => $catName]);
      if (!$cat) {
        $cat = new Category();
        $cat->setOrganization($this->organization);
        $cat->setCategoryName($catName);
        $cat->setAllowSharing(false);
        $cat->setActive(true);
        $this->categoryRepository->save($cat);
      }

      $this->category = $cat;
    }
  }


  private function getOrg($orgName)
  {
    $org = $this->organizationRepository->findOneByOrganizationName($orgName);
    if (!$org) {
      $org = new Organization();
      $org->setOrganizationName($orgName);
      $org->setActive(true);
      $org->setAllowSharing(false);
      $org->setOwner($this->authenticationManager->getUser());
      $org = $this->organizationRepository->save($org);
    }

    $this->organization = $org;
  }
}