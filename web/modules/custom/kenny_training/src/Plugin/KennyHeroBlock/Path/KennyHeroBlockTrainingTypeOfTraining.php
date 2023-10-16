<?php

namespace Drupal\kenny_training\Plugin\KennyHeroBlock\Path;

use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path\KennyHeroBlockPathPluginBase;
use Drupal\media\MediaInterface;
use Symfony\Component\DependencyInjection\Container;

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
    $last_segment = $this->getCurrentPath();

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
  }

  protected function getCurrentPath() {
    $current_path = \Drupal::request()->getRequestUri();
    $path_segments = explode('/', trim($current_path, '/'));
    $last_segment = end($path_segments);
    return $last_segment;
  }

}