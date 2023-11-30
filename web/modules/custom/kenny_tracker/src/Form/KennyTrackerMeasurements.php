<?php

namespace Drupal\kenny_tracker\Form;


use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\kenny_tracker\Service\TrackerMeasurements\KennyTrackerMeasurementsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KennyTrackerMeasurements extends FormBase {

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
   * The tracker measurements.
   *
   * @var \Drupal\kenny_tracker\Service\TrackerMeasurements\KennyTrackerMeasurementsInterface
   */
  protected $trackerMeasurements;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * KennyTrainingPlanForm constructor
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   * @param \Drupal\kenny_tracker\Service\TrackerMeasurements\KennyTrackerMeasurementsInterface $tracker_measurements
   *   The tracker_measurements.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *    The current user.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, MessengerInterface $messenger, KennyTrackerMeasurementsInterface $tracker_measurements, AccountProxyInterface $current_user) {
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
    $this->trackerMeasurements = $tracker_measurements;
    $this->currentUser = $current_user;
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
      $container->get('kenny_tracker.tracker_measurements'),
      $container->get('current_user'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kenny_tracker_measurements_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

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

    // Container for exercise selection.
    $form['measurements_selection'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'measurements-selection'],
    ];

    // Gather the number of names in the form already.
    $num_exercises = $form_state->get('num_exercises');
    // We have to ensure that there is at least one name field.
    if ($num_exercises === NULL) {
      $name_field = $form_state->set('num_exercises', 1);
      $num_exercises = 1;
    }

    $form['#tree'] = TRUE;

    for ($i = 0; $i < $num_exercises; $i++) {

      $form['measurements_selection'][$i] = $this->createMeasurementsField($form_state);
    }

    $form['measurements_selection']['actions'] = ['#type' => 'actions'];

    if ($num_exercises > 1) {
      $form['measurements_selection']['actions']['remove_field'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove one field'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addMoreCallback',
          'wrapper' => 'measurements-selection',
        ],
      ];
    };

    $form['measurements_selection']['actions']['add_field'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more field'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'wrapper' => 'measurements-selection',
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
   * Create main fields.
   *
   * @param FormStateInterface $form_state
   * @return array
   */
  protected function createMeasurementsField(FormStateInterface $form_state) {
    $options = $this->getMeasurementsOption();

    $form_field['measurement_name'] = [

      '#type' => 'select',
      '#title' => $this->t('Select the prefered body part'),
      '#options' => $options,
      '#empty_option' => '- Select -',
      '#default_value' => '- Select -'
    ];

    $form_field['measurement_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter the prefered value'),
    ];

    return $form_field;
  }

  /**
   * Options by measurements.
   *
   * @return array
   */
  protected function getMeasurementsOption() {
    return [
      'Biceps' => 'Biceps',
      'Chest' => 'Chest',
      'Forearms' => 'Forearms',
      'Weight' => 'Weight',
      'Neck' => 'Neck',
      'Thigh' => 'Thigh',
      'Waist' => 'Waist',
      'Glutes' => 'Glutes',
    ];
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
    return $form['measurements_selection'];
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addMoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['measurements_selection'];
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
   * {@inheritdoc}
   */
  public function ajaxValidateSave(array &$form, FormStateInterface $form_state) {
    $measurements_selection = $form_state->getValue('measurements_selection');
    unset($measurements_selection['actions']);

    for ($i = 0; $i < count($measurements_selection); $i++) {

      $measurement_name = $measurements_selection[$i]['measurement_name'];
      $measurement_value = $measurements_selection[$i]['measurement_value'];

      if (!empty($measurement_name)) {
        $this->validateNumericField($form_state, $measurement_value, $measurement_name);
      } else {
        $this->validateNonEmptyField($form_state, $measurement_value, $measurement_name);
      }

      for ($k = 0; $k < $i; $k++) {
        $measurement_name_relative = $measurements_selection[$k]['measurement_name'];

        if ($measurement_name_relative == $measurement_name) {
          $this->validateSameName($form_state, $measurement_name_relative);
        }
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
  public function validateNumericField(FormStateInterface $form_state, $value, $measurement_name) {
    if (!is_numeric($value) || floatval($value) <= 0) {
      $form_state->setErrorByName($measurement_name, $this->t("Value for @exercise must be a float. Use '.' instead ','", [
        '@exercise' => $measurement_name,
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
  public function validateNonEmptyField(FormStateInterface $form_state, $measurement_value, $measurement_name) {
    if (!empty($value)) {
      $field_name = strtolower($measurement_name);
      $form_state->setErrorByName($field_name, $this->t('Entered %field_label @value for empty field exercise.', [
        '%field_label' => $measurement_name,
        '@value' => $measurement_value
      ]));
    }
  }

  /**
   * Check if have same fields.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *    Об'єкт стану форми.
   * @param string $measurement_name
   *   The name of measurements
   * @return void
   */
  public function validateSameName(FormStateInterface $form_state, $measurement_name) {

    $field_name = strtolower($measurement_name);
    $form_state->setErrorByName($field_name, $this->t('You have two more field with the same name: %field_label', [
      '%field_label' => $measurement_name,
    ]));

  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $date = $form_state->getValue('date');
    $drupal_date = strtotime($date);
    $formatted_date = date('d F Y', $drupal_date);

    $title = $formatted_date . ' | Track Measurements';
    // ------------------------------------------------------- Current Measurements

    $uid = $this->currentUser->id();

    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'measurements')
      ->condition('field_uid',$uid)
      ->condition('field_created',$date, '<=')
      ->sort('field_created', 'DESC')
      ->accessCheck(FALSE); // Bypass node access check, or adjust as needed.
    $nids = $query->execute();

    // Get id of started measurement by tracker.
    if ($nids) {
      $current_measurement_id = reset($nids);
    }

    // Create tracker measurements object.
    $tracker_measurements = $this->nodeStorage->create([
      'type' => 'tracker_measurements',
      'title' => $title,
      'field_created' => $date,
      'field_uid' => $uid,
    ]);

    // Set started measurement field.
    $tracker_measurements->field_current_measurements = $current_measurement_id;

    // Delete actions by array.
    $measurements_selection = $form_state->getValue('measurements_selection');
    unset($measurements_selection['actions']);


    // Create desired result.
    foreach ($measurements_selection as $paragraph_tracker_measurements) {
      $name = $paragraph_tracker_measurements['measurement_name'];
      $value = $paragraph_tracker_measurements['measurement_value'];

      $paragraph = $this->paragraphStorage->create([
        'type' => 'prefered_measurements',
        'field_measurement_name' => $name,
        'field_measurement_value' => $value,
      ]);

      /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
      $paragraph->save();

      // Set desired result.
      $tracker_measurements->field_tracker_measurement[] = $paragraph;
    }

    // Save tracker measurements object.
    $tracker_measurements->save();


    // Check to have a content of Track Measurements.
    $is_track = $this->trackerMeasurements->isTrack($uid);

    if (!$is_track) {
      // Add to database that this user have a track.
      $this->trackerMeasurements->setTrack($uid, $tracker_measurements->id());
    }


    $this->messenger->addMessage(
      t('The @title successfully add', [
        '@title' => $title,
      ])
    );

    $form_state->setRedirectUrl(Url::fromUri('internal:/test-tracker'));



  }
}