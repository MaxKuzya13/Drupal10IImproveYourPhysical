<?php

namespace Drupal\kenny_training\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\kenny_training\Service\Favorite\FavoriteManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FavoriteController extends ControllerBase {

  /**
   * The favorite manager service interface.
   *
   * @var Drupal\kenny_training\Service\Favorite\FavoriteManagerInterface $favoriteService
   *
   */
  protected FavoriteManagerInterface $favoriteService;

  /**
   * Construct a new FavoriteManager instance.
   */
  public function __construct(FavoriteManagerInterface $favorite_service) {
    $this->favoriteService = $favorite_service;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('kenny_training.favorite_manager')  // Замініть на ім'я вашого сервісу
    );
  }

  public function addFavorite($uid, $nid) {

    $result = $this->favoriteService->setFavorite($uid, $nid);

    // Поверніть відповідь (наприклад, JSON).
    return new JsonResponse(['status' => 'success']);
  }

  public function deleteFavorite($uid, $nid) {

    $result = $this->favoriteService->deleteFavorite($uid, $nid);

    // Поверніть відповідь (наприклад, JSON).
    return new JsonResponse(['status' => 'success']);
  }
}