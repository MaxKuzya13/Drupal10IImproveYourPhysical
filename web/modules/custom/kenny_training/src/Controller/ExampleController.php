<?php

namespace Drupal\kenny_training\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\kenny_training\Event\ExampleEvent;
use Drupal\kenny_training\Event\HelloWorldControllerEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExampleController implements ContainerInjectionInterface {

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected EventDispatcherInterface $eventDispatcher;

  /**
   * Constructs a new ExampleController object.
   *
   * @param EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   */
  public function __construct(EventDispatcherInterface $event_dispatcher) {
    $this->eventDispatcher = $event_dispatcher;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('event_dispatcher')
    );
  }
  public function build() {
    $page_content = ['#markup' => 'Hello world'];

    $event = new HelloWorldControllerEvent($page_content);
    $this->eventDispatcher->dispatch($event, ExampleEvent::HELLO_WORLD_BUILD);

    $build['#title'] = $event->getPageTitle();

    $build['content'] = $event->getPageContent();

    return $build;
  }



}