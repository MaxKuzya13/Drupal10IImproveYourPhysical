<?php

namespace Drupal\kenny_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\kenny_training\Service\TrainingMethods\TrainingMethodsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class KennyTrainingPlanForm extends FormBase {

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
    return 'kenny_training_plan_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $training_type = $this->termStorage->loadTree('type_of_training');
    $training_type_options = [];
    foreach ($training_type as $term) {
      $training_type_options[$term->tid] = $term->name;
    }

    $body_part = $this->termStorage->loadTree('body_part');
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

    $form['num_groups'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of Muscle Groups'),
      '#options' => [1 => '1', 'full_body' => 'Full Body'],
      '#empty_optios' => $this->t('- Select groups of body -'),
      '#required' => TRUE,
    ];

    $form['muscle_groups'] = [
      '#type' => 'select',
      '#title' => 'Choose Muscle group',
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

        $form['exercise_selection'][$exercise_container_id]['exercises'] = $this
          ->createExerciseSelectField($form, $form_state, $i);
        $form['exercise_selection'][$exercise_container_id]['weight'] = $this
          ->createExerciseField($form_state, 'weight', 'Weight');
        $form['exercise_selection'][$exercise_container_id]['repetition'] = $this
          ->createExerciseField($form_state,'repetition', 'Repetition');
        $form['exercise_selection'][$exercise_container_id]['approaches'] = $this
          ->createExerciseField($form_state,'approaches', 'Approaches');
      }
    }

    $form['exercise_selection']['actions'] = ['#type' => 'actions'];

    if ($num_exercises > 1) {
      $form['exercise_selection']['actions']['remove_field'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove one field'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addMoreCallback',
          'wrapper' => 'exercise-selection',
        ],
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
    ];



    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#submit' => ['::submitForm'],
      '#validate' => ['::ajaxValidateSave'],
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
   * Create field for exercise
   *
   * @param integer $index
   *   The exercise index
   * @return array
   *   Form element
   */
  public function createExerciseSelectField(&$form, $form_state, $index) {

    if (!empty($form_state->getValue('muscle_groups'))) {
      $body_part_id = $form_state->getValue('muscle_groups');

      /** @var \Drupal\taxonomy\TermStorageInterface $term */
      $term = $this->termStorage->load($body_part_id);
      $vocabulary_name = strtolower($term->getName());

      $exercises_terms = $this->termStorage->loadTree($vocabulary_name);

      $exercises_options = [];
      foreach ($exercises_terms as $exercises_term) {
        $exercises_options[$exercises_term->tid] = $exercises_term->name;
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
  public function validateNumericField(FormStateInterface $form_state, $value,  $field_label, $exercise_name = '') {
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

    $training_plan = $this->nodeStorage->create([
      'type' => 'training_plan',
      'title' => $title,
      'field_body_part' => $body_part,
      'field_type_of_training' => $training_type,
      'field_training_date' => $date,
    ]);

    $exercise_selection = $form_state->getValue('exercise_selection');
    unset($exercise_selection['actions']);

    foreach ($exercise_selection as $exercise_container) {
      $exercise = $exercise_container['exercises']['exercise'];
      $weight = $exercise_container['weight']['weight'];
      $repetition = $exercise_container['repetition']['repetition'];
      $approaches = $exercise_container['approaches']['approaches'];

      $paragraph_type = strtolower($body_part_name);

      $paragraph = $this->paragraphStorage->create([
        'type' => $paragraph_type,
        'field_exercise' => $exercise,
        'field_weight' => $weight,
        'field_repetition' => $repetition,
        'field_approaches' => $approaches,
      ]);

      // Зберігаємо параграф.
      $paragraph->save();

      // Додаємо параграф до поля "field_exercises" вузла "Training Plan."
      $training_plan->field_exercises[] = $paragraph;
      $training_plan->save();
    }

    // Виводимо текст допоміжний
    $this->messenger->addMessage(
      t('The training plan @title for body part @body_part successfully add', [
        '@title' => $title,
        '@body_part' => $body_part_name
      ])
    );

    $form_state->setRedirectUrl(Url::fromUri('internal:/training'));

  }
}
