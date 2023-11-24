<?php

namespace Drupal\kenny_training\Event;

use Drupal\Component\EventDispatcher\Event;

class MonthlyReminderEvent extends Event {

  /**
   * Bane of the event.
   */
  const MONTHLY_REMINDER_EVENT = 'kenny_training.monthly_reminder_event';
}