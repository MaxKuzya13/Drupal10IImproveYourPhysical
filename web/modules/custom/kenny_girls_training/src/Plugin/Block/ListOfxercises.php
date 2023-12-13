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
 *   id = "kenny_list_of_exercises_block",
 *   admin_label = @Translation("Kenny Exercises Block"),
 * )
 */
class ListOfxercises extends BlockBase implements ContainerFactoryPluginInterface {

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
    $output['container'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['muscles']
      ],
    ];
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

      $muscle_name = $term->name;
      $muscle_name_lower = strtolower(str_replace(' ', '-', $term->name));

      $output['container'][$muscle_name_lower] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['muscles-container']
        ],
      ];
      $output['container'][$muscle_name_lower][$muscle_name_lower . '_title'] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' =>  $muscle_name,
        '#attributes' => [
          'class' => ["muscles-container__title"],
        ],
      ];


      $muscle_container_class = 'exercises-container-' . $muscle_name_lower;
      $output['container'][$muscle_name_lower]['exercises_container'] = [
        '#type' => 'container',
        '#attributes' => ['class' => [$muscle_container_class, 'muscles-container__exercises']],
      ];

      $i = 0;

      // Get all exercises for current muscle part.
      foreach ($exercises as $exercise) {
        $exercise_name = $exercise->get('name')->value;
        $exercise_video = $exercise->get('field_video_training')->entity->get('mid')->value;
        $video = $entity_type_manager->getStorage('media')->load($exercise_video);



        $exercise_container = strtolower(str_replace(' ', '-', $term->name)) . '_' . $i;
        $exercise_container_class = 'exercise-container-'. $exercise_container;
        $exercise_class = 'exercises-' . strtolower(str_replace(' ', '-', $term->name));

        $output['container'][$muscle_name_lower]['exercises_container'][$exercise_container] = [
          '#type' => 'container',
          '#attributes' => ['class' => [$exercise_container_class, 'container-exercise']],
        ];
        // Hidden exercises after 3;
        if ($i > 2) {
          $output['container'][$muscle_name_lower]['exercises_container'][$exercise_container] = [
            '#type' => 'container',
            '#attributes' => ['class' => [$exercise_container_class, 'hide-exercises', 'container-exercise']],
          ];
        }
        $class_button = 'show-video';
        $class_video = 'exercise-container-' . $exercise_container . '_video';

        // Video hidden by default.
        $output['container'][$muscle_name_lower]['exercises_container'][$exercise_container]['exercise'] = [
          'button' => [
            '#type' => 'button',
            '#value' => $exercise_name,
            '#attributes' => [
              'class' => [$class_button],
              'data-show-video' => $exercise_container_class,
            ],
          ],
          'video' => [
            '#type' => 'container',
            'video_element' => $entity_type_manager->getViewBuilder('media')->view($video, 'preview'),
            '#attributes' => [
              'class' => ['hide-exercises', $class_video, 'container-video'],
            ],
          ],

        ];
        $i++;
      };
      $output['container'][$muscle_name_lower]['exercises_container']['button'] = [
        '#type' => 'button',
        '#value' => $this->t('Show all exercises'),
        '#attributes' => [
          'class' => ['show-all-exercises-button'],
          'data-term-identifier' => "exercise-container-" . $muscle_name_lower,
        ],
      ];
      $output['container'][$muscle_name_lower]['exercises_container']['add_exercise'] = [
        '#theme' => 'links',
        '#links' => [
          'link' => [
            'title' => $this->t('Add a new exercise'),
            'url' => Url::fromRoute('kenny_girls_training.new_girl_exercise'),
            'attributes' => [
              'class' => ['use-ajax', 'create-a-new-exercise'],
              'data-dialog-type' => 'modal',
              'data-dialog-options' => json_encode(['height' => 600, 'width' => '50vw']),
            ],
          ],
        ]
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