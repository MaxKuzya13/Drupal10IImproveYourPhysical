<?php

namespace Drupal\kenny_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Url;

/**
 * Provides a 'Anonymous Training Block' block.
 *
 * @Block(
 *   id = "anonymous_training_block",
 *   admin_label = @Translation("Anonymous Training Block"),
 * )
 */
class AnonymousTrainingBlock extends BlockBase  {


  /**
   * {@inheritdoc }
   */
  public function build() {

    $output['#attached']['library'] = 'core/drupal.dialog.ajax';
    $output = [
      '#theme' => 'links',
      '#links' => [
        'link' => [
          'title' => 'Login if u want to add new Training',
          'url' => Url::fromRoute('user.login'),
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

    if ($account->id() !== 0) {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }


}
