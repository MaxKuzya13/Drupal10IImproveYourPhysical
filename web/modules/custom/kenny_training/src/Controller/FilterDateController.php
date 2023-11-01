<?php

namespace Drupal\kenny_training\Controller;


use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FilterDateController extends ControllerBase {

  /**
   * The favorite manager service interface.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface $block_manager
   */
  protected $blockManager;

  /**
   * Construct a new FavoriteManager instance.
   */
  public function __construct(BlockManagerInterface $block_manager) {
    $this->blockManager = $block_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.block')  // Замініть на ім'я вашого сервісу
    );
  }


  /**
   * Change timeline.
   *
   * @param $selected_date
   *   The selected date.
   * @return JsonResponse
   */
  public function changeSelectedDate(Request $request, $selected_date) {

    $block = $this->blockManager->createInstance('kenny_filter_training_date_block');
    // Отримуємо поточну конфігурацію блоку
    $config = $block->getConfiguration();
    // Оновлюємо значення 'period' в конфігурації
    $config['period'] = $selected_date;
    $block->setConfiguration($config);

    // Зберігаємо оновлену конфігурацію блоку
    $block->blockSubmit('', new FormState());
    // Поверніть відповідь (наприклад, JSON).
    return new JsonResponse(['status' => 'success']);
  }
}