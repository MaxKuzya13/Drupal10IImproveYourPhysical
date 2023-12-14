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
*   id = "kenny_favorite_training_block",
*   admin_label = @Translation("Kenny Favorite Training Block"),
* )
*/
class FavoriteTrainingBlock extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * The favorite manager interface
   *
   * @var \Drupal\kenny_training\Service\Favorite\FavoriteManagerInterface
   */
  protected $favoriteManager;

  /**
   * The current user
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;


  /**
  * Constructs a new FavoriteTrainingBlock.
  *
  * @param array $configuration
  *   A configuration array containing information about the plugin instance.
  * @param string $plugin_id
  *   The plugin_id for the plugin.
  * @param mixed $plugin_definition
  *   The plugin implementation definition.
  * @param \Drupal\kenny_training\Service\Favorite\FavoriteManagerInterface $favorite_manager
  *   The favorite manager service.
  * @param \Drupal\Core\Session\AccountInterface $current_user
  */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FavoriteManagerInterface $favorite_manager, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->favoriteManager = $favorite_manager;
    $this->currentUser = $current_user;
  }

  /**
  * Container by FavoriteTrainingBlock.
  */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('kenny_training.favorite_manager'),
      $container->get('current_user'),
    );
  }

  /**
  * {@inheritdoc}
  */
  public function build() {

    $uid = $this->currentUser->id();
    $favorite_training_plans = $this->favoriteManager->getFavoriteTrainingPlans($uid);

    $view_name = 'favorite_training_plan';
    $display_id = 'block_favorite_training_plan';

    $output = views_embed_view($view_name, $display_id);




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
