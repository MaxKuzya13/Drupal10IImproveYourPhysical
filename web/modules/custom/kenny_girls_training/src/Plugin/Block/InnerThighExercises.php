<?php

namespace Drupal\kenny_girls_training\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Inner Thigh Exercises' block.
 *
 * @Block(
 *   id = "kenny_inner_thigh_exercises_block",
 *   admin_label = @Translation("Kenny Inner Thigh Exercises Block"),
 * )
 */
class InnerThighExercises extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * Constructor by InnerThighExercises object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The form builder.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->termStorage = $entity_type_manager->getStorage('taxonomy_term');
  }

  /**
   * Container by NewTrainingGirlsBlock.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $entity_type_manager = $this->entityTypeManager;
    $term_storage = $this->termStorage;
    $body_plus_muscle_term_tree = $term_storage->loadTree('girls_training', '0', '2', '');
    $body_term_tree = $term_storage->loadTree('girls_training', '0', '1', '');
    $compareFunction = function ($a, $b) {
      return $a->tid - $b->tid;
    };
    $terms_muscle = array_udiff($body_plus_muscle_term_tree, $body_term_tree, $compareFunction);
    $output['#attached']['library'][] = 'kenny_girls_training/exercises-display';
    foreach ($terms_muscle as $term) {
      $query = $term_storage->getQuery()
        ->condition('vid', 'girls_training')
        ->condition('parent', $term->tid)
        ->accessCheck('FALSE')
        ->execute();
      if ($query) {
        $exercises_ids["exercises_{$term->name}"] = $query;
        $exercises = $term_storage->loadMultiple($exercises_ids["exercises_{$term->name}"]);
      }
      $output["exercises_{$term->name}"] = [
        '#markup' => "</br>" . "<h1>" . $term->name . "</h1>",
      ];


      $muscle_name = strtolower(str_replace(' ', '-', $term->name));
      $output[$muscle_name] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['exercises-container-' . $muscle_name]],
      ];

      $i = 0;

      foreach ($exercises as $exercise) {
        $exercise_name = $exercise->get('name')->value;
        $exercise_video = $exercise->get('field_video_training')->entity->get('mid')->value;
        $video = $entity_type_manager->getStorage('media')->load($exercise_video);
        $class = "exercises-' . strtolower(str_replace(' ', '-', $term->name))";





        $exercise_container = strtolower(str_replace(' ', '-', $term->name)) . '_' . $i;
        $output[$muscle_name][$exercise_container] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['exercise-container-'. $exercise_container]],
        ];


        $output[$muscle_name][$exercise_container]['exercise'] = [
          '#markup' => '</br>' . '<span>' . $exercise_name . '</span>',
          'video' => $entity_type_manager->getViewBuilder('media')
            ->view($video, 'preview'),
          '#attributes' => [
            'class' => ['exercises-' . strtolower(str_replace(' ', '-', $term->name))],
          ],
        ];
        $i++;
      };
      $output[$muscle_name]['button'] = [
        '#type' => 'button',
        '#value' => $this->t('Show all exercises'),
        '#attributes' => [
          'class' => ['show-all-exercises-button'],
          'data-term-identifier' => "exercise-container-" . $muscle_name,
        ],
      ];
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