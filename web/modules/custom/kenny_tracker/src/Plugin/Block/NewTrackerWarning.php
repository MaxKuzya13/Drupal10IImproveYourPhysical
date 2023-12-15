<?php

namespace Drupal\kenny_tracker\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'New Tracker Measurements' block.
 *
 * @Block(
 *   id = "kenny_new_tracker_warning_block",
 *   admin_label = @Translation("Kenny New Tracker Warning Block"),
 * )
 */
class NewTrackerWarning extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {

      $output['notification'] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' =>  $this->t('If u want to see tracker, rotate your phone'),
        '#attributes' => [
          'class' => ['tracker__notification'],
        ],
      ];

    return $output;



  }

  /**z
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {

    return AccessResult::allowed();
  }
}
