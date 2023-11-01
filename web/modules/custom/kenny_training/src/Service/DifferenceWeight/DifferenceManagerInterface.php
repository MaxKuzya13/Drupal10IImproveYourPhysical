<?php

namespace Drupal\kenny_training\Service\DifferenceWeight;

interface DifferenceManagerInterface {

  /**
   * @param $pid
   *   The paragraph id.
   * @return mixed
   */
  public function getCurrentParagraph($pid);
}