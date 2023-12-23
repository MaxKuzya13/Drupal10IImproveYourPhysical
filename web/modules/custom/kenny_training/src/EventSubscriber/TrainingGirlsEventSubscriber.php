<?php

namespace Drupal\kenny_training\EventSubscriber;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\kenny_training\Event\FinalCreateTrainingGirlsEvent;
use Drupal\kenny_training\Event\TrainingGirlsEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrainingGirlsEventSubscriber implements EventSubscriberInterface, ContainerInjectionInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * The messanger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface;
   */
  protected MessengerInterface $messenger;

  /**
   * Construct a new TrainingGirlsEventSubscriber object.
   *
   * @param AccountProxyInterface $current_user
   *   The current user.
   * @param MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(AccountProxyInterface $current_user, MessengerInterface $messenger) {
    $this->currentUser = $current_user;
    $this->messenger = $messenger;
  }

  /**
   * Create a new container for TrainingGirlsEventSubscribe.
   */
  public static function create(ContainerInterface $container) {
    $container->get('current_user');
    $container->get('messenger');
  }

  /**
  * {@inheritdoc}
  */
  public static function getSubscribedEvents() {
    $events[FinalCreateTrainingGirlsEvent::TRAINING_GIRLS_EVENT][] = 'onGirlsTrainingNodeCreation';
    return $events;
  }

  /**
   * Send successfully email and motivation.
   *
   * @param TrainingGirlsEvent $event
   *   The training girl event.
   * @return void
   */
  public function onGirlsTrainingNodeCreation(TrainingGirlsEvent $event) {
    $node = $event->getNode();

    // Перевірте, чи це вузол типу "girls_training".
    if ($node->getType() === 'girls_training') {

      $body_part = $node->get('field_girls_body_part')->entity->getName();
      $type_of_training = $node->get('field_girls_type_of_training')->entity->getName();
      $date = $node->get('field_girls_training_date')->value;

      // Data for mail.
      $email = 'maxit2301@gmail.com';
      $from = 'maxit2301@gmail.com';
      $subject = 'Created new training';
      $message = t('Created new training. Muscle Group: @body_part; Type of training: @type_of_training; Date: @date', [
      '@body_part' => $body_part,
      '@type_of_training' => $type_of_training,
      '@date' => $date,
      ]);

      $module = 'kenny_training';
      $key = 'create_training';
      $langcode = $this->currentUser->getPreferredLangcode();
      $params['subject'] = $subject;
      $params['body_part'] = $body_part;
      $params['type_of_training'] = $type_of_training;
      $params['date'] = $date;

      // Send email
      \Drupal::service('plugin.manager.mail')->mail($module, $key, $email, $langcode, $params, NULL, TRUE);


      $message_text = $this->getMotivation();
      $text_id = array_rand($message_text);
      $text = $message_text[$text_id];
      $this->messenger->addMessage($text);

    }
  }

  /**
   * List of motivation.
   *
   * @return array
   */
  protected function getMotivation() {
    return [
      'Для чого взагалі ти це робиш?',
      'Тобі не здається, що цього замало?',
      'Розслабся, в тебе нічого не вийде!',
      'І це ти називаєш тренуванням?',
      "Спробуй, хоча б якось!",
      "Ти не спроможна!",
      "Забудь про свої мрії!",
      "Все найгірше попереду!",
      "Посміхнись, ти зробила це!",
      "Ще одне тренування завершено вдало!",
      "На один крок ближча до мети!",
      "Просто посміховисько",
    ];
  }


}