<?php

namespace Drupal\kenny_girls_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Favorite Training Plan' block.
 *
 * @Block(
 *   id = "kenny_new_training_girls_block",
 *   admin_label = @Translation("Kenny New Training Girls Block"),
 * )
 */
class NewTrainingGirlsBlock extends BlockBase implements ContainerFactoryPluginInterface {
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

    $output['#attached']['library'] = 'core/drupal.dialog.ajax';
    $output = [
      '#theme' => 'links',
      '#links' => [
        'link' => [
          'title' => $this->t('Create a new training'),
          'url' => Url::fromRoute('kenny_girls_training.new_girl_training'),
          'attributes' => [
            'class' => ['use-ajax', 'create-a-new-training'],
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
