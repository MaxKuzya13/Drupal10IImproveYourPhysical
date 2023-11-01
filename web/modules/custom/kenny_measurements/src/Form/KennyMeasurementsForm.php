<?php

/**
 * @file
 * A form to enter measurements details
 */

namespace Drupal\kenny_measurements\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   */
  public function __construct(AccountInterface $current_user, TimeInterface $current_time, Connection $database, MessengerInterface $messenger ) {
    $this->currentUser = $current_user;
    $this->currentTime = $current_time;
    $this->database = $database;
    $this->messenger = $messenger;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('datetime.time'),
      $container->get('database'),
      $container->get('messenger'),

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
    $form['weight'] = [
      '#type' => 'textfield',
      '#title' => t('Your bodyweight'),
      '#required' => TRUE,
      '#suffix' => 'kg',
    ];
    $form['height'] = [
      '#type' => 'textfield',
      '#title' => t('Your height'),
      '#required' => TRUE,
      '#suffix' => 'sm',
    ];
    $form['biceps'] = [
      '#type' => 'textfield',
      '#title' => t('Your biceps'),
      '#required' => TRUE,
      '#suffix' => 'sm',
    ];
    $form['forearms'] = [
      '#type' => 'textfield',
      '#title' => t('Your forearms'),
      '#required' => TRUE,
      '#suffix' => 'sm',
    ];
    $form['chest'] = [
      '#type' => 'textfield',
      '#title' => t('Your chest'),
      '#required' => TRUE,
      '#suffix' => 'sm',
    ];
    $form['neck'] = [
      '#type' => 'textfield',
      '#title' => t('Your neck'),
      '#required' => TRUE,
      '#suffix' => 'sm',
    ];
    $form['waist'] = [
      '#type' => 'textfield',
      '#title' => t('Your waist'),
      '#required' => TRUE,
      '#suffix' => 'sm',
    ];
    $form['legs'] = [
      '#type' => 'textfield',
      '#title' => t('Your legs'),
      '#required' => TRUE,
      '#suffix' => 'sm',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Measurements'),
    ];
   return $form;

  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $fields_to_check = [
      'weight' => 'Weight',
      'height' => 'Height',
      'biceps' => 'Biceps',
      'forearms' => 'Forearms',
      'chest' => 'Chest',
      'neck' => 'Neck',
      'waist' => 'Waist',
      'legs' => 'Legs',
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
    $current_time = $this->currentTime->getRequestTime();

    $values = [
      'uid' => $uid,
      'weight' => $form_state->getValue('weight'),
      'height' => $form_state->getValue('height'),
      'biceps' => $form_state->getValue('biceps'),
      'forearms' => $form_state->getValue('forearms'),
      'chest' => $form_state->getValue('chest'),
      'neck' => $form_state->getValue('neck'),
      'waist' => $form_state->getValue('waist'),
      'legs' => $form_state->getValue('legs'),
      'created' => $current_time,
    ];
    try {

      // Start to build
      $query = $this->database->insert('kenny_measurements');

      // Specify the fields a query will insert into.
      $query->fields($values);

      // Execute the query
      $query->execute();

      $this->messenger->addMessage(
        t('Thank you for your measurements')
      );

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