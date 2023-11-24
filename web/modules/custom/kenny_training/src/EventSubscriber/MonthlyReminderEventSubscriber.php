<?php

namespace Drupal\kenny_training\EventSubscriber;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\kenny_training\Event\MonthlyReminderEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MonthlyReminderEventSubscriber implements EventSubscriberInterface, ContainerInjectionInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * The mail manager.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected MailManagerInterface $mailManager;


  /**
   * Construct a new TrainingGirlsEventSubscriber object.
   *
   * @param AccountProxyInterface $current_user
   *   The current user.
   * @param MailManagerInterface $mail_manager
   *   The mail manager.
   */
  public function __construct(AccountProxyInterface $current_user, MailManagerInterface $mail_manager) {
    $this->currentUser = $current_user;
    $this->mailManager = $mail_manager;
  }

  /**
   * Create a new container for TrainingGirlsEventSubscribe.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('plugin.manager.mail')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[MonthlyReminderEvent::MONTHLY_REMINDER_EVENT][] = 'onCron';
    return $events;
  }

  public function onCron() {
    // Отримати поточну дату.
    $current_date = new DrupalDateTime('now');

    // Перевірити, чи поточний день місяця дорівнює 1.
    if ($current_date->format('j') == 25) {
      // Сформувати унікальний ідентифікатор для поточного місяця.
      $cache_key = 'monthly_reminder_' . $current_date->format('Ym');

//       Перевірити, чи лист вже відправлений за поточний місяць.
      $cache = \Drupal::cache('monthly_reminder')->get($cache_key);
      if (!$cache->data) {
        // Логіка для виклику події лише 1 числа кожного місяця.
        $formatted_date = $current_date->format('Y-m-d');
        $subject = 'Monthly reminder';
        $module = 'kenny_training';
        $key = 'monthly_reminder';
        $email = 'maxit2301@gmail.com';
        $langcode = $this->currentUser->getPreferredLangcode();
        $message = t("Today @date, don't forget to take measurements", [
          '@date' => $formatted_date
        ]);

        $params['subject'] = $subject;
        $params['date'] = $formatted_date;
        $params['message'] = $message;

        $this->mailManager->mail($module, $key, $email, $langcode, $params, NULL, TRUE);

        // Зберегти інформацію про те, що лист вже відправлений за поточний місяць.
        \Drupal::cache('monthly_reminder')->set($cache_key, TRUE);
      }
    }
  }


}