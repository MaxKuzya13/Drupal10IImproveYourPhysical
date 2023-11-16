<?php

namespace Drupal\kenny_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\kenny_training\Service\Favorite\FavoriteManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'Favorite Training Plan' block.
 *
 * @Block(
 *   id = "kenny_new_training_man_block",
 *   admin_label = @Translation("Kenny New Training Man Block"),
 * )
 */
class NewTrainingManBlock extends BlockBase  {

  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\kenny_training\Form\KennyTrainingPlanForm');
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
