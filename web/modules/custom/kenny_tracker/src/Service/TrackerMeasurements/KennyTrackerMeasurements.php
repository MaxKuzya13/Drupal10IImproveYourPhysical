<?php

namespace Drupal\kenny_tracker\Service\TrackerMeasurements;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class KennyTrackerMeasurements implements KennyTrackerMeasurementsInterface {

  /**
   * The database.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface;
   */
  protected $entityTypeManager;


  /**
   * Construct a database instance
   */
  public function __construct(Connection $database, EntityTypeManagerInterface $entity_type_manager) {
    $this->database = $database;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Create a new static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity_type.manager'),
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

  public function setRelevantMeasurements($measurement_id, $uid, $date) {


    $tracker_measurements_ids = $this->entityTypeManager
      ->getStorage('node')
      ->getQuery()
      ->condition('type', 'tracker_measurements')
      ->condition('field_uid', $uid)
      ->condition('field_created', $date, '<')
      ->accessCheck('FALSE')
      ->execute();

    foreach ($tracker_measurements_ids as $tracker_measurements_id) {
      $tracker_measurements = $this->entityTypeManager
        ->getStorage('node')->load($tracker_measurements_id);
      $tracker_measurements->field_relevant_measurements[] = [
        'target_id' => $measurement_id,
      ];
      $tracker_measurements->save();
    }
  }

  public function getProgressOverTime($nid) {

    $node_storage = $this->entityTypeManager->getStorage('node');
    $tracker = $node_storage->load($nid);

    $relevant_measurements = $tracker->get('field_relevant_measurements')->referencedEntities();
    if ($relevant_measurements) {
      $last_measurement = end($relevant_measurements);
    }

    $started_measurements = $tracker->get('field_current_measurements')->entity;
    $tracker_measurement = $tracker->get('field_tracker_measurement')->referencedEntities();

    $progression_values = [];

    if ($last_measurement) {

      foreach ($tracker_measurement as $tm) {
        $name = $tm->get('field_measurement_name')->value;
        $tracker_name = strtolower($name);

        $started_value = $started_measurements->get("field_{$tracker_name}")->value;
        $relevant_values = $last_measurement->get("field_{$tracker_name}")->value;
        $progression_values[$tracker_name] = $relevant_values - $started_value;
      }

      $created_start = $tracker->get('field_created')->value;
      $date_created = new \DateTime($created_start);
      $created_relative = $last_measurement->get('field_created')->value;
      $date_relative = new \DateTime($created_relative);

      $interval = $date_relative->diff($date_created);

      if ($interval->days > 30) {
        // Якщо більше 30 днів, виводимо в місяцях та днях.
        $months = $interval->m + $interval->y * 12;
        $timeline = $months . ' months ' . $interval->d . ' days';
      } else {
        // Якщо менше 30 днів, виводимо в днях.
        $timeline = $interval->days . ' days';
      }

      $progression_values['date'] = $timeline;

    } else {
      foreach ($tracker_measurement as $tm) {
        $name = $tm->get('field_measurement_name')->value;
        $tracker_name = strtolower($name);

        $started_value = $started_measurements->get("field_{$tracker_name}")->value;
        $relevant_values = $tm->get("field_measurement_value")->value;
        $progression_values[$tracker_name] = $relevant_values - $started_value;
      }


    }


    return $progression_values;

  }

  public function getDesiredResult($nid) {

    $node_storage = $this->entityTypeManager->getStorage('node');
    $tracker = $node_storage->load($nid);


    $started_measurements = $tracker->get('field_current_measurements')->entity;
    $tracker_measurement = $tracker->get('field_tracker_measurement')->referencedEntities();

    $progression_values = [];

    foreach ($tracker_measurement as $tm) {
      $name = $tm->get('field_measurement_name')->value;
      $tracker_name = strtolower($name);

      $started_value = $started_measurements->get("field_{$tracker_name}")->value;
      $relevant_values = $tm->get("field_measurement_value")->value;
      $progression_values[$tracker_name] = $relevant_values - $started_value;
    }


    return $progression_values;

  }

  public function isStillLeft($nid) {

    $node_storage = $this->entityTypeManager->getStorage('node');
    $tracker = $node_storage->load($nid);

    $relevant_measurements = $tracker->get('field_relevant_measurements')->referencedEntities();
    if ($relevant_measurements) {
      $last_measurement = end($relevant_measurements);
    }

    $tracker_measurement = $tracker->get('field_tracker_measurement')->referencedEntities();

    $progression_values = [];

    if ($last_measurement) {

      foreach ($tracker_measurement as $tm) {
        $name = $tm->get('field_measurement_name')->value;
        $tracker_name = strtolower($name);

        $decired_value = $tm->get("field_measurement_value")->value;
        $relevant_values = $last_measurement->get("field_{$tracker_name}")->value;
        $progression_values[$tracker_name] = $decired_value - $relevant_values;
      }


    } else {
     return null;


    }


    return $progression_values;


  }

  public function selectedFields($id) {
    $tracker_measurements = $this->entityTypeManager->getStorage('node')
      ->load($id);

    $decired_measurements = $tracker_measurements->get('field_tracker_measurement')->referencedEntities();

    $started_field_names = [];
    foreach ($decired_measurements as $desired) {
      $field_name = strtolower($desired->get('field_measurement_name')->value);
      $started_field_names['group'][] = $desired->get('field_measurement_name')->value;
      $started_field_names['fields'][] = "field_{$field_name}";

    }

    return $started_field_names;

  }

  public function getStarted($id, $selected_fields) {
    $tracker_measurements = $this->entityTypeManager->getStorage('node')
      ->load($id);

    $started_measurements = $tracker_measurements
      ->get('field_current_measurements')->referencedEntities();

    $node = reset($started_measurements);
    $values = [];
    foreach ($selected_fields as $field) {
      $values[$field] = $node->get($field)->value;
    }
    return $values;
  }

  public function getRelative($id, $selected_fields) {
    $tracker_measurements = $this->entityTypeManager->getStorage('node')
      ->load($id);

    $relevant_measurements = $tracker_measurements
      ->get('field_relevant_measurements')->referencedEntities();


    $values = [];
      foreach ($relevant_measurements as $sm) {
          foreach ($selected_fields as $field) {
            $values[$field][] = $sm->get($field)->value;
          }
        $values['date'][] = $sm->get('field_created')->value;
      }

    return $values;
  }


  public function getDecired($id) {
    $tracker_measurements = $this->entityTypeManager->getStorage('node')
      ->load($id);

    $decired_measurements = $tracker_measurements
      ->get('field_tracker_measurement')->referencedEntities();


    $values = [];
    foreach ($decired_measurements as $tm) {
      $name = $tm->get('field_measurement_name')->value;
      $tracker_name = "field_" . strtolower($name);
      $values[$tracker_name] = $tm->get("field_measurement_value")->value;
    }

    return $values;
  }


}


