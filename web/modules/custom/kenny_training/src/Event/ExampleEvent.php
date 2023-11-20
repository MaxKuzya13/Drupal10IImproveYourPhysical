<?php

namespace Drupal\kenny_training\Event;

final class ExampleEvent {

  /**
   * Name of the event.
   *
   * @Event
   *
   * @see \Drupal\kenny_training\Event\HelloWorldControllerEvent
   * @see \Drupal\kenny_training\Controller\ExampleController
   *
   * @var string
   */
  const HELLO_WORLD_BUILD = 'kenny_training.hello_world_build';
}