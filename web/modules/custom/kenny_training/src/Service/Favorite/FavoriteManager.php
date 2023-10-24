<?php

namespace Drupal\kenny_training\Service\Favorite;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

class FavoriteManager implements FavoriteManagerInterface {

  /**
   * The database.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Construct a database instance
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Create a new static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isFavorite($uid, $nid) {
    $database = $this->database->select('kenny_favorite_training', 'kft')
      ->fields('kft', ['id'])
      ->condition('uid', $uid)
      ->condition('nid', $nid);
    $result = $database->execute();
    return !empty($result->fetchAssoc());
  }

  /**
   * {@inheritdoc}
   */
  public function setFavorite($uid, $nid) {
    $result = $this->isFavorite($uid, $nid);
    if (empty($result)) {
      $database = $this->database->insert('kenny_favorite_training')
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
    $database = $this->database->delete('kenny_favorite_training')
      ->condition('uid', $uid)
      ->condition('nid', $nid)
      ->execute();
  }


  /**
   * {@inheritdoc}
   */
  public function getFavoriteTrainingPlans($uid) {
    $database = $this->database->select("kenny_favorite_training", 'kft');
    $database->addField('kft', 'nid');
    $database->condition('kft.uid', $uid);
    $result = $database->execute();
    return $result->fetchCol();

  }
}


