<?php

namespace Drupal\aluminum_vault\Form;

use Drupal\aluminum_vault\VaultConfig;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for Aluminum.
 */
class AluminumVaultSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'aluminum_vault_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['aluminum_vault.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $vault_config = new VaultConfig();

    $form['aluminum_vault'] = [
      '#type' => 'vertical_tabs',
      '#title' => 'Aluminum vault',
    ];

    foreach ($vault_config->getVaultGroups() as $group_id => $group) {
      $form['aluminum_vault'][$group_id] = $group;
    }

    foreach ($vault_config->getVaultConfig() as $option_id => $option) {
      $option['#default_value'] = $vault_config->getOptionValue($option, $form_state);

      $form['aluminum_vault'][$option['#vault_group']][$option_id] = $option;
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('aluminum_vault.settings');
    $vault_config = new VaultConfig();
    $new_config = [];

    foreach ($vault_config->getVaultGroups() as $group_id => $group) {
      foreach ($vault_config->getVaultConfig() as $option_id => $option) {
        if ($option['#vault_group'] != $group_id) {
          continue;
        }

        $new_config[$group_id][$option_id] = $form_state->getValue($option_id);
      }
    }

    $config->set('aluminum_vault', $new_config);
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
