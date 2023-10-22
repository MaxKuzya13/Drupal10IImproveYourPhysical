<?php

namespace Drupal\kenny_training\Service\Favorite;

interface FavoriteManagerInterface {

  /**
   * Перевіряє чи є дана нода для даного користувача в улюблених
   * @param int $uid
   *   Current user id.
   * @param int $nid
   *   The node id.
   * @return bool
   *   Return TRUE if favorite.
   */
  public function isFavorite($uid, $nid);

  /**
   * Додаємо новий запис в таблиц.
   *
   * @param int $uid
   *    Current user id.
   * @param int $nid
   *    The node id.
   * @return mixed
   */
  public function setFavorite($uid, $nid);

  /**
   * Видвляємо запис з таблиці
   *
   * @param int $uid
   *    Current user id.
   * @param int $nid
   *    The node id.
   * @return mixed
   */
  public function deleteFavorite($uid, $nid);
}