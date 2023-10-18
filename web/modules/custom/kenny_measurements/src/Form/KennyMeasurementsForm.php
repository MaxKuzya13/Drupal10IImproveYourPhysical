<?php

/**
 * @file
 * A form to enter measurements details
 */

namespace Drupal\kenny_measurements\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class KennyMeasurementsForm extends FormBase {

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
        $form_state->setErrorByName($field_name, $this->t('@label must be an float with "." instead ","', ['@label' => $field_label]));
      }
    }

  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uid = \Drupal::currentUser()->id();
    $current_time = \Drupal::time()->getRequestTime();

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
      $query = \Drupal::database()->insert('kenny_measurements');

      // Specify the fields a query will insert into.
      $query->fields($values);

      // Execute the query
      $query->execute();

      \Drupal::messenger()->addMessage(
        t('Thank you for your measurements')
      );


    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
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