<?php

namespace Drupal\kenny_settings\Plugin\views\relationship;

use Drupal\views\Plugin\views\relationship\Standard;

/**
 * Provides a relationship for the kenny_favorite_training table.
 *
 * @ViewsRelationship("kenny_favorite_training")
 */
class KennyFavoriteTrainingRelationships extends Standard {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Опишіть SQL-запит для вашої таблиці тут.
    $this->table = 'kenny_favorite_training'; // Замініть це на правильну назву вашої таблиці.
    $this->field = 'nid'; // Замініть це на поле, за яким ви хочете здійснювати зв'язок.
    $this->left_table = 'node'; // Замініть це на таблицю, з якою ви хочете зв'язувати вашу таблицю.

    parent::query();
  }
}