<?php

namespace Drupal\kenny_training\Plugin\KennyHeroBlock\Path;

use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path\KennyHeroBlockPathPluginBase;
use Drupal\media\MediaInterface;
use Drupal\media\MediaStorage;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * Hero block for training path for each type of training
 *
 * @KennyHeroBlockPath(
 *   id = "kenny_hero_block_training_type_of_training",
 *   match_type = "listed",
 *   match_path = {"/training/force", "/training/intensive"},
 *   weight = 100,
 * )
 */

class KennyHeroBlockTrainingTypeOfTraining extends KennyHeroBlockPathPluginBase {


  /**
   * {@inheritdoc}
   */
  public function getHeroImage() {
    $last_segment = $this->getLastSegment();
    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    switch ($last_segment) {
      case 'intensive':
        $media_image = $media_storage->load(9);
        break;
      case 'force':
        $media_image = $media_storage->load(10);
        break;
    }

    if ($media_image instanceof MediaInterface) {
      return $media_image->get('field_media_image')->entity->get('uri')->value;
    }
    return;
  }

  /**
   * Get last segment of current path
   *
   * @return false|string
   */
  protected function getLastSegment() {
    $current_path = $this->getRequest()->getRequestUri();
    $path_segments = explode('/', trim($current_path, '/'));
    $last_segment = end($path_segments);
    return $last_segment;
  }

}