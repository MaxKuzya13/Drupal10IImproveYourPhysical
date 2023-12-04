<?php declare(strict_types = 1);

namespace Drupal\kenny_random_training\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Kenny Stats form.
 */
final class KennyPickRandomTraining extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'kenny_pick_random_training';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {


    $form['mans_training'] = [
      '#type' => 'submit',
      '#value' => 'mans',
    ];

    $form['girls_training'] = [
      '#type' => 'submit',
      '#value' => 'girls',
    ];


    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $value = $form_state->getTriggeringElement();
    $date = $value['#value'];

    $_SESSION['kenny_pick_random_training_form_value'] = $date;
  }

}
