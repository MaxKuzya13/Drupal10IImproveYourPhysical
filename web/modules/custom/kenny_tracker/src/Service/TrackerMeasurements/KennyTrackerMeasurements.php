<?php

namespace Drupal\kenny_tracker\Service\TrackerMeasurements;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

class KennyTrackerMeasurements implements KennyTrackerMeasurementsInterface {

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
      $container->get('database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isTrack($uid) {
    $database = $this->database->select('kenny_tracker_measurements', 'ktm')
      ->fields('ktm', ['id'])
      ->condition('uid', $uid);
    $result = $database->execute();
    return !empty($result->fetchAssoc());
  }

  /**
   * {@inheritdoc}
   */
  public function setTrack($uid, $nid) {
    $result = $this->isTrack($uid);
    if (empty($result)) {
      $database = $this->database->insert('kenny_tracker_measurements')
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
  public function deleteTrack($uid, $nid) {
    $database = $this->database->delete('kenny_tracker_measurements')
      ->condition('uid', $uid)
      ->condition('nid', $nid)
      ->execute();
  }


  /**
   * {@inheritdoc}
   */
  public function getTrackedMeasurements($uid) {
    $database = $this->database->select("kenny_tracker_measurements", 'ktm')
      ->fields('ktm', ['nid'])
      ->condition('uid', $uid);
    $result = $database->execute();
    return $result->fetchCol();

  }
}


