<?php

namespace Drupal\aluminum\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides an Aluminum admin controller.
 */
class AluminumAdminController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $build = [
      '#type' => 'markup',
      '#markup' => '<p>' . $this->t('This is the Aluminum configuration section. Use the child pages of this section to configure Aluminum.') . '</p>',
    ];

    return $build;
  }

}
