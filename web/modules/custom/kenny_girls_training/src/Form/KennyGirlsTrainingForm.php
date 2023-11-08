<?php

namespace Drupal\kenny_girls_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
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

//  /**
//   * The number of field for exercises.
//   *
//   * @var int
//   */
//  protected int $numExercise = 10;

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
    $training_type_options = ['' => $this->t('- Select -')];
    foreach ($training_type as $term) {
      $training_type_options[$term->tid] = $term->name;
    }

    $body_part = $this->termStorage->loadTree('girls_body_part');
    // Get list of body part taxonomy
    $body_part_options = ['' => $this->t('- Select -')];
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
      '#required' => TRUE,
    ];

    $form['muscle_groups'] = [
      '#type' => 'select',
      '#title' => 'Choose Muscle group',
      '#options' => $body_part_options,
      '#prefix' => '<div id="muscle-groups-wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => '::chooseExerciseAjax',
        'event' => 'change',
      ]
    ];

    // Get count of fields
//    $num_exercises = $this->numExercise;
//    $num_exercises_options = [];
    $num_exercises = 7;
//    for ($i = 0; $i < 11; $i++) {
//      $num_exercises_options[] = $i;
//    }

//    $form['num_exercises'] = [
//      '#type' => 'select',
//      '#title' => 'Choose Count of Field',
//      '#options' => $num_exercises_options,
//      '#default_value' => $num_exercises,
//      '#ajax' => [
//        'callback' => '::changeNumExercisesCallback',
//        'wrapper' => 'exercise-selection',
//        'event' => 'change',
//      ],
//    ];
    $form['num_exercises'] = [
      '#type' => 'hidden',
      '#value' => $num_exercises,
    ];

    // Container for exercise selection.
    $form['exercise_selection'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'exercise-selection'],
    ];

    for ($i = 0; $i < $num_exercises; $i++) {
      $form['exercise_selection']['exercises_' . $i] = $this->createExerciseSelectField($form, $form_state, $i);
      $form['exercise_selection']['weight_' . $i] = $this->createExerciseField($form_state, 'weight_' . $i, 'Weight', ' kg');
      $form['exercise_selection']['repetition_' . $i] = $this->createExerciseField($form_state,'repetition_' . $i, 'Repetition');
      $form['exercise_selection']['approaches_' . $i] = $this->createExerciseField($form_state,'approaches_' . $i, 'Approaches');
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;

  }

//  /**
//   * Get count of field to exercises.
//   *
//   * @param array $form
//   *    An associative array containing the structure of the form.
//   * @param \Drupal\Core\Form\FormStateInterface $form_state
//   *    The current state of the form.
//   * @return AjaxResponse
//   */
//  public function changeNumExercisesCallback(array &$form, FormStateInterface $form_state) {
//    $response = new AjaxResponse();
//    $num_exercises = $form_state->getValue('num_exercises');
//
//    // Очищаємо попередні поля для вправ і додаємо нові поля.
//    $form['exercise_selection'] = [
//      '#type' => 'container',
//      '#attributes' => ['id' => 'exercise-selection'],
//    ];
//
//    for ($i = 0; $i < $num_exercises; $i++) {
//      $form['exercise_selection']['exercises_' . $i] = $this->createExerciseSelectField($form, $form_state, $i);
//      $form['exercise_selection']['weight_' . $i] = $this->createExerciseField($form_state, 'weight_' . $i, 'Weight', ' kg');
//      $form['exercise_selection']['repetition_' . $i] = $this->createExerciseField($form_state,'repetition_' . $i, 'Repetition');
//      $form['exercise_selection']['approaches_' . $i] = $this->createExerciseField($form_state,'approaches_' . $i, 'Approaches');
//    }
//
//    // Оновлюємо контейнер з полями відповіді AJAX.
//    $response->addCommand(new HtmlCommand('#exercise-selection', $form['exercise_selection']));
//
//    return $response;
//  }
//
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

      $choose_body_part_id = $form_state->getValue('muscle_groups');

      /** @var \Drupal\taxonomy\TermStorageInterface $choose_body_part_name */
      $choose_body_part_name = $this->termStorage->load($choose_body_part_id)->getName();

      if ($choose_body_part_name == 'Full Body') {
        $exercises_id = $this->getAllExercises();
      } else {
        $body_part = $this->getAllBodyPart();
        $body_part_id = $this->getMatchBodyPart($body_part, $choose_body_part_name);
        $exercises_id = $this->getExercisesList($body_part_id);
      }

      /** @var \Drupal\taxonomy\TermStorageInterface $exercises_terms */
      $exercises_terms = $this->termStorage->loadMultiple($exercises_id);

      $exercises_options = ['' => $this->t('- Select -')];

      foreach ($exercises_terms as $exercises_term) {
        $exercises_options[$exercises_term->id()] = $exercises_term->getName();
      }

      $exerciseField = [
        'exercise_' . $index => [
          '#type' => 'select',
          '#title' => $this->t('Choose Exercise'),
          '#options' => $exercises_options,
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
   * List of exercises by body part.
   *
   * @param array $form
   *     An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *     The current state of the form.
   * @return AjaxResponse
   */
  public function chooseExerciseAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $choose_body_part_id = $form_state->getValue('muscle_groups');
    /** @var \Drupal\taxonomy\TermStorageInterface $choose_body_part_name */

    /** @var \Drupal\taxonomy\TermStorageInterface $choose_body_part_name */
    $choose_body_part_name = $this->termStorage->load($choose_body_part_id)->getName();

    // id`s body part in Training Girls Taxonomy.
    $body_part = $this->getAllBodyPart();

    // Id of body part that matches.
    $body_part_id = $this->getMatchBodyPart($body_part, $choose_body_part_name);

    // Id`s exercises for current body part.
    $exercises_id = $this->getExercisesList($body_part_id);

    /** @var \Drupal\taxonomy\TermStorageInterface $exercises_terms */
    $exercises_terms = $this->termStorage->loadMultiple($exercises_id);

    $exercises_options = ['' => $this->t('- Select -')];

    foreach ($exercises_terms as $exercises_term) {
      $exercises_options[$exercises_term->id()] = $exercises_term->getName();
    }

    // Value of exercise
    $num_exercises = $form_state->getValue('num_exercises');
    for ($i = 0; $i < $num_exercises; $i++) {
      $form['exercise_selection']['exercises_' . $i]['exercise']['#options'] = $exercises_options;
      $response->addCommand(new HtmlCommand('#exercise-selection', $form['exercise_selection']));
    }


    return $response;
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
   * @param string $choose_body_part_name
   *   The name of the chosen body part.
   * @return int|string|null
   */
  protected function getMatchBodyPart($body_part, $choose_body_part_name) {
    foreach ($body_part as $body_id) {
      /** @var \Drupal\taxonomy\TermStorageInterface $body_part_name */
      $body_part_name = $this->termStorage->load($body_id)->getName();
      if ($body_part_name == $choose_body_part_name) {
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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Отримайте кількість вправ, які потрібно перевірити.
    $num_exercises = $form_state->getValue('num_exercises');

    for ($i = 0; $i < $num_exercises; $i++) {
      // Отримайте значення полів для відповідної вправи.
      $exercise_value = $form_state->getValue('exercise_' . $i);
      $exercise_name = !empty($exercise_value) ? $this->termStorage->load($exercise_value)->getName() : '';
      // Перевірте значення полів і за потреби виведіть повідомлення про помилки.

      if (!empty($exercise_value)) {
        $this->validateNumericField($form_state, 'weight_' . $i, 'Weight', $exercise_name);
        $this->validateNumericField($form_state, 'repetition_' . $i, 'Repetition', $exercise_name);
        $this->validateNumericField($form_state, 'approaches_' . $i, 'Approaches', $exercise_name);
      } else {
        $this->validateNonEmptyField($form_state, 'weight_' . $i, 'Weight');
        $this->validateNonEmptyField($form_state, 'repetition_' . $i, 'Repetition');
        $this->validateNonEmptyField($form_state, 'approaches_' . $i, 'Approaches');
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
  public function validateNumericField(FormStateInterface $form_state, $field_name, $field_label, $exercise_name = '') {
    $value = $form_state->getValue($field_name);
    if (!is_numeric($value) || intval($value) <= 0) {
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
  public function validateNonEmptyField(FormStateInterface $form_state, $field_name, $field_label) {
    $value = $form_state->getValue($field_name);
    if (!empty($value)) {
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

    $num_exercises = $form_state->getValue('num_exercises');

    for ($i = 0; $i < $num_exercises; $i++ ) {
      if (!empty($form_state->getValue('exercise_' . $i))) {
        $exercise[$i] = $form_state->getValue('exercise_' . $i);
        $weight[$i] = $form_state->getValue('weight_' . $i);
        $repetition[$i] = $form_state->getValue('repetition_' . $i);
        $approaches[$i] = $form_state->getValue('approaches_' . $i);
//        dump($exercise, $weight, $repetition, $approaches);

      }
    }

    $girls_training = $this->nodeStorage->create([
      'type' => 'girls_training',
      'title' => $title,
      'field_girls_body_part' => $body_part,
      'field_girls_type_of_training' => $training_type,
      'field_girls_training_date' => $date,
    ]);

    for ($i = 0; $i < $num_exercises; $i++) {
      if (!empty($exercise[$i])) {

        $paragraph = $this->paragraphStorage->create([
          'type' => 'girl_training',
          'field_girl_exercise' => $exercise[$i],
          'field_weight' => $weight[$i],
          'field_repetition' => $repetition[$i],
          'field_approaches' => $approaches[$i],
        ]);

        // Зберігаємо параграф.
        $paragraph->save();

        // Додаємо параграф до поля "field_exercises" вузла "Training Plan."
        $girls_training->field_girls_exercises[] = $paragraph;
      }
    }

    $girls_training->save();
    // Виводимо текст допоміжний
    $this->messenger->addMessage(
      t('The training @title for @body_part successfully add', [
        '@title' => $title,
        '@body_part' => $body_part_name
      ])
    );
  }
}