<?php

namespace Drupal\aluminum_blocks\Plugin\Block;

/**
 * Provides a 'Follow links' block.
 *
 * @Block(
 *     id = "aluminum_follow",
 *     admin_label = @Translation("Follow links"),
 * )
 */
class AluminumFollowBlock extends AluminumBlockBase {

  /**
   * {@inheritdoc}
   */
  public function getOptions() {
    $options = [];

    $options['link_target'] = [
      '#type' => 'select',
      '#title' => $this->t('Link target'),
      '#description' => $this->t('select the browser target for the follow links.'),
      '#options' => [
        '_blank' => 'New window',
        '_self' => 'Same window',
        '_parent' => 'Parent frame',
        '_top' => 'Top frame',
      ],
      '#default_value' => '_blank',
    ];

    $weight = 10;

    foreach (aluminum_vault_social_networks() as $id => $name) {
      $options[$id . '_enabled'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('%name enabled', ['%name' => $name]),
        '#description' => $this->t('%name will be shown if this box is checked.', ['%name' => $name]),
        '#default_value' => TRUE,
      ];

      $options[$id . '_weight'] = [
        '#type' => 'textfield',
        '#title' => $this->t('%name weight', ['%name' => $name]),
        '#description' => $this->t('This integer defines the weight of %name in relation to other links.', ['%name' => $name]),
        '#default_value' => $weight,
      ];

      $weight += 10;
    }

    return $options;
  }

  /**
   * Icon class.
   *
   * @param string $id
   *   The id.
   *
   * @return mixed|string
   *   The icon class.
   */
  protected function iconClass($id) {
    $config = aluminum_vault_config();

    $class = $config[$id][$id . '_icon_class'];

    if (!empty($config['general']['base_icon_class'])) {
      $class = $config['general']['base_icon_class'] . ' ' . $class;
    }

    return $class;
  }

  /**
   * Get social networks.
   *
   * @return array
   *   An array of social networks, keyed by id.
   */
  protected function getSocialNetworks() {
    $config = aluminum_vault_config();

    $socialNetworks = aluminum_vault_social_networks();

    $networks = [];

    foreach ($socialNetworks as $id => $name) {
      if ($this->getOptionValue($id . '_enabled')) {
        $networks[$id] = [
          'name' => $name,
          'weight' => $this->getOptionValue($id . '_weight'),
          'url' => $config[$id][$id . '_page_url'],
          'icon_class' => $this->iconClass($id),
        ];
      }
    }

    usort($networks, function ($a, $b) {
      if ($a['weight'] == $b['weight']) {
        return 0;
      }

      return ($a['weight'] < $b['weight']) ? -1 : 1;
    });

    return $networks;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'aluminum_follow_list',
      '#list' => $this->getSocialNetworks(),
      '#link_target' => $this->getOptionValue('link_target', '_blank'),
    ];
  }

}
