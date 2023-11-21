<?php

namespace Drupal\kenny_training\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\node\NodeInterface;


class TrainingGirlsEvent extends Event {

  /**
   * The node object.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * @param \Drupal\node\NodeInterface  $node
   *   The node Object.
   */
  public function __construct(NodeInterface $node) {
    $this->node = $node;
  }

  /**
   * Get node.
   *
   * @return \Drupal\node\NodeInterface
   */
  public function getNode() {
    return $this->node;
  }
}
