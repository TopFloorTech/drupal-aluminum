<?php

namespace Drupal\aluminum_blocks\Plugin\Block;

/**
 * Provides a 'Phone number list' block.
 *
 * @Block(
 *     id = "aluminum_phone_number_list",
 *     admin_label = @Translation("Phone number list"),
 * )
 */
class AluminumPhoneNumberListBlock extends AluminumBlockBase {

  /**
   * Get phone number options.
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
    $options = [];

    $options['enabled_phone_numbers'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Enabled phone numbers'),
      '#description' => $this->t('Check which phone numbers to show in the list'),
      '#options' => $this->getPhoneNumberOptions(),
      '#default_value' => array_keys($this->getPhoneNumberOptions()),
    ];

    $options['display_short_titles'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display short titles'),
      '#description' => $this->t('Check to use short abbreviations in the list instead of long titles.'),
      '#default_value' => FALSE,
    ];

    return $options;
  }

  /**
   * Get list.
   *
   * @return array
   *   An array of list items.
   */
  protected function getList() {
    $enabled = array_keys(array_filter($this->getOptionValue('enabled_phone_numbers')));
    $phone_numbers = aluminum_vault_phone_numbers();
    $display_short_titles = $this->getOptionValue('display_short_titles');

    $list = [];

    foreach ($enabled as $type) {
      $key = $display_short_titles ? 'short_title' : 'title';
      $title = $phone_numbers[$type][$key];

      $phone_number = aluminum_vault_config('contact_info.' . $type);

      $list[] = [
        'title' => $title,
        'phone_number' => $phone_number,
        'url' => 'tel:+1 ' . $phone_number,
      ];
    }

    return $list;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'aluminum_phone_number_list',
      '#list' => $this->getList(),
    ];
  }

}
