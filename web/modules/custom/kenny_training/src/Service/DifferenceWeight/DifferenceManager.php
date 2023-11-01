<?php

namespace Drupal\kenny_training\Service\DifferenceWeight;

use Drupal\node\NodeInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\ParagraphInterface;

class DifferenceManager implements DifferenceManagerInterface {

  public function getCurrentParagraph($pid) {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $paragraph */
    $paragraph = \Drupal::entityTypeManager()->getStorage('paragraph')->load($pid);
    if ($paragraph instanceof ParagraphInterface) {
      $exercise = $paragraph->get('field_exercise')->entity->id();

      $type_of_training_id = $this->getTypeOfTrainingId($pid);

      $weight = $paragraph->get('field_weight')->value;
      $relative_weight = $this->getRelativeWeight($pid, $exercise, $type_of_training_id);

      if ($weight >= $relative_weight) {
        $difference = '+ ' . $weight - $relative_weight;
      } else {
          $difference = '- ' . $relative_weight - $weight;
      }

      return $difference;
    }

    return null;
  }

  public function getTypeOfTrainingId($pid) {

    $nodes_using_paragraph = [];

    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple();
    foreach ($nodes as $node) {
      // Перевірте, чи в даному вузлі є поле, яке містить параграф.
      $field_name = 'field_exercises'; // Замініть на реальне ім'я поля, де зберігаються параграфи.

      if ($node->hasField($field_name)) {
        $items = $node->get($field_name);

        // Перевірте кожен параграф у полі.
        foreach ($items as $item) {
          if ($item->target_id == $pid) {
            // Збережіть інформацію про вузол, в якому знайдено параграф.
            $nodes_using_paragraph[] = $node;
          }
        }
      }
    }

    foreach ($nodes_using_paragraph as $node) {
      if ($node->hasField('field_type_of_training')) {
        $field_value = $node->get('field_type_of_training')->getValue();

        foreach ($field_value as $item) {
          // Тут ви можете отримати значення поля 'field_training_of_type'.
          $training_type = $item['target_id']; // Або будь-яке інше значення, яке вам потрібно.
          // Зробіть що-небудь із $training_type.
        }
        return $training_type;
      }
    }

   return [];
  }

  public function getRelativeWeight($pid, $exercise, $type_of_training_id) {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'training_plan')
      ->accessCheck('FALSE')
      ->condition('status', NodeInterface::PUBLISHED);


    $query->condition('field_type_of_training', $type_of_training_id);

    $nids = $query->execute();

    $result_pids = [];
    foreach ($nids as $nid) {
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      if ($node && $node->hasField('field_exercises')) {
        $field_exercises = $node->get('field_exercises')->referencedEntities();
        foreach ($field_exercises as $paragraph) {
          if ($paragraph->hasField('field_exercise') && $paragraph->get('field_exercise')->target_id == $exercise) {
            $result_pids[] = $paragraph->id();

          }
        }

      }
    }

    rsort($result_pids);
    $key = array_search($pid, $result_pids);
    if ($key !== false && $key < count($result_pids) - 1) {
      $relative_paragraph = $result_pids[$key + 1];
    } else {
      $relative_paragraph = [];
    }

    if(!empty($relative_paragraph)) {
      $paragraph = Paragraph::load($relative_paragraph);
      $relative_weight = $paragraph->get('field_weight')->value;
    } else {
      $relative_weight = 1;
    }

    return $relative_weight;
  }
}