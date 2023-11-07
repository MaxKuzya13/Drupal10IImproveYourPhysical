<?php

namespace Drupal\kenny_girls_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\taxonomy\Plugin\views\argument\Taxonomy;

/**
* Provides a 'Favorite Training Plan' block.
*
* @Block(
*   id = "kenny_test_taxonomy_block",
*   admin_label = @Translation("Kenny Test Taxonomy Block"),
* )
*/
class TestTaxonomyTrainingBlock extends BlockBase {


  /**
  * {@inheritdoc}
  */
  public function build() {

    $taxonomy_name = 'girls_training';
    $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $taxonomy = $taxonomy_storage->loadTree($taxonomy_name);

    $top_level_term_ids = [];

    foreach ($taxonomy as $term) {

      if ($term->parents[0] == 0) {
        $top_level_term_ids[] = $term->tid;
      }
    }
    $names = [];
    $body_parts = $taxonomy_storage->loadMultiple($top_level_term_ids);
    foreach ($body_parts as $body_part) {
      $names[] = $body_part->id();
    }

    $lower_body_id = 107;
    $lower_body_parts = [];
    foreach ($taxonomy as $term) {
      if ($term->parents[0] == $lower_body_id) {
        $lower_body_parts[] = $term->tid;
      }
    }

    $lower_body_parts_ids = [];
    $exercise_list = [];
    $load_lower_body_parts = $taxonomy_storage->loadMultiple($lower_body_parts);
    foreach ($load_lower_body_parts as $term) {
      $lower_body_parts_ids[] = $term->id();
      $exercise_list[] = $term->getName();

      foreach ($taxonomy as $tax) {
        if ($tax->parents[0] == $term->id()) {
          $exercise_list[] = $tax->tid;
        }
      }

    }




  }

  /**z
  * {@inheritdoc}
  */
  protected function blockAccess(AccountInterface $account) {
  // Check access permission for the block, return AccessResult::forbidden() if needed.
  // Example:
  // if ($account->hasPermission('access favorite training plans')) {
  //   return AccessResult::allowed();
  // }
  return AccessResult::allowed();
  }
}
