<?php

namespace Drupal\backstop_generator\Form;

use Drupal\backstop_generator\Entity\BackstopReport;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Serialization\Yaml;

/**
 * Configure BackstopJS settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'backstop_generator_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['backstop_generator.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('backstop_generator.settings');
    $defaults = \Drupal::config('backstop_generator.settings');

    $form['backstop_directory'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Backstop Directory'),
      '#description' => t('This directory is in the <em>project</em> root, one level above your Drupal site.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('backstop_directory'), // $this->config('backstop_generator.settings')->get('backstop_directory'),
      '#attributes' => [
        'disabled' => 'disabled',
      ]
    ];

    $form['debugging'] = [
      '#type' => 'details',
      '#title' => t('Debugging'),
      '#open' => FALSE,
    ];
    $form['debugging']['debug'] = [
      '#type' => 'checkbox',
      '#title' => t('debug'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('debug') ?? FALSE,
    ];
    $form['debugging']['debugWindow'] = [
      '#type' => 'checkbox',
      '#title' => t('debugWindow'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $config->get('debugWindow') ?? FALSE,
    ];

    $form['advanced_settings'] = [
      '#type' => 'details',
      '#title' => t('Advanced Settings'),
      '#open' => FALSE,
    ];
    $form['advanced_settings']['use_defaults'] = [
      '#type' => 'checkbox',
      '#title' => t('Use Backstop defaults'),
      '#description' => t('Checking this box will populate the Advanced Settings fields with module defaults. The values will not be saved until you click the Save Configuration button below.'),
      '#default_value' => $config->get('use_defaults'),
      '#ajax' => [
        'callback' => '::populateDefaults',
        'event' => 'change',
      ],
    ];
    $form['advanced_settings']['onBeforeScript'] = [
      '#type' => 'textfield',
      '#title' => t('onBeforeScript'),
      '#description' => t('TODO: Need description here'),
      '#description_display' => 'before',
      '#default_value' => $config->get('use_defaults') ? $defaults->get('onBeforeScript') : $config->get('onBeforeScript'),
      '#attributes' => [
        'readonly' => $config->get('use_defaults') ?? TRUE,
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
        'readonly' => $config->get('use_defaults') ?? TRUE,
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
        'readonly' => $config->get('use_defaults') ?? TRUE,
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
        'readonly' => $config->get('use_defaults') ?? TRUE,
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
        'readonly' => $config->get('use_defaults') ?? TRUE,
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
        'readonly' => $config->get('use_defaults') ?? TRUE,
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
        'readonly' => $config->get('use_defaults') ?? TRUE,
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
    $current_dir = $this->config('backstop_generator.settings')
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

    $this->config('backstop_generator.settings')
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

    // Update the backstop.json files for reports that use global settings.
    $updated_reports = $this->updateReports();
    // Inform the user which backstop.json files have been updated.
    $message = count($updated_reports) > 1 ?
      t('The %label backstop.json files have been updated.', ['%label' => implode(', ', $updated_reports), ]) :
      t('The %label backstop.json file has been updated.', ['%label' => implode(', ', $updated_reports), ]);
    $this->messenger->addMessage($message);
  }

  /**
   * Repopulate the advanced settings fields with defaults provided from the
   * module's config/install/backstop_generator.settings.defaults.yml file.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function populateDefaults(array $form, FormStateInterface $formState) {
    $response = new AjaxResponse();
    if ($formState->getValue('use_defaults') === 0) {
      return $response;
    }

    $module_path = \Drupal::moduleHandler()->getModule('backstop_generator')->getPath();
    // Get the module default values.
    $file_path = DRUPAL_ROOT . '/' . $module_path . '/config/install/backstop_generator.settings.defaults.yml';
    $settings_file = file_get_contents($file_path);
    $settings_yml = Yaml::decode($settings_file);

    // Reset the Advanced Settings fields with module defaults.
    $response->addCommand(new InvokeCommand('#edit-onbeforescript', 'val', [$settings_yml['onBeforeScript']]));
    $response->addCommand(new InvokeCommand('#edit-paths', 'val', [$settings_yml['paths']]));
    $response->addCommand(new InvokeCommand('#edit-report', 'val', [$settings_yml['report']]));
    $response->addCommand(new InvokeCommand('#edit-engine', 'val', [$settings_yml['engine']]));
    $response->addCommand(new InvokeCommand('#edit-engineoptions', 'val', [$settings_yml['engineOptions']]));
    $response->addCommand(new InvokeCommand('#edit-asynccapturelimit', 'val', [$settings_yml['asyncCaptureLimit']]));
    $response->addCommand(new InvokeCommand('#edit-asynccomparelimit', 'val', [$settings_yml['asyncCompareLimit']]));
    $response->addCommand(new InvokeCommand('#edit-debug', 'val', [$settings_yml['debug']]));
    $response->addCommand(new InvokeCommand('#edit-debug', 'removeAttr', ['checked']));

    return $response;
  }

  /**
   * Regenerate the backstop.json files for reports that use global settings.
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function updateReports() {
    $report_ids = \Drupal::entityTypeManager()
      ->getStorage('backstop_report')
      ->getQuery()
      ->execute();
    $settings = $this->config('backstop_generator.settings');
    $regenerated_reports = [];

    if (!empty($report_ids)) {
      foreach ($report_ids as $id) {
        // Load the report config.
        $report_config = \Drupal::configFactory()->getEditable("backstop_generator.report.$id");

        // Ignore reports using custom advanced settings.
        if (!$report_config->get('use_globals')) {
          continue;
        }

        // Update the config values and save.
        $report_config->set('onBeforeScript', $settings->get('onBeforeScript'))
          ->set('paths', $settings->get('paths'))
          ->set('report', $settings->get('report'))
          ->set('engine', $settings->get('engine'))
          ->set('engineOptions', $settings->get('engineOptions'))
          ->set('asyncCaptureLimit', $settings->get('asyncCaptureLimit'))
          ->set('asyncCompareLimit', $settings->get('asyncCompareLimit'));
        $report_config->save();

        // Regenerate the backstop.json file.
        $report = BackstopReport::load($id);
        $report->generateBackstopFile($id);
        $regenerated_reports[] = $report->label();
      }
    }
    return $regenerated_reports;
  }

}
