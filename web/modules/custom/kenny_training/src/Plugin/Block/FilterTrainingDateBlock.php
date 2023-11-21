<?php

namespace Drupal\kenny_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\kenny_training\Service\FilterDate\FilterDateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Favorite Training Plan' block.
 *
 * @Block(
 *   id = "kenny_filter_training_date_block",
 *   admin_label = @Translation("Kenny Filter Training Date Block"),
 * )
 */
class FilterTrainingDateBlock extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * The entity type managed.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   *
   */
  protected $entityTypeManager;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The filter by date.
   *
   * @var \Drupal\kenny_training\Service\FilterDate\FilterDateInterface
   */
  protected $filterDate;


  /**
   * Constructor by FilterTrainingDateBlock.
   *
   * @param array $configuration
   *    A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *    The plugin_id for the plugin.
   * @param mixed $plugin_definition
   *    The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   * @param \Drupal\kenny_training\Service\FilterDate\FilterDateInterface $filter_date
   *   The filter date.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, FormBuilderInterface $form_builder, FilterDateInterface $filter_date) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->formBuilder = $form_builder;
    $this->filterDate = $filter_date;
  }

  /**
   * Container by FilterTrainingDateBlock.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('form_builder'),
      $container->get('kenny_training.filter_date'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'period' => 'default',
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = [];
    $config = $this->getConfiguration();
    $selected_period = $config['period'];

    $nodes = $this->filterDate->filterTrainingByDate($selected_period);


    if(!empty($nodes)) {

      $teasers = [];

      foreach ($nodes as $node) {
        $teasers[] = $this->entityTypeManager
          ->getViewBuilder('node')
          ->view($node, 'teaser');
      }

      $output['content'] = $teasers;
      return $output;
    }

    return [];

  }


/**z
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {

    return AccessResult::allowed();
  }
}
