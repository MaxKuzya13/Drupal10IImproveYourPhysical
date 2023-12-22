<?php

namespace Drupal\kenny_girls_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\RedirectCommand;
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

    $form['#attached']['library'][] = 'kenny_girls_training/kenny_girls_script';



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
        $form['exercise_selection'][$exercise_container_id]['weight'] = $this->createExerciseField($form_state, 'weight', 'Weight', $i);
        $form['exercise_selection'][$exercise_container_id]['repetition'] = $this->createExerciseField($form_state,'repetition', 'Repetition', $i);
        $form['exercise_selection'][$exercise_container_id]['approaches'] = $this->createExerciseField($form_state,'approaches', 'Approaches', $i);
      }
    }


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


    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('Save'),
      '#ajax' => [
        'callback' => '::submitData'
      ],
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
   *   The exercise index.
   * @return array
   *   Form element.
   */
  public function createExerciseSelectField(&$form, FormStateInterface $form_state, $index) {

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
          '#suffix' => '<div class="exercise-item error" id="exercises_' . $index . '"></div>',
          '#attributes' => [
            'class' => ['edit-exercises-' . $index],
          ]
        ],
      ];



      return $exerciseField;
    }
    return [];

  }

  /**
   * Create a field for exercise (weight, repetition, approaches).
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   * @param string $field_name
   *   The field name (weight, repetition, approaches).
   * @param string $title
   *   The title for the field.
   * @param int $index
   *   The current index.
   * @return array
   *   Form element.
   */
  public function createExerciseField(FormStateInterface $form_state, $field_name, $title, $index) {

    if (!empty($form_state->getValue('muscle_groups'))) {
      $formField = [
        $field_name => [
          '#type' => 'textfield',
          '#title' => $this->t($title),
          '#suffix' => '<div class="error" id="' . $field_name . '_' . $index . '"></div>',
          '#attributes' => [
            'class' => ['edit-' . $field_name . '-' . $index],
          ]
        ],
      ];

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
   * Check if the field value is numeric.
   *
   * @param \Drupal\Core\Ajax\AjaxResponse $ajax_response
   *   The ajax response object.
   * @param int $index
   *    The current index.
   * @param string|int|null $value,
   *   The value of field.
   * @param string $field_label
   *   Назва поля для відображення.
   * @return bool
   *
   */
  public function validateNumericField($ajax_response, $index, $value, $field_label) {
    if (!is_numeric($value) || intval($value) <= 0) {
      $field_name = strtolower($field_label);
      $ajax_response->addCommand(new HtmlCommand('#' . $field_name . '_' . $index, $this->t('%field_label must be a positive number.', [
        '%field_label' => $field_label
      ])));
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if the field is none empty.
   *
   * @param \Drupal\Core\Ajax\AjaxResponse $ajax_response
   *  The ajax response object.
   * @param int $index
   *    The current index.
   * @param string|int|null $value,
   *   The value of field.
   * @param string $field_label
   *   Назва поля для відображення.
   * @return bool
   *
   */
  public function validateNonEmptyField($ajax_response, $index, $value, $field_label) {
    if (!empty($value)) {
      $field_name = strtolower($field_label);
      $ajax_response->addCommand(new HtmlCommand('#' . $field_name . '_' . $index, $this->t('Entered %field_label for empty field exercise.', [
        '%field_label' => $field_label,
      ])));
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * The ajax submit.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state
   * @return \Drupal\Core\Ajax\AjaxResponse
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   */
  function submitData(array &$form, FormStateInterface $form_state) {

    $ajax_response = new AjaxResponse();

    $exercise_selection = $form_state->getValue('exercise_selection');
    unset($exercise_selection['actions']);

    $flag = [];


    for ($i = 0; $i < count($exercise_selection); $i++) {
      $exercise_container = $exercise_selection['exercise-container-' . $i];
      $exercise = $exercise_container['exercises']['exercise'];
      $exercise_name = !empty($exercise) ? $this->termStorage->load($exercise)->getName() : '';
      $weight = $exercise_container['weight']['weight'];
      $repetition = $exercise_container['repetition']['repetition'];
      $approaches = $exercise_container['approaches']['approaches'];

      if (empty($exercise_name) && $i == 0) {

        return $ajax_response->addCommand(new HtmlCommand('#exercises_' . $i, 'Exercise must be selected'));
        $flag[] = 'add some';
      }


      if (!empty($exercise)) {
        $weight_flag = $this->validateNumericField($ajax_response, $i, $weight, 'Weight');
        $repetition_flag = $this->validateNumericField($ajax_response, $i, $repetition, 'Repetition');
        $approaches_flag = $this->validateNumericField($ajax_response, $i, $approaches, 'Approaches');
      } else {
        $weight_flag  = $this->validateNonEmptyField($ajax_response, $i, $weight, 'Weight');
        $repetition_flag = $this->validateNonEmptyField($ajax_response, $i, $repetition, 'Repetition');
        $approaches_flag = $this->validateNonEmptyField($ajax_response, $i, $approaches, 'Approaches');
      }

      if ($weight_flag || $repetition_flag || $approaches_flag) {
        $flag[] = 'add some';
      }
    }

    if (empty($flag)) {
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

      $exercise_selection = $form_state->getValue('exercise_selection');
      unset($exercise_selection['actions']);


      $girls_training = $this->nodeStorage->create([
        'type' => 'girls_training',
        'title' => $title,
        'field_girls_body_part' => $body_part,
        'field_girls_type_of_training' => $training_type,
        'field_girls_training_date' => $date,
      ]);



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

      $referer = $this->getRequest()->headers->get('referer');
      $ajax_response->addCommand(new RedirectCommand($referer));
    }
      return $ajax_response;
  }



}