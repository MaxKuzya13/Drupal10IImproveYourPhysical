<?php declare(strict_types = 1);

namespace Drupal\kenny_training\EventSubscriber;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\kenny_training\Event\KennyPreprocessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @todo Add description for this subscriber.
 */
class KennyTrainingSubscriber implements EventSubscriberInterface {


//  /**
//   * The entity type manager.
//   *
//   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
//   */
//  protected $entityTypeManager;
//
//  /**
//   * The messenger service.
//   *
//   * @var \Drupal\Core\Messenger\MessengerInterface
//   */
//  protected $messenger;
//
//  /**
//   * The current user.
//   *
//   * @var \Drupal\Core\Session\AccountProxyInterface
//   */
//  protected $currentUser;
//
//  /**
//   * Constructs a new GirlsTrainingEventSubscriber object.
//   *
//   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
//   *   The entity type manager.
//   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
//   *   The messenger service.
//   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
//   *   The current user.
//   */
//  public function __construct(EntityTypeManagerInterface $entity_type_manager, MessengerInterface $messenger, AccountProxyInterface $current_user) {
//    $this->entityTypeManager = $entity_type_manager;
//    $this->messenger = $messenger;
//    $this->currentUser = $current_user;
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public static function getSubscribedEvents() {
//    $events[KennyPreprocessEvent::PREPROCESS_PAGE][] = ['onEntityInsert'];
//    return $events;
//  }
//
//  /**
//   * Reacts on entity insert event.
//   *
//   * @param \Symfony\Component\EventDispatcher\Event $event
//   *   The event to process.
//   */
//  public function onEntityInsert(Event $event) {
//    $entity = $event->getEntity();
//
//    // Check if the inserted entity is of type "girls_training".
//    if ($entity->getEntityTypeId() == 'girls_training') {
//      // Get the creation date.
//      $created = $entity->getCreatedTime();
//
//      // Get the user who created the entity.
//      $user_id = $entity->getOwnerId();
//      $user = $this->entityTypeManager->getStorage('user')->load($user_id);
//      $username = $user->getUsername();
//
//      // Get the title of the entity.
//      $title = $entity->label();
//
//      // Send a message.
//      $this->messenger->addMessage("New girls_training created:\nDate: $created\nUser: $username\nTitle: $title");
//    }
//  }


}
