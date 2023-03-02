<?php

namespace Drupal\backstop_js;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a backstop report entity type.
 */
interface BackstopReportInterface extends ConfigEntityInterface {

  /**
   * Generates a backstop file from configuration settings.
   *
   * @return mixed
   */
  public function generateBackstopFile();
}
