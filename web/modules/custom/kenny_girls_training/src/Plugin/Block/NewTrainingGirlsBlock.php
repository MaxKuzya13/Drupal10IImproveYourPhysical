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
 *   id = "kenny_new_training_girls_block",
 *   admin_label = @Translation("Kenny New Training Girls Block"),
 * )
 */
class NewTrainingGirlsBlock extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\kenny_girls_training\Form\KennyGirlsTrainingForm');
    $output['form'] = $form;

    return $output;



  }

  /**z
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {

    return AccessResult::allowed();
  }
}
