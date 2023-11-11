<?php declare(strict_types = 1);

namespace Drupal\kenny_stats\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Kenny Stats settings for this site.
 */
final class StatsExerciseForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'kenny_stats_stats_exercise';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['kenny_stats.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $chest_exercises_options = $this->getExercisesOptions('chest');
    dump($chest_exercises_options);
    $biceps_exercises_options = $this->getExercisesOptions('biceps');
    $shoulders_exercises_options = $this->getExercisesOptions('shoulders');
    $legs_exercises_options = $this->getExercisesOptions('legs');
    $triceps_exercises_options = $this->getExercisesOptions('triceps');
    $back_exercises_options = $this->getExercisesOptions('back');

    $form['chest'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Chest Exercise'),
      '#options' => $chest_exercises_options,
      '#default_value' => $this->config('kenny_stats.settings')->get('chest'),
    ];
    $form['biceps'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Biceps Exercise'),
      '#options' => $biceps_exercises_options,
      '#default_value' => $this->config('kenny_stats.settings')->get('biceps'),
    ];
    $form['shoulders'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Shoulders Exercise'),
      '#options' => $shoulders_exercises_options,
      '#default_value' => $this->config('kenny_stats.settings')->get('shoulders'),
    ];
    $form['legs'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Legs Exercise'),
      '#options' => $legs_exercises_options,
      '#default_value' => $this->config('kenny_stats.settings')->get('legs'),
    ];
    $form['triceps'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Triceps Exercise'),
      '#options' => $triceps_exercises_options,
      '#default_value' => $this->config('kenny_stats.settings')->get('triceps'),
    ];
    $form['back'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Back Exercise'),
      '#options' => $back_exercises_options,
      '#default_value' => $this->config('kenny_stats.settings')->get('back'),
    ];
    return parent::buildForm($form, $form_state);
  }

  protected function getExercisesOptions($taxonomy_name) {
    $body_part_exercises = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
      ->loadTree($taxonomy_name);

    $body_part_exercises_options = ['' => $this->t('- Select -')];
    foreach ($body_part_exercises as $term) {
      $body_part_exercises_options[$term->tid] = $term->name;
    }

    return $body_part_exercises_options;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('kenny_stats.settings')
      ->set('biceps', $form_state->getValue('biceps'))
      ->set('chest', $form_state->getValue('chest'))
      ->set('shoulders', $form_state->getValue('shoulders'))
      ->set('legs', $form_state->getValue('legs'))
      ->set('triceps', $form_state->getValue('triceps'))
      ->set('back', $form_state->getValue('back'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
