<?php

namespace Drupal\backstop_js\Form;

use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure BackstopJS settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'backstop_js_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['backstop_js.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('backstop_js.settings');
    $defaults = \Drupal::config('backstop_js.settings');

    $form['backstop_directory'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Backstop Directory'),
      '#description' => t('This directory is in the <em>project</em> root, one level above your Drupal site.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('backstop_directory'), // $this->config('backstop_js.settings')->get('backstop_directory'),
      '#attributes' => [
        'disabled' => 'disabled',
      ]
    ];
    $form['use_defaults'] = [
      '#type' => 'checkbox',
      '#title' => t('Use Backstop defaults'),
      '#default_value' => $config->get('use_defaults'),
    ];
    $form['advanced_settings'] = [
      '#type' => 'details',
      '#title' => t('Advanced Settings'),
      '#open' => FALSE,
    ];
    $form['advanced_settings']['onBeforeScript'] = [
      '#type' => 'textfield',
      '#title' => t('onBeforeScript'),
      '#description' => t('TODO: Need description here'),
      '#description_display' => 'before',
      '#default_value' => $config->get('use_defaults') ? $defaults->get('onBeforeScript') : $config->get('onBeforeScript'),
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['paths'] = [
      '#type' => 'textarea',
      '#title' => t('Paths'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('paths'),
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['report'] = [
      '#type' => 'textfield',
      '#title' => t('Report'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('report') ?? 'browser',
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['engine'] = [
      '#type' => 'textfield',
      '#title' => t('Engine'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('engine') ?? 'puppeteer',
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['engineOptions'] = [
      '#type' => 'textfield',
      '#title' => t('engineOptions'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('engineOptions') ?? '--no-sandbox',
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['asyncCaptureLimit'] = [
      '#type' => 'number',
      '#title' => t('asyncCaptureLimit'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('asyncCaptureLimit') ?? 5,
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['asyncCompareLimit'] = [
      '#type' => 'number',
      '#title' => t('asyncCompareLimit'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('asyncCompareLimit') ?? 50,
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['debug'] = [
      '#type' => 'checkbox',
      '#title' => t('debug'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('debug') ?? FALSE,
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['debugWindow'] = [
      '#type' => 'checkbox',
      '#title' => t('debugWindow'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('debugWindow') ?? FALSE,
      '#attributes' => [
        'disabled' => $config->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\Core\File\FileSystemInterface $file_system */
    $file_system = \Drupal::service('file_system');

    // If a new directory is created, delete the old directory.
    $current_dir = $this->config('backstop_js.settings')
      ->get('backstop_directory');
    $new_dir = $form_state->getValue('backstop_directory');
    if ($current_dir && $current_dir != $new_dir) {
      // Delete the directory created by the old value.

      // Create the backstop directory where reports will be saved.
      $project_root = dirname(DRUPAL_ROOT);
      $report_directory = "$project_root/{$form_state->getValue('backstop_directory')}";
      $file_system->prepareDirectory(
        $report_directory,
        FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS
      );

    }

    $this->config('backstop_js.settings')
      ->set('use_defaults', $form_state->getValue('use_defaults'))
      ->set('backstop_directory', $form_state->getValue('backstop_directory'))
      ->set('onBeforeScript', $form_state->getValue('onBeforeScript'))
      ->set('paths', $form_state->getValue('paths'))
      ->set('report', $form_state->getValue('report'))
      ->set('engine', $form_state->getValue('engine'))
      ->set('engineOptions', $form_state->getValue('engineOptions'))
      ->set('asyncCaptureLimit', $form_state->getValue('asyncCaptureLimit'))
      ->set('asyncCompareLimit', $form_state->getValue('asyncCompareLimit'))
      ->set('debug', $form_state->getValue('debug'))
      ->set('debugWindow', $form_state->getValue('debugWindow'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
