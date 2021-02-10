<?php


namespace Drupal\africassoc_utils\Perm;


use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ForaPermissions implements ContainerInjectionInterface {

  use StringTranslationTrait;

  protected $entityManager;

  /**
   * ForaPermissions constructor.
   *
   * @param $entityManager
   */
  public function __construct(EntityTypeManager $entityManager) {
    $this->entityManager = $entityManager;
  }

  public static function create(ContainerInterface $container): ForaPermissions {
    return new static($container->get('entity_type.manager'));
  }

  public function permissions() {
    $permissions = [];
    $allEvents = $this->entityManager->getStorage('node')
      ->getQuery()
      ->condition('type', 'event')
      ->execute();
    foreach ($allEvents as $event) {
      $permissions += [

      ];
    }
  }
}
