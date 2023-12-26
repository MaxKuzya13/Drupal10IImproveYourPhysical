<?php

namespace Drupal\kenny_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Url;

/**
 * Provides a 'Favorite Training Plan' block.
 *
 * @Block(
 *   id = "kenny_new_training_man_block",
 *   admin_label = @Translation("Kenny New Training Man Block"),
 * )
 */
class NewTrainingManBlock extends BlockBase  {


  /**
   * {@inheritdoc }
   */
  public function build() {

    $output['#attached']['library'] = 'core/drupal.dialog.ajax';
    $output = [
      '#theme' => 'links',
      '#links' => [
        'link' => [
          'title' => 'Create a new training',
          'url' => Url::fromRoute('kenny_training.new_man_training'),
          'attributes' => [
            'class' => ['use-ajax'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => json_encode(['height' => 600, 'width' => 600]),
          ],
        ],
      ]
    ];

    return $output;

  }

  /**z
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {

    if ($account->id() == 0) {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }


}
