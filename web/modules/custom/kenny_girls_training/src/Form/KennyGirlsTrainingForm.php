<?php

namespace Drupal\kenny_girls_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\kenny_training\Event\HelloWorldControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KennyGirlsTrainingForm extends FormBase {

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
    return 'kenny_girls_training_plan_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $training_type = $this->termStorage->loadTree('girls_type_of_training');
    $training_type_options = [];
    foreach ($training_type as $term) {
      $training_type_options[$term->tid] = $term->name;
    }

    $body_part = $this->termStorage->loadTree('girls_body_part');
    // Get list of body part taxonomy
    $body_part_options = [];
    foreach ($body_part as $term) {
      $body_part_options[$term->tid] = $term->name;
    }

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
      '#empty_option' => $this->t('- Select a training type -'),
      '#required' => TRUE,
    ];

    $form['muscle_groups'] = [
      '#type' => 'select',
      '#title' => 'Select Muscle group',
      '#options' => $body_part_options,
      '#empty_option' => $this->t('- Select a muscle groups -'),
      '#required' => TRUE,
      '#prefix' => '<div id="muscle-groups-wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => '::selectExerciseAjax',
        'event' => 'change',
        'wrapper' => 'exercise-selection',
      ]
    ];

    // Gather the number of names in the form already.
    $num_exercises = $form_state->get('num_exercises');
    // We have to ensure that there is at least one name field.
    if ($num_exercises === NULL) {
      $name_field = $form_state->set('num_exercises', 1);
      $num_exercises = 1;
    }

    $form['#tree'] = TRUE;


    // Container for exercise selection.
    $form['exercise_selection'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'exercise-selection'],
    ];

    $muscle_groups = $form_state->getValue('muscle_groups');

    if (!empty($muscle_groups)) {
      for ($i = 0; $i < $num_exercises; $i++) {
        $exercise_container_id = 'exercise-container-' . $i;

        // Додайте контейнер для кожної вправи.
        $form['exercise_selection'][$exercise_container_id] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['exercise-container']],
        ];

        $form['exercise_selection'][$exercise_container_id]['exercises'] = $this->createExerciseSelectField($form, $form_state, $i);
        $form['exercise_selection'][$exercise_container_id]['weight'] = $this->createExerciseField($form_state, 'weight', 'Weight');
        $form['exercise_selection'][$exercise_container_id]['repetition'] = $this->createExerciseField($form_state,'repetition', 'Repetition');
        $form['exercise_selection'][$exercise_container_id]['approaches'] = $this->createExerciseField($form_state,'approaches', 'Approaches');
      }
    }

    $form['actions'] = ['#type' => 'actions'];

    if ($num_exercises > 1) {
      $form['exercise_selection']['actions']['remove_field'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove one field'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addMoreCallback',
          'wrapper' => 'exercise-selection',
        ],
        '#attributes' => [
          'class' => ['remove-field', 'new-training-form-button'],
        ]
      ];
    };

    $form['exercise_selection']['actions']['add_field'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more field'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'wrapper' => 'exercise-selection',
      ],
      '#attributes' => [
        'class' => ['add-field', 'new-training-form-button'],
      ]
    ];



    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#submit' => ['::submitForm'],
      '#validate' => ['::ajaxValidateSave'],
      '#attributes' => [
        'class' => ['save-new-training-form', 'new-training-form-button'],
      ]
    ];

    return $form;

  }

  /**
   * List of exercises by body part.
   *
   * @param array $form
   *     An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *     The current state of the form.
   * @return array
   */
  public function selectExerciseAjax(array &$form, FormStateInterface $form_state) {
    return $form['exercise_selection'];
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addMoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['exercise_selection'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_exercises');
    if ($name_field < 10) {
      $add_button = $name_field + 1;
      $form_state->set('num_exercises', $add_button);
    }
    // Since our buildForm() method relies on the value of 'num_names' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_exercises');
    if ($name_field > 1) {
      $remove_button = $name_field - 1;
      $form_state->set('num_exercises', $remove_button);
    }
    // Since our buildForm() method relies on the value of 'num_names' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state->setRebuild();
  }

  /**
   * Create selected options for exercises.
   *
   * @param array $form
   *    An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *    The current state of the form.
   * @param $index
   *   The index by
   * @return array
   */
  public function createExerciseSelectField(&$form, $form_state, $index) {

    if (!empty($form_state->getValue('muscle_groups'))) {

      $selected_body_part_id = $form_state->getValue('muscle_groups');

      /** @var \Drupal\taxonomy\TermStorageInterface $selected_body_part_name */
      $selected_body_part_name = $this->termStorage->load($selected_body_part_id)->getName();

      if ($selected_body_part_name == 'Full Body') {
        $exercises_id = $this->getAllExercises();
      } else {
        $body_part = $this->getAllBodyPart();
        $body_part_id = $this->getMatchBodyPart($body_part, $selected_body_part_name);
        $exercises_id = $this->getExercisesList($body_part_id);
      }

      /** @var \Drupal\taxonomy\TermStorageInterface $exercises_terms */
      $exercises_terms = $this->termStorage->loadMultiple($exercises_id);

      $exercises_options = [];

      foreach ($exercises_terms as $exercises_term) {
        $exercises_options[$exercises_term->id()] = $exercises_term->getName();
      }

      $exerciseField = [
        'exercise' => [
          '#type' => 'select',
          '#options' => $exercises_options,
          '#empty_option' => $this->t('- Select an exercise -'),
          '#prefix' => '<div class="exercise-item" id="exercises_' . $index . '">',
          '#suffix' => '</div>',
        ],
      ];

      return $exerciseField;
    }
    return [];

  }

  /**
   * Create a field for exercise (weight, repetition, approaches).
   *
   * @param string $field_name
   *   The field name (weight, repetition, approaches).
   * @param string $title
   *   The title for the field.
   * @param string $unit
   *   The unit for the field (e.g., "kg").
   * @return array
   *   Form element.
   */
  public function createExerciseField(FormStateInterface $form_state, $field_name, $title, $unit = '') {

    if (!empty($form_state->getValue('muscle_groups'))) {
      $formField = [
        $field_name => [
          '#type' => 'textfield',
          '#title' => $this->t($title),
        ],
      ];
      if (!empty($unit)) {
        $formField[$field_name]['#prefix'] = $unit;
      }
      return $formField;
    }

    return [];
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

  /**
   * All body part
   *
   * @return array
   */
  protected function getAllBodyPart() {
    /** @var \Drupal\taxonomy\TermStorageInterface $taxonomy */
    $taxonomy = $this->termStorage->loadTree('girls_training');
    $top_level_term_ids = [];

    foreach ($taxonomy as $term) {
      if ($term->parents[0] == 0) {
        $top_level_term_ids[] = $term->tid;
      }
    }

    return $top_level_term_ids;
  }


  /**
   * Get the match body part id.
   *
   * @param array $body_part
   *   List of id`s body part
   * @param string $selectedbody_part_name
   *   The name of the chosen body part.
   * @return int|string|null
   */
  protected function getMatchBodyPart($body_part, $selected_body_part_name) {
    foreach ($body_part as $body_id) {
      /** @var \Drupal\taxonomy\TermStorageInterface $body_part_name */
      $body_part_name = $this->termStorage->load($body_id)->getName();
      if ($body_part_name == $selected_body_part_name) {
        $id = $this->termStorage->load($body_id)->id();
        return $id;
      }
    }

    return null;
  }

  /**
   * List of exercises by chosen body part.
   *
   * @param int $body_part_id
   *   The id of chosen body part.
   * @return array
   */
  protected function getExercisesList($body_part_id) {
    /** @var \Drupal\taxonomy\TermStorageInterface $girls_training */
    $girls_training = $this->termStorage->loadTree('girls_training');
    $exercises = [];
    $muscle_part = '';
    $muscle_parts = [];

    foreach ($girls_training as $girl_term) {

      // Get a list of muscles that should be excluded from
      // the list of exercises
      if ($girl_term->parents[0] == $body_part_id) {
        $muscle_part = $girl_term->tid;
        $muscle_parts[] = $muscle_part;
      }

      if (!empty($muscle_part)) {
        if ($girl_term->parents[0] == $muscle_part) {
          $exercises[] = $girl_term->tid;
        }
      }
    }

    if (empty($exercises)) {
      $exercises = $muscle_parts;
    }

    return $exercises;
  }


  /**
   * {@inheritdoc}
   */
  public function ajaxValidateSave(array &$form, FormStateInterface $form_state) {
    $exercise_selection = $form_state->getValue('exercise_selection');
    unset($exercise_selection['actions']);

    for ($i = 0; $i < count($exercise_selection); $i++) {
      $exercise_container = $exercise_selection['exercise-container-' . $i];
      $exercise = $exercise_container['exercises']['exercise'];
      $exercise_name = !empty($exercise) ? $this->termStorage->load($exercise)->getName() : '';
      $weight = $exercise_container['weight']['weight'];
      $repetition = $exercise_container['repetition']['repetition'];
      $approaches = $exercise_container['approaches']['approaches'];

      if (!empty($exercise)) {
        $this->validateNumericField($form_state, $weight,'Weight', $exercise_name);
        $this->validateNumericField($form_state, $repetition, 'Repetition', $exercise_name);
        $this->validateNumericField($form_state, $approaches, 'Approaches', $exercise_name);
      } else {
        $this->validateNonEmptyField($form_state, $weight, 'Weight');
        $this->validateNonEmptyField($form_state, $repetition, 'Repetition');
        $this->validateNonEmptyField($form_state, $approaches, 'Approaches');
      }
    }

  }

  /**
   * Перевірка числового поля.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Об'єкт стану форми.
   * @param string $field_name
   *   Назва поля.
   * @param string $field_label
   *   Назва поля для відображення.
   * @param string $exercise_name
   *   Назва вправи.
   */
  public function validateNumericField(FormStateInterface $form_state, $value, $field_label, $exercise_name = '') {
    if (!is_numeric($value) || intval($value) <= 0) {
      $field_name = strtolower($field_label);
      $form_state->setErrorByName($field_name, $this->t('%field_label for @exercise must be a positive integer.', [
        '%field_label' => $field_label,
        '@exercise' => $exercise_name,
      ]));
    }
  }

  /**
   * Перевірка непорожнього поля.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Об'єкт стану форми.
   * @param string $field_name
   *   Назва поля.
   * @param string $field_label
   *   Назва поля для відображення.
   */
  public function validateNonEmptyField(FormStateInterface $form_state, $value, $field_label) {
    if (!empty($value)) {
      $field_name = strtolower($field_label);
      $form_state->setErrorByName($field_name, $this->t('Entered %field_label @value for empty field exercise.', [
        '%field_label' => $field_label,
        '@value' => $value
      ]));
    }
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $training_type = $form_state->getValue('training_type');
    $training_type_name = !empty($training_type) ? $this->termStorage
      ->load($training_type)->getName() : '';

    $body_part = $form_state->getValue('muscle_groups');
    $body_part_name = !empty($body_part) ? $this->termStorage
      ->load($body_part)->getName() : '';

    $date = $form_state->getValue('date');
    $drupal_date = strtotime($date);
    $formatted_date = date('d F Y', $drupal_date);

    $title = $formatted_date . ' | ' . $body_part_name . ' | ' . $training_type_name;

    $girls_training = $this->nodeStorage->create([
      'type' => 'girls_training',
      'title' => $title,
      'field_girls_body_part' => $body_part,
      'field_girls_type_of_training' => $training_type,
      'field_girls_training_date' => $date,
    ]);

    $exercise_selection = $form_state->getValue('exercise_selection');
    unset($exercise_selection['actions']);

    foreach ($exercise_selection as $exercise_container) {
      $exercise = $exercise_container['exercises']['exercise'];
      $weight = $exercise_container['weight']['weight'];
      $repetition = $exercise_container['repetition']['repetition'];
      $approaches = $exercise_container['approaches']['approaches'];

      $paragraph = $this->paragraphStorage->create([
        'type' => 'girl_training',
        'field_girl_exercise' => $exercise,
        'field_weight' => $weight,
        'field_repetition' => $repetition,
        'field_approaches' => $approaches,
      ]);

      // Зберігаємо параграф.
      $paragraph->save();

      // Додаємо параграф до поля "field_exercises" вузла "Training Plan."
      $girls_training->field_girls_exercises[] = $paragraph;
      $girls_training->save();
    }

    // Виводимо текст допоміжний
    $this->messenger->addMessage(
      t('The training @title for @body_part successfully add', [
        '@title' => $title,
        '@body_part' => $body_part_name
      ])
    );

    $form_state->setRedirectUrl(Url::fromUri('internal:/training-girls'));

  }
}