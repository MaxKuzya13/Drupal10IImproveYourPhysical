<?php

namespace Drupal\kenny_training\Service\Favorite;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class FavoriteManager implements FavoriteManagerInterface {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function isFavorite($uid, $nid) {
    $database = \Drupal::database();
    $query = $database->select('kenny_favorite_training', 'kft')
      ->fields('kft', ['id'])
      ->condition('uid', $uid)
      ->condition('nid', $nid);
    $result = $query->execute();
    return !empty($result->fetchAssoc());
  }

  /**
   * {@inheritdoc}
   */
  public function setFavorite($uid, $nid) {
    $result = $this->isFavorite($uid, $nid);
    if (empty($result)) {
      $database = \Drupal::database();
      $query = $database->insert('kenny_favorite_training')
        ->fields([
          'uid' => $uid,
          'nid' => $nid,
        ])
        ->execute();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function deleteFavorite($uid, $nid) {
    $database = \Drupal::database();
    $query = $database->delete('kenny_favorite_training')
      ->condition('uid', $uid)
      ->condition('nid', $nid)
      ->execute();
  }
}


