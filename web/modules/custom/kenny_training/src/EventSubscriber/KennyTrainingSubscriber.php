<?php declare(strict_types = 1);

namespace Drupal\kenny_training\EventSubscriber;

use Drupal\Component\Diff\Diff;
use Drupal\Component\EventDispatcher\Event;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Diff\DiffFormatter;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @todo Add description for this subscriber.
 */
class KennyTrainingSubscriber implements EventSubscriberInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  /**
   * The logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected LoggerChannelInterface $logger;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * The diff formatter.
   *
   * @var \Drupal\Core\Diff\DiffFormatter
   */
  protected DiffFormatter $diffFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected RendererInterface $renderer;


  /**
   * Construct a new KennyTrainingSubscriber object.
   *
   * @param LoggerChannelInterface $logger
   *   The logger channel.
   * @param AccountProxyInterface $current_user
   *   The current user.
   * @param DiffFormatter $diff_formatter
   *  The diff formatter.
   * @param RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(LoggerChannelInterface $logger, AccountProxyInterface $current_user, DiffFormatter $diff_formatter, RendererInterface $renderer) {
    $this->logger = $logger;
    $this->currentUser = $current_user;
    $this->diffFormatter = $diff_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ConfigEvents::SAVE][] = ['onConfigSave'];
    return $events;
  }

  /**
   * Callback for config save events.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   *   The config event.
   */
  public function onConfigSave(ConfigCrudEvent $event) {
    // Отримайте дані про конфігурацію та виконайте вашу логіку.
    $config = $event->getConfig();

    if ($config->isNew()) {
      return;
    }

    $original_data = explode("\n", Yaml::encode($config->getOriginal()));
    $current_data = explode("\n", Yaml::encode($config->get()));

    $diff = new Diff($original_data, $current_data);

    $build['diff'] = [
      '#type' => 'table',
      '#header' => [
        ['data' => 'From', 'colspan' => '2'],
        ['data' => 'To', 'colspan' => '2'],
      ],
      '#rows' => $this->diffFormatter->format($diff),
    ];
    $diff_html = $this->renderer->renderPlain($build);

    $message = new FormattableMarkup('<p> The %username user has changed the configuration of %config_id. </p> @changes>', [
      '%username' => $this->currentUser->getDisplayName(),
      '%config_id' => $config->getName(),
      '@changes' => Markup::create($diff_html),
    ]);
    $this->logger->notice($message);
  }


}
