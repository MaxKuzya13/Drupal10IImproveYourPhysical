<?php

namespace Drupal\kenny_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
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

    $body_part = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('body_part');
    $body_part_options = ['' => $this->t('- Select -')];
    foreach ($body_part as $term) {
      $body_part_options[$term->tid] = $term->name;
    }

    $form['training_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type of training'),
      '#options' => $training_type_options,
      '#required' => TRUE,
    ];

    $form['num_groups'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of Muscle Groups'),
      '#options' => ['' => '- Select -', 1 => '1', 'full_body' => 'Full Body'],
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

    // Container for exercise selection.
    $form['exercise_selection'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'exercise-selection'],
    ];

    $num_exercises = 10;
    $form['num_exercises'] = [
      '#type' => 'hidden',
      '#value' => $num_exercises,
    ];

    for ($i = 0; $i < $num_exercises; $i++) {
      $form['exercise_selection']['exercises_' . $i] = $this->createExerciseSelectField($form, $form_state, $i);
    }
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
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

    $num_exercises = $form_state->getValue('num_exercises');
    for ($i = 0; $i < $num_exercises; $i++) {
      $form['exercise_selection']['exercises_' . $i]['exercise']['#options'] = $exercises_options;
      $response->addCommand(new HtmlCommand('#exercise-selection', $form['exercise_selection']));
    }


    return $response;
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
      $term = Term::load($body_part_id);
      $vocabulary_name = strtolower($term->getName());

      $exercises_terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadTree($vocabulary_name);

      $exercises_options = ['' => $this->t('- Select -')];
      foreach ($exercises_terms as $exercises_term) {
        $exercises_options[$exercises_term->tid] = $exercises_term->name;
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
    } else {
      return [];
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $training_type = $form_state->getValue('training_type');
    $body_part = $form_state->getValue('muscle_groups');
    $num_exercises = $form_state->getValue('num_exercises');
    $title = 'Something';
    for ($i = 0; $i < $num_exercises; $i++ ) {
      if (!empty($form_state->getValue('exercise_' . $i))) {
        $exercises[$i] = $form_state->getValue('exercise_' . $i);
      }
    }


    $training_plan = Node::create([
      'type' => 'training_plan',
      'title' => $title,
      'field_body_part' => $body_part,
      'field_type_of_training' => $training_type,
    ]);

    $training_plan->save();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodes = $node_storage->loadByProperties(['title' => $title]);

    $training_plan_exercise = reset($nodes);


    $exercises = [];
    // Тут крч треба створити параграфи по назві body_part котрі матимуть
    // exercise, weight, approaches, repetition і потім прив'язати їх до ноди
    // Мороки дохуя



    $training_plan_exercise->set('field_exercises', $exercises);

  }
}
