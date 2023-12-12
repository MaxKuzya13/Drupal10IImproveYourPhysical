<?php declare(strict_types = 1);

namespace Drupal\kenny_stats\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Kenny Stats form.
 */
final class StatsDateForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'kenny_stats_date';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['submit_1_month'] = [
      '#type' => 'submit',
      '#value' => '1 month',
    ];

    $form['submit_3_month'] = [
      '#type' => 'submit',
      '#value' => '3 month',
    ];

    $form['submit_6_month'] = [
      '#type' => 'submit',
      '#value' => '6 month',
    ];

    $form['submit_1_year'] = [
      '#type' => 'submit',
      '#value' => '1 year',
    ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $value = $form_state->getTriggeringElement();
    $date = $value['#value'];

    $_SESSION['kenny_stats_form_value'] = $date;
  }

}
