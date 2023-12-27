<?php

namespace Drupal\kenny_measurements\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Url;

/**
 * Provides a 'New Measurement' block.
 *
 * @Block(
 *   id = "kenny_new_measurement_block",
 *   admin_label = @Translation("Kenny New Measurement Block"),
 * )
 */
class NewMeasurementBlock extends BlockBase  {


  /**
   * {@inheritdoc }
   */
  public function build() {

    $output['#attached']['library'] = 'core/drupal.dialog.ajax';
    $output['new_measurement'] = [
      '#theme' => 'links',
      '#links' => [
        'link' => [
          'title' => $this->t('Enter your a new measurement'),
          'url' => Url::fromRoute('kenny_measurements.form'),
          'attributes' => [
            'class' => ['use-ajax', 'create-a-new-measurement'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => json_encode(['height' => 600, 'width' => '50vw']),
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
