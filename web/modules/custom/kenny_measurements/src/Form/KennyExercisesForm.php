<?php

namespace Drupal\kenny_measurements\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;

class KennyExercisesForm extends FormBase {

//  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface
//   *   The entity type manager
//   * */
//  protected $entityTypemanager;
//
//  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
//    $this->entityTypemanager = $entity_type_manager;
//  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kenny_exercises_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('body_part');
    $term_options = ['' => $this->t('- Select -')];
    foreach ($terms as $term) {
      $term_options[$term->tid] = $term->name;
    }

    $form['term'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#options' => $term_options,
      '#required' => TRUE,
    ];

    $form['exercise_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Exercise Name'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }


  /**
   *  {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Отримуємо айді терміна який вибрали в select
    $term_id = $form_state->getValue('term');
    // Отримуємо назву вправи яку хочемо додати
    $exercise = $form_state->getValue('exercise_name');

    $term = Term::load($term_id);
    // Так як ми отримували id терміна із Vocabulary Body part, то нам тепер
    // треба отримати сам vocabulary для цього терміна, а він в нас має таку
    // назву, тільки з маленької бувки.
    $vocabulary_name = strtolower($term->getName());

    $body_part = Vocabulary::load($vocabulary_name);
    if ($body_part) {
      // Перевіряємо чи існує вже вправа з такою назвою як у нас
      $query = \Drupal::entityQuery('taxonomy_term')
        ->condition('vid', $body_part->id())
        ->condition('name', $exercise)
        ->accessCheck(FALSE)
        ->range(0,1);

      $tids = $query->execute();

      // Якщо таких вправ нема, то додаємо
      if (empty($tids)) {
        $new_exercise = Term::create([
          'vid' => $body_part->id(),
          'name' => $exercise,
        ]);
        $new_exercise->save();

        // Виводимо текст допоміжний
        \Drupal::messenger()->addMessage(
          t('The exercise "@exercise" has been added to the body part "@body_part"', ['@exercise' => $exercise, '@body_part' => $term->getName()])
        );
      } else {
        \Drupal::messenger()->addMessage(
          t('The exercise "@exercise" already exists in the body part "@body_part"', ['@exercise' => $exercise, '@body_part' => $term->getName()])
        );
      }

    } else {
      \Drupal::messenger()->addMessage(
        t('The body part "@body_part" does not exist.', ['@body_part' => $term->getName()])
      );
    }

  }


}