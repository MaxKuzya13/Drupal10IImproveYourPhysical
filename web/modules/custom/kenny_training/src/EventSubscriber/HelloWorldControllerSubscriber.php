<?php declare(strict_types = 1);

namespace Drupal\kenny_training\EventSubscriber;

use Drupal\Core\Config\ConfigEvents;
use Drupal\kenny_training\Event\ExampleEvent;
use Drupal\kenny_training\Event\HelloWorldControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @todo Add description for this subscriber.
 */
class HelloWorldControllerSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ExampleEvent::HELLO_WORLD_BUILD][] = ['onHelloWorldBuild'];
    return $events;
  }

  /**
   * @param HelloWorldControllerEvent $event
   *   The hello world controller event.
   * @return void
   */
  public function onHelloWorldBuild(HelloWorldControllerEvent $event) {
    $event->setPageTitle('Hello from event');
    $content = $event->getPageContent();
    $content['additional'] = [
      '#type' => 'fieldset',
      '#title' => 'Additional content',
    ];
    $content['additional']['description'] = [
      '#markup' => 'This content was additionally added from hello world event subscriber.'
    ];

    $event->setPageContent($content);
  }
}