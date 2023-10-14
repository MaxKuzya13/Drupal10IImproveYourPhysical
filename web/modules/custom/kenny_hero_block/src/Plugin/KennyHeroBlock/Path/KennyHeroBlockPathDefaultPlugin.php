<?php

namespace Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path;

/**
 * Default plugin which will be used if none of others met their requirements.
 *
 * @KennyHeroBlockPath(
 *   id = "kenny_hero_block_path_default",
 *   match_path = {"/training"},
 *   weight = -100,
 * )
 */
class KennyHeroBlockPathDefaultPlugin extends KennyHeroBlockPathPluginBase {

}
