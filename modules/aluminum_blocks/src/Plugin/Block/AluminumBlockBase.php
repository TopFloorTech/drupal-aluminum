<?php

namespace Drupal\aluminum_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base class for AluminumBlocks.
 *
 * Created by PhpStorm.
 * User: BMcClure.
 * Date: 9/9/2016.
 * Time: 2:44 PM.
 */
abstract class AluminumBlockBase extends BlockBase implements BlockPluginInterface {

  /**
   * Optionally override this to manually set an aluminum_id for this block.
   *
   * @var string
   */
  protected $aluminumId = '';

  /**
   * Override to specify configuration options.
   *
   * @return array
   *   An array of options.
   */
  public function getOptions() {
    return [];
  }

  /**
   * Get the aluminum id.
   *
   * @return string
   *   The aluminum id.
   */
  public function getAluminumId(): string {
    if (!empty($this->aluminumId)) {
      return $this->aluminumId;
    }

    return strtolower(preg_replace([
      '/([a-z\d])([A-Z])/',
      '/([^_])([A-Z][a-z])/',
    ], '$1_$2', self::class));
  }

  /**
   * Gets the current value for an option returned by getOptions()
   *
   * @param string $option_name
   *   The option name.
   * @param bool $replace_tokens
   *   Whether or not to replace tokens.
   *
   * @return string
   *   The option value.
   */
  public function getOptionValue(string $option_name, bool $replace_tokens = FALSE): string {
    $config = $this->getConfiguration();

    $options = $this->getOptions();

    $default = $options[$option_name]['#default_value'] ?? '';

    $value = $config[$option_name] ?? $default;

    if ($replace_tokens) {
      $value = \Drupal::token()->replace($value);
    }

    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    foreach ($this->getOptions() as $option_name => $option) {
      $option += [
        '#type' => 'textfield',
        '#title' => ucfirst(str_replace('_', ' ', $option_name)),
      ];

      $default = $option['#default_value'] ?? '';

      $option['#default_value'] = $config[$option_name] ?? $default;

      $form[$option_name] = $option;
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    foreach ($this->getOptions() as $option_name => $option) {
      $this->setConfigurationValue($option_name, $form_state->getValue($option_name));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('aluminum_blocks.settings');

    $values = [];

    foreach ($this->getOptions() as $option_name => $option) {
      $values[$option_name] = $default_config->get($this->getAluminumId() . '.' . $option_name);
    }

    return $values;
  }

}
