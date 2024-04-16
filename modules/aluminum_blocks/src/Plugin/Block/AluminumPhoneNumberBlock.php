<?php

namespace Drupal\aluminum_blocks\Plugin\Block;

use Drupal\Core\Url;

/**
 * Provides a 'Phone number' block.
 *
 * @Block(
 *     id = "aluminum_phone_number",
 *     admin_label = @Translation("Phone number"),
 * )
 */
class AluminumPhoneNumberBlock extends AluminumBlockBase {

  /**
   * Get the phone number options.
   *
   * @return array
   *   An array of phone number options.
   */
  protected function getPhoneNumberOptions() {
    $options = [];

    foreach (aluminum_vault_phone_numbers() as $phone_number_type => $info) {
      $options[$phone_number_type] = $info['title'];
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions() {
    return [
      'phone_number_type' => [
        '#type' => 'select',
        '#title' => $this->t('Phone number type'),
        '#description' => $this->t('Choose which phone number type to display.'),
        '#options' => $this->getPhoneNumberOptions(),
        '#default_value' => 'phone_number',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $type = $this->getOptionValue('phone_number_type');
    $phone_number = aluminum_vault_config('contact_info.' . $type);
    $url = Url::fromUri('tel:+1 ' . $phone_number);

    return [
      '#theme' => 'aluminum_phone_number',
      '#phone_number' => $phone_number,
      '#url' => $url,
    ];
  }

}
