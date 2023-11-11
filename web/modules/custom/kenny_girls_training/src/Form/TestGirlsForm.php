<?php

namespace Drupal\kenny_girls_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestGirlsForm extends FormBase {

  /**
   * The entity type manager
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The taxonomy term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   *
   */
  protected $termStorage;

  /**
   * The paragraph storage.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $paragraphStorage;

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   *
   */
  protected $nodeStorage;


  /**
   * KennyTrainingPlanForm constructor
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, MessengerInterface $messenger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
    $this->termStorage = $entity_type_manager->getStorage('taxonomy_term');
    $this->paragraphStorage = $entity_type_manager->getStorage('paragraph');
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * Creates an instance of the form
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('messenger'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kenny_test_girls_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $training_type_options = $this->getTaxonomyOptions('girls_type_of_training');
    $body_part_options = $this->getTaxonomyOptions('girls_body_part');

    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date'),
      '#date_date_format' => 'd.m.Y',
      '#date_year_range' => '0:+10',
      '#date_increment' => 15,
      '#default_value' => date('Y-m-d'), // Формат Y-m-d.
      // Зворотній переклад для відображення дати в "dd.mm.yyyy".
      '#value_callback' => 'date_element_value_callback',
      '#required' => TRUE,
    ];

    $form['training_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type of training'),
      '#options' => $training_type_options,
      '#required' => TRUE,
    ];

    $form['body_part'] = [
      '#type' => 'select',
      '#title' => $this->t('Body Part'),
      '#options' => $body_part_options,
      '#required' => TRUE,
      '#ajax' => [
      'callback' => '::updateTestExercisesOptions',
      'wrapper' => 'test-exercises-wrapper', // Wrapper для оновлення опцій test_exercises.
      'effect' => 'fade',
      'event' => 'change',
      'progress' => [
        'type' => 'throbber',
        'message' => t('Updating exercises...'),
      ],
    ],
    ];

    $form['test_exercises_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'test-exercises-wrapper'],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;

  }


  ////////////////////////////////////////////////

  public function getTaxonomyOptions($taxonomy_name, $parent = 0, $depth = null, $entities = TRUE) {
    $options = ['' => $this->t('- Select -')];
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree($taxonomy_name, $parent, $depth, $entities);

    foreach ($terms as $term) {
      $options[$term->id()] = $term->getName();;
    }

    return $options;
  }


  public function getTopLevelTrainingOptions() {
    $options = ['' => $this->t('- Select -')];

    // Отримати терміни найвищого рівня для таксономії "Training".
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree('girls_training');

    $top_level_trainings = [];
    foreach ($terms as $term) {
      $tid = $term->tid;
      $parents = $this->termStorage->loadParents($tid);


      if (empty($parents)) {
        $options[$tid] = $term->name;
      }
    }

    $options += [$this->t('Full Body')];

    return $options;
  }

  public function getChildren($tid) {
    $childrens = [];
    if (is_array($tid)) {
      foreach ($tid as $id) {
        $parent_term = $this->termStorage->load($id);
        if ($parent_term) {
          // Завантажуємо дітей для батьківського терміна.
          $childrens += $this->termStorage->loadChildren($id);
        }
      }
    } else {
      $parent_term = $this->termStorage->load($tid);
      if ($parent_term) {
        // Завантажуємо дітей для батьківського терміна.
        $childrens += $this->termStorage->loadChildren($tid);

      }
    }

    $options = [];
    foreach ($childrens as $children) {
      $options[$children->id()] = $children->getName();
    }

    return $options;
  }


  ////////////////////////////////////////////////
  // Оновлення опцій для test_exercises AJAX-запитом.
  public function updateTestExercisesOptions(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $selected_parent = $form_state->getValue('body_part');


    if (!empty($selected_parent)) {
      // Отримати опції для test_exercises на основі вибору з test_parent.
      if ($selected_parent == 170) {
        $test_exercises= $this->getAllExercises();
        $test_exercises_terms = $this->termStorage->loadMultiple($test_exercises);
        $test_exercises_options = ['' => $this->t('- Select -')];

        foreach ($test_exercises_terms as $exercises_term) {
          $test_exercises_options[$exercises_term->id()] = $exercises_term->getName();
        }
      } else {
        $test_exercises_options = ['' => $this->t('- Select -')];
        $test_exercises_options += $this->getTestExercisesOptions($selected_parent);
      }


      // Оновити опції в test_exercises_container.
      $form['test_exercises_container']['test_exercises'] = [
        '#type' => 'select',
        '#title' => $this->t('Test exercises'),
        '#options' => $test_exercises_options,
        '#required' => TRUE,
      ];

      $form['test_exercises_container']['test_weight'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Weight'),
        '#required' => TRUE,
      ];

      $form['test_exercises_container']['test_repetition'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Repetition'),
        '#required' => TRUE,
      ];

      $form['test_exercises_container']['test_approaches'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Approaches'),
        '#required' => TRUE,
      ];

      $form_state->setValue('test_exercises', '');
      $form_state->setValue('test_weight', '');
      $form_state->setValue('test_repetition', '');
      $form_state->setValue('test_approaches', '');

      $response->addCommand(new HtmlCommand('#test-exercises-wrapper', $form['test_exercises_container']));
    }

    return $response;

  }

  public function getTestExercisesOptions($selected_parent) {

    $selected_body_part = $this->termStorage->load($selected_parent);
    $selected_body_part_name = $selected_body_part->getName();

    $taxonomy = $this->termStorage->loadTree('girls_training');
    foreach ($taxonomy as $term) {
      if ($term->name == $selected_body_part_name) {
        $body_part_id = $term->tid;
        break;
      }
    }

    $muscle = $this->getChildren($body_part_id);
    $muscle_keys = array_keys($muscle);

    $exercises_options = $this->getChildren($muscle_keys);

    return $exercises_options;
  }

  /**
   * All exercises.
   *
   * @return array
   */
  protected function getAllExercises() {
    /** @var \Drupal\taxonomy\TermStorageInterface $taxonomy */
    $taxonomy = $this->termStorage->loadTree('girls_training');

    $all_exercise = [];

    foreach ($taxonomy as $tax) {
      if($tax->depth == 2) {
        $all_exercise[] = $tax->tid;
      }
    }
    return $all_exercise;
  }





  ////////////////////////////////////////////////

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   $all_value = $form_state->getValues();
   dump($all_value);
  }
}