<?php

namespace Drupal\kenny_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;

class KennyTrainingPlanForm extends FormBase {

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
    $training_type = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('type_of_training');
    $training_type_options = ['' => $this->t('- Select -')];
    foreach ($training_type as $term) {
      $training_type_options[$term->tid] = $term->name;
    }

    $form['training_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type of training'),
      '#options' => $training_type_options,
      '#attributes' => ['id' => 'training-type-attributes'],
      '#required' => TRUE,
    ];

    $form['num_groups'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of Muscle Groups'),
      '#options' => ['' => '- Select -', 1 => '1', 2 => '2', 'full_body' => 'Full Body'],
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::numGroupsAjaxCallback',
        'event' => 'change',
      ]
    ];

    $form['muscle_groups'] = [
      '#type' => 'select',
      '#title' => 'Choose body part',
      '#options' => [],
      '#prefix' => '<div id="muscle-groups-wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => '::chooseExerciseAjax',
        'event' => 'change'
      ]
    ];

    // Контейнер для полів вибору вправ.
    $form['exercises_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'exercises-container'],
    ];

    // Початкове поле вибору вправ.
    $form['exercises_container']['exercises_0'] = [
      '#type' => 'select',
      '#title' => $this->t('Choose exercise'),
      '#options' => [], // Поки порожнє
      '#prefix' => '<div class="exercise-item" id="exercises_0">',
      '#suffix' => '</div>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  public function numGroupsAjaxCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $num_groups = $form_state->getValue('num_groups');
    $body_part = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('body_part');
    $body_part_options = ['' => $this->t('- Select -')];
    foreach ($body_part as $term) {
      $body_part_options[$term->tid] = $term->name;
    }

    if ($num_groups == '1') {
      $form['muscle_groups']['#options'] = $body_part_options;
      $response->addCommand(new HtmlCommand('#muscle-groups-wrapper', $form['muscle_groups']));
    }

    return $response;
  }

  public function chooseExerciseAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $body_part_id = $form_state->getValue('muscle_groups');
    $term = Term::load($body_part_id);
    $vocabulary_name = strtolower($term->getName());

    $exercises_terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree($vocabulary_name);

    $exercises_options = ['' => $this->t('- Select -')];
    foreach ($exercises_terms as $exercises_term) {
      $exercises_options[$exercises_term->tid] = $exercises_term->name;
    }

    $form['exercises_container']['exercises_0']['#options'] = $exercises_options;
    $response->addCommand(new HtmlCommand('#exercises_0', $form['exercises_container']['exercises_0']));

    return $response;
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Додайте код для обробки форми тут.
  }
}
