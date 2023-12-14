<?php

/**
 * @file
 * A form to enter measurements details
 */

namespace Drupal\kenny_measurements\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

class KennyMeasurementsForm extends FormBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $currentUser;

  /**
   * The time created.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $currentTime;

  /**
   * The database.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $database;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * KennyMeasurementsForm constructor
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Component\Datetime\TimeInterface $current_time
   *   The current time.
   * @param \Drupal\Core\Database\Connection $database
   *   The database.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *    The entity type manager.
   */
  public function __construct(AccountInterface $current_user, TimeInterface $current_time, Connection $database, MessengerInterface $messenger, EntityTypeManagerInterface $entity_type_manager ) {
    $this->currentUser = $current_user;
    $this->currentTime = $current_time;
    $this->database = $database;
    $this->messenger = $messenger;
    $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('datetime.time'),
      $container->get('database'),
      $container->get('messenger'),
      $container->get('entity_type.manager'),

    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kenny_measurements_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $form['height'] = [
      '#type' => 'textfield',
      '#title' => t('Your height (sm)'),
      '#required' => TRUE,
    ];

    $form['weight'] = [
      '#type' => 'textfield',
      '#title' => t('Your bodyweight (kg)'),
      '#required' => TRUE,
    ];

    $form['neck'] = [
      '#type' => 'textfield',
      '#title' => t('Your neck (sm)'),
      '#required' => TRUE,
    ];

    $form['chest'] = [
      '#type' => 'textfield',
      '#title' => t('Your chest (sm)'),
      '#required' => TRUE,
    ];

    $form['biceps'] = [
      '#type' => 'textfield',
      '#title' => t('Your biceps (sm)'),
      '#required' => TRUE,
    ];

    $form['forearms'] = [
      '#type' => 'textfield',
      '#title' => t('Your forearms (sm)'),
      '#required' => TRUE,
    ];

    $form['waist'] = [
      '#type' => 'textfield',
      '#title' => t('Your waist (sm)'),
      '#required' => TRUE,
    ];

    $form['glutes'] = [
      '#type' => 'textfield',
      '#title' => t('Your glutes (sm)'),
      '#required' => TRUE,
    ];

    $form['thigh'] = [
      '#type' => 'textfield',
      '#title' => t('Your thigh (sm)'),
      '#required' => TRUE,
    ];

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
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save'),
    ];
   return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $fields_to_check = [
      'weight' => 'Weight',
      'height' => 'Height',
      'biceps' => 'Biceps',
      'forearms' => 'Forearms',
      'chest' => 'Chest',
      'neck' => 'Neck',
      'waist' => 'Waist',
      'thigh' => 'Thigh',
      'glutes' => 'Glutes',
    ];

    foreach ($fields_to_check as $field_name => $field_label) {
      if (!$this->isFloatValue($form_state, $field_name)) {
        $form_state->setErrorByName($field_name, $this->t('@label must be an float with "." instead ","', [
          '@label' => $field_label
        ]));
      }
    }

  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {


    $uid = $this->currentUser->id();
    $user_name = $this->currentUser->getDisplayName();
    $date = $form_state->getValue('date');
    $drupal_date = strtotime($date);
    $formatted_date = date('d F Y', $drupal_date);

    $title = $formatted_date . ' | Measurements by ' .  $user_name;

    try {
      /** @var \Drupal\node\NodeStorageInterface $node_storage */
      $node_storage = $this->entityTypeManager->getStorage('node');
      $measurements = $node_storage->create([
        'type' => 'measurements',
        'title' => $title,
        'field_uid' => $uid,
        'field_weight' => $form_state->getValue('weight'),
        'field_height' => $form_state->getValue('height'),
        'field_biceps' => $form_state->getValue('biceps'),
        'field_forearms' => $form_state->getValue('forearms'),
        'field_chest' => $form_state->getValue('chest'),
        'field_neck' => $form_state->getValue('neck'),
        'field_waist' => $form_state->getValue('waist'),
        'field_thigh' => $form_state->getValue('thigh'),
        'field_glutes' => $form_state->getValue('glutes'),
        'field_created' => $form_state->getValue('date'),
      ]);

      $measurements->save();

      $this->messenger->addMessage(
        t('Thank you for your measurements')
      );

      $form_state->setRedirectUrl(Url::fromUri('internal:/training/man-stats'));

    } catch (\Exception $e) {
      $this->messenger->addError(
        t('Unable to save measurement at this time die to database error.
          Please try again.')
      );
    }
  }


  /**
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string $field_name
   *   The name of the field to be checked.
   * @return bool
   *   return TRUE if value of field is float
   */
  private function isFloatValue($form_state, $field_name) {
    $value = $form_state->getValue($field_name);
    return preg_match('/^\d+(\.\d+)?$/', $value);
  }

}