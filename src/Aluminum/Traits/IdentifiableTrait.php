<?php

namespace Drupal\aluminum\Aluminum\Traits;

use Drupal\aluminum\Aluminum\Exception\AluminumException;

/**
 * Provides an identifiable trait.
 */
trait IdentifiableTrait {

  /**
   * Get id.
   *
   * @return mixed
   *   If there is an id, it is returned.
   *
   * @throws \Drupal\aluminum\Aluminum\Exception\AluminumException
   */
  public function getId() {
    if (!isset($this->id)) {
      throw new AluminumException("No ID set for this object.");
    }

    return $this->id;
  }

  /**
   * Get name.
   *
   * @param bool $translate
   *   Whether to translate name.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup|string
   *   If translated, return the translated name, else, string name.
   *
   * @throws \Drupal\aluminum\Aluminum\Exception\AluminumException
   */
  public function getName(bool $translate = TRUE) {
    $name = (!empty($this->name)) ? $this->name : $this->generateName();

    if ($translate) {
      if (method_exists($this, 't')) {
        $name = $this->t('%name', ['%name' => $name]);
      }
      else {
        $name = t('%name', ['%name' => $name]);
      }
    }

    return $name;
  }

  /**
   * Generate name.
   *
   * @return string
   *   The name.
   *
   * @throws \Drupal\aluminum\Aluminum\Exception\AluminumException
   */
  public function generateName() {
    $id = $this->getId();

    if (!$id) {
      throw new AluminumException("No ID, can't generate name.");
    }

    $id = preg_replace('/[_-]+/', ' ', $id);
    $id = ucfirst($id);

    return $id;
  }

}
