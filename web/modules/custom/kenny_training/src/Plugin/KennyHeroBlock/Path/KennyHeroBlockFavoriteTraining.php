<?php

namespace Drupal\kenny_training\Plugin\KennyHeroBlock\Path;

use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path\KennyHeroBlockPathPluginBase;
use Drupal\media\MediaInterface;

/**
 * Hero block for training path
 *
 * @KennyHeroBlockPath(
 *   id = "kenny_hero_block_favorite_training",
 *   match_type = "listed",
 *   match_path = {"/favorite-training"},
 * )
 */

class KennyHeroBlockFavoriteTraining extends KennyHeroBlockPathPluginBase {


  /**
   * {@inheritdoc}
   */
  public function getHeroImage() {

    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    $media_image = $media_storage->load(9);
    if ($media_image instanceof MediaInterface) {
      return $media_image->get('field_media_image')->entity->get('uri')->value;

    }
    return;
  }




}