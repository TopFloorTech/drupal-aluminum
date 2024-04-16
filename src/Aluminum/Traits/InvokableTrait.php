<?php

namespace Drupal\aluminum\Aluminum\Traits;

/**
 * Provides an invokable trait.
 */
trait InvokableTrait {

  /**
   * The hook data.
   *
   * @var array
   */
  protected $hookData = [];

  /**
   * Invoke hook.
   *
   * @param string $hookName
   *   The hook name.
   * @param array $defaultItem
   *   The default item.
   *
   * @return array|mixed
   *   Whatever this returns.
   */
  protected function invokeHook($hookName, array $defaultItem = []) {
    if (!isset($this->hookData[$hookName])) {
      $moduleHandler = \Drupal::moduleHandler();

      $data = $moduleHandler->invokeAll($hookName);
      $moduleHandler->alter($hookName, $data);

      $this->hookData[$hookName] = $data;
    }

    $data = $this->hookData[$hookName];

    if (!empty($defaultItem)) {
      foreach ($data as $id => $value) {
        $data[$id] = $value + $defaultItem;
      }
    }

    return $data;
  }

}
