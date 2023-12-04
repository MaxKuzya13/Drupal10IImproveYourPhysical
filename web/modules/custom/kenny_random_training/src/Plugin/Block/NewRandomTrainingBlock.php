<?php

namespace Drupal\kenny_random_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Url;
use Drupal\taxonomy\Plugin\views\argument\Taxonomy;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Favorite Training Plan' block.
 *
 * @Block(
 *   id = "kenny_new_random_training_block",
 *   admin_label = @Translation("Kenny New Random Training Block"),
 * )
 */
class NewRandomTrainingBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected FormBuilderInterface $formBuilder;

  /**
   * Constructor by NewTrainingGirlsBlock.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
  }

  /**
   * Container by NewTrainingGirlsBlock.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $output = [];
    $form_pick = \Drupal::formBuilder()->getForm('Drupal\kenny_random_training\Form\KennyPickRandomTraining');
    $output['form_pick'] = $form_pick;

    // Отримати значення, яке приходить з форми, якщо воно встановлено в сесії.
    $value = isset($_SESSION['kenny_pick_random_training_form_value']) ? $_SESSION['kenny_pick_random_training_form_value'] : '';
    $limit = !empty($value) ? $value : 'girls';
    // Знищити значення в сесії, оскільки ми його вже отримали.
    unset($_SESSION['kenny_pick_random_training_form_value']);

    if ($limit == 'girls') {
      $form_girls = \Drupal::formBuilder()->getForm('Drupal\kenny_random_training\Form\KennyRandomGirlsTraining');
      $output['girls_form'] = $form_girls;
    } elseif ($limit == 'mans') {

    }

    return $output;



  }

  /**z
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {

    return AccessResult::allowed();
  }
}
