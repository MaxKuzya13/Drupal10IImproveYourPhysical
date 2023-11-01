<?php

namespace Drupal\kenny_training\Service\FilterDate;

interface FilterDateInterface {

  /**
   * The filter training by date.
   *
   * @param string $selected_period
   *   The selected period.
   * @return array
   *    Array of node id
   */
  public function filterTrainingByDate($selected_period = 'default');

}