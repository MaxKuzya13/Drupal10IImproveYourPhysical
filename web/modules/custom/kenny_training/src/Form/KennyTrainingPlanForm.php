<?php

namespace Drupal\kenny_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
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

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name of training'),
      '#reqired' => TRUE,
    ];

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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Отримайте кількість вправ, які потрібно перевірити.
    $num_exercises = $form_state->getValue('num_exercises');

    for ($i = 0; $i < $num_exercises; $i++) {
      // Отримайте значення полів для відповідної вправи.
      $exercise_value = $form_state->getValue('exercise_' . $i);
      $exercise_name = !empty($exercise_value) ? Term::load($exercise_value)->getName() : '';
      $weight_value = $form_state->getValue('weight_' . $i);
      $repetition_value = $form_state->getValue('repetition_' . $i);
      $approaches_value = $form_state->getValue('approaches_' . $i);
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
    $body_part = $form_state->getValue('muscle_groups');
    $body_part_name = '';
    if(!empty($body_part)) {
      $term = Term::load($body_part);
      if ($term) {
        $body_part_name = $term->getName();
      }
    }
    $num_exercises = $form_state->getValue('num_exercises');
    $title = $form_state->getValue('title');
    for ($i = 0; $i < $num_exercises; $i++ ) {
      if (!empty($form_state->getValue('exercise_' . $i))) {
        $exercise[$i] = $form_state->getValue('exercise_' . $i);
        $weight[$i] = $form_state->getValue('weight_' . $i);
        $repetition[$i] = $form_state->getValue('repetition_' . $i);
        $approaches[$i] = $form_state->getValue('approaches_' . $i);
      }
    }

    $training_plan = Node::create([
      'type' => 'training_plan',
      'title' => $title,
      'field_body_part' => $body_part,
      'field_type_of_training' => $training_type,
    ]);


    for ($i = 0; $i < $num_exercises; $i++) {
      if (!empty($exercise[$i])) {
        $paragraph_type = strtolower($body_part_name);

        $paragraph = Paragraph::create([
          'type' => $paragraph_type,
          'field_exercise' => $exercise[$i],
          'field_weight' => $weight[$i],
          'field_repetition' => $repetition[$i],
          'field_approaches' => $approaches[$i],
        ]);

        // Зберігаємо параграф.
        $paragraph->save();

        // Додаємо параграф до поля "field_exercises" вузла "Training Plan."
        $training_plan->field_exercises[] = $paragraph;
      }
    }

    $training_plan->save();
    // Виводимо текст допоміжний
    \Drupal::messenger()->addMessage(
      t('The training plan @title for body part @body_part successfully add', ['@title' => $title, '@body_part' => $body_part_name])
    );
  }
}
