<?php

namespace Drupal\aluminum\Aluminum\Traits;

/**
 * Provides a static invokable trait.
 */
trait StaticInvokableTrait {

  /**
   * The hook data.
   *
   * @var array
   */
  protected static $hookData = [];

  /**
   * Invoke hook.
   *
   * @param string $hookName
   *   The hook name.
   * @param array $defaultItem
   *   The default item.
   *
   * @return array|mixed
   *   Some data.
   */
  protected static function invokeHook(string $hookName, array $defaultItem = []) {
    if (!isset(self::$hookData[$hookName])) {
      $moduleHandler = \Drupal::moduleHandler();

      $data = $moduleHandler->invokeAll($hookName);
      $moduleHandler->alter($hookName, $data);

      self::$hookData[$hookName] = $data;
    }

    $data = self::$hookData[$hookName];

    if (!empty($defaultItem)) {
      foreach ($data as $id => $value) {
        $data[$id] = $value + $defaultItem;
      }
    }

    return $data;
  }

}
