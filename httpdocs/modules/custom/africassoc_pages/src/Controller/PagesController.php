<?php


namespace Drupal\africassoc_pages\Controller;


use Drupal;
use Drupal\block_content\BlockContentInterface;
use Drupal\block_content\Entity\BlockContent;
use Drupal\Core\Controller\ControllerBase;

class PagesController extends ControllerBase {

  public function foraBrusselsPage(): array {
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
    ];
  }
}
