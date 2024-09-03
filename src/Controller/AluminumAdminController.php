<?php

namespace Drupal\aluminum\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides a controller for managing Aluminum.
 */
class AluminumAdminController extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  public function content() {
    $build = [
      '#markup' => '<p>' . $this->t('This is the Aluminum configuration section. Use the child pages of this section to configure Aluminum.') . '</p>',
      '#allowed_tags' => ['p'],
    ];

    return $build;
  }
}
