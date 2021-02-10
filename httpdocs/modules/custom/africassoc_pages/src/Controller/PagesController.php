<?php


namespace Drupal\africassoc_pages\Controller;


use Drupal;
use Drupal\block_content\BlockContentInterface;
use Drupal\block_content\Entity\BlockContent;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Menu\MenuLinkTreeElement;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\menu_link_content\Plugin\Menu\MenuLinkContent;
use Drupal\node\Entity\Node;

class PagesController extends ControllerBase {

  public function foraBrusselsPage(): array {
    $menuTree = Drupal::menuTree()
      ->load('space-for-your-activity', new MenuTreeParameters());
    $activitySpace = [];
    foreach ($menuTree as $element) {
      if ($element instanceof MenuLinkTreeElement) {
        $content = $element->link;
        if ($content instanceof MenuLinkContent) {
          $url = $content->getUrlObject()->getUri();
          $title = $element->link->getTitle();
          $link['url'] = $url;
          $link['title'] = $title;
          array_push($activitySpace, $link);
        }
      }
    }
    $language = Drupal::languageManager()->getCurrentLanguage()->getId();
    $fora_blocks = BlockContent::loadMultiple([1, 2, 3, 3, 4, 5]);
    $fora_brussels = [];
    $vgc = [];
    $n22 = [];
    $subsidies = [];
    $membership = [];
    foreach ($fora_blocks as $key => $block) {
      if ($block instanceof BlockContentInterface) {
        $block = $block->hasTranslation($language) ? $block->getTranslation($language) : $block;
      }
      switch ($key) {
        case 1:
          $fora_brussels = $block;
          break;
        case 2:
          $vgc = $block;
          break;
        case 3:
          $n22 = $block;
          break;
        case 4:
          $subsidies = $block;
          break;
        case 5:
          $membership = $block;
          break;
      }
    }
    return [
      '#theme' => 'fora_brussels_page',
      '#fora_brussels' => $fora_brussels,
      '#vgc' => $vgc,
      '#n22' => $n22,
      '#subsidies' => $subsidies,
      '#membership' => $membership,
      '#activitySpace' => $activitySpace,
    ];
  }

  public function developmentPage(): array {
    $pages = [];
    try {
      $storage = Drupal::entityTypeManager()->getStorage('node');
      $query = $storage->getQuery()
        ->condition('type', 'page')
        ->condition('status', Node::PUBLISHED)
        ->condition('field_page_type', 'development');
      $pageIds = $query->execute();
      if (!empty($pageIds)) {
        $pages = Node::loadMultiple($pageIds);
      }
    } catch (InvalidPluginDefinitionException | PluginNotFoundException $e) {
      Drupal::logger('africassoc_pages')->error($e->getMessage());
    }
    return [
      '#theme' => 'development_page',
      '#pages' => $pages
    ];
  }

  public function developmentPageTitle(): TranslatableMarkup {
    return $this->t('Development');
  }

}
