<?php

namespace Drupal\kenny_training\Plugin\KennyHeroBlock\Path;

use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path\KennyHeroBlockPathPluginBase;
use Drupal\media\MediaInterface;

/**
 * Hero block for training path
 *
 * @KennyHeroBlockPath(
 *   id = "kenny_hero_block_training",
 *   match_type = "listed",
 *   match_path = {"/training", "/training/all"},
 * )
 */

class KennyHeroBlockTraining extends KennyHeroBlockPathPluginBase {


  /**
   * {@inheritdoc}
   */
  public function getHeroImage() {

    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    $media_image = $media_storage->load(1);
    if ($media_image instanceof MediaInterface) {
      return $media_image->get('field_media_image')->entity->get('uri')->value;

    }
    return;
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroVideo() {

    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    $media_video = $media_storage->load(2);
    if ($media_video instanceof MediaInterface) {
      return  [
        'video/mp4' => $media_video->get('field_media_video_file')->entity->get('uri')->value
      ];
    }
    return;
  }


}