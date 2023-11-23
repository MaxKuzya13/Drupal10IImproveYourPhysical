<?php declare(strict_types = 1);

namespace Drupal\kenny_girls_stats\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Kenny Girls Stats settings for this site.
 */
final class GirlsStatsExerciseForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'kenny_girls_stats_girls_stats_exercise';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['kenny_girls_stats.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $inner_thigh_options = $this->getExercisesOptions('Inner Thigh');
    $quadriceps_options = $this->getExercisesOptions('Quadriceps');
    $hamstring_options = $this->getExercisesOptions('Hamstring');

    $shoulders_options = $this->getExercisesOptions('Shoulders');
    $triceps_options = $this->getExercisesOptions('Triceps');
    $biceps_options = $this->getExercisesOptions('Biceps');
    $back_options = $this->getExercisesOptions('Back');
    $chest_options = $this->getExercisesOptions('Chest');

    $glutes_options = $this->getExercisesOptions('Glutes Container');


    $form['lower_body'] = [
      '#markup' => '<h2> Lower Body </h2>',
    ];


    $form['inner_thigh'] = [
      '#type' => 'select',
      '#title' => $this->t('Select inner thigh exercise'),
      '#options' => $inner_thigh_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('inner_thigh'),
    ];

    $form['quadriceps'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Quadriceps exercise'),
      '#options' => $quadriceps_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('quadriceps'),
    ];

    $form['hamstring'] = [
      '#type' => 'select',
      '#title' => $this->t('Select hamstring exercise'),
      '#options' => $hamstring_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('hamstring'),
    ];

    $form['upper_body'] = [
      '#markup' => '<h2> Upper Body </h2>',
    ];

    $form['shoulders'] = [
      '#type' => 'select',
      '#title' => $this->t('Select shoulders exercise'),
      '#options' => $shoulders_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('shoulders'),
    ];

    $form['triceps'] = [
      '#type' => 'select',
      '#title' => $this->t('Select triceps exercise'),
      '#options' => $triceps_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('triceps'),
    ];

    $form['biceps'] = [
      '#type' => 'select',
      '#title' => $this->t('Select biceps exercise'),
      '#options' => $biceps_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('biceps'),
    ];

    $form['back'] = [
      '#type' => 'select',
      '#title' => $this->t('Select back exercise'),
      '#options' => $back_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('back'),
    ];

    $form['chest'] = [
      '#type' => 'select',
      '#title' => $this->t('Select chest exercise'),
      '#options' => $chest_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('chest'),
    ];


    $form['glutes_container'] = [
      '#markup' => '<h2> Glutes </h2>',
    ];

    $form['glutes'] = [
      '#type' => 'select',
      '#title' => $this->t('Select glutes exercise'),
      '#options' => $glutes_options,
      '#default_value' => $this->config('kenny_girls_stats.settings')->get('glutes'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * Exercises options.
   *
   * @param string $taxonomy_name
   *   The taxonomy name.
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getExercisesOptions($taxonomy_name) {
    $all_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
      ->loadTree('girls_training');

    $taxonomy = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
      ->loadTree('girls_training', 0, 2, true);

    $exercises_options = ['' => $this->t('- Select -')];
    foreach ($taxonomy as $term) {
      if ($term->getName() == $taxonomy_name) {
        $parent_id = $term->id();
      }
    }

    if ($parent_id) {
      foreach ($all_terms as $term) {
        if ($term->parents[0] == $parent_id) {
          $exercises_options[$term->tid] = $term->name;
        }
      }

    }

    return $exercises_options;

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
    $this->config('kenny_girls_stats.settings')
      ->set('inner_thigh', $form_state->getValue('inner_thigh'))
      ->set('quadriceps', $form_state->getValue('quadriceps'))
      ->set('hamstring', $form_state->getValue('hamstring'))
      ->set('shoulders', $form_state->getValue('shoulders'))
      ->set('triceps', $form_state->getValue('triceps'))
      ->set('biceps', $form_state->getValue('biceps'))
      ->set('back', $form_state->getValue('back'))
      ->set('chest', $form_state->getValue('chest'))
      ->set('glutes', $form_state->getValue('glutes'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
