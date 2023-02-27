<?php

namespace Drupal\backstop_report\Form;

use Drupal\Core\Config\Entity\ConfigEntityStorage;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Backstop Report form.
 *
 * @property \Drupal\backstop_report\BackstopReportInterface $entity
 */
class BackstopReportForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the backstop report.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\backstop_report\Entity\BackstopReport::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

//    $form['status'] = [
//      '#type' => 'checkbox',
//      '#title' => $this->t('Enabled'),
//      '#default_value' => $this->entity->status(),
//    ];
//
//    $form['description'] = [
//      '#type' => 'textarea',
//      '#title' => $this->t('Description'),
//      '#default_value' => $this->entity->get('description'),
//      '#description' => $this->t('Description of the backstop report.'),
//    ];

    $form['viewports'] = [
      '#type' => 'checkboxes',
      '#title' => t('Viewports'),
      '#descriptions' => t('Select the viewports to include in this report'),
      '#description_display' => 'before',
      '#options' => $this->getConfig('backstop_viewport'),
      '#default_value' => $this->entity->get('viewports') ?? [],
    ];
    $form['onBeforeScript'] = [
      '#type' => 'textfield',
      '#title' => t('onBeforeScript'),
      '#description' => t('TODO: Need description here'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('onBeforeScript') ?? 'puppet/onBefore.js',
    ];
    $form['scenarios'] = [
      '#type' => 'checkboxes',
      '#title' => t('Scenarios'),
      '#description' => t('Select the scenarios to include in this report.'),
      '#description_display' => 'before',
      '#options' => $this->getConfig('backstop_scenario'),
      '#default_value' => $this->entity->get('scenarios') ?? [],
    ];
    $form['paths'] = [
      '#type' => 'textarea',
      '#title' => t('Paths'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('paths') ?? $this->pathsDefault(),
    ];
    $form['report'] = [
      '#type' => 'textfield',
      '#title' => t('Report'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('report') ?? "browser",
    ];
    $form['engine'] = [
      '#type' => 'textfield',
      '#title' => t('Engine'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('engine') ?? 'puppeteer',
    ];
    $form['engineOptions'] = [
      '#type' => 'textfield',
      '#title' => t('engineOptions'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('engineOptions') ?? '--no-sandbox',
    ];
    $form['asyncCaptureLimit'] = [
      '#type' => 'number',
      '#title' => t('asyncCaptureLimit'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('asyncCaptureLimit') ?? 5,
    ];
    $form['asyncCompareLimit'] = [
      '#type' => 'number',
      '#title' => t('asyncCompareLimit'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('asyncCompareLimit') ?? 50,
    ];
    $form['debug'] = [
      '#type' => 'checkbox',
      '#title' => t('debug'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('debug') ?? 0,
    ];
    $form['debugWindow'] = [
      '#type' => 'checkbox',
      '#title' => t('debugWindow'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('debugWindow') ?? 0,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new backstop report %label.', $message_args)
      : $this->t('Updated backstop report %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    $this->generateBackstopFile();
    return $result;
  }

  private function getConfig(string $config_name) {
    // Get the config entity manager.
    $entity_storage = \Drupal::service('entity_type.manager')
      ->getStorage($config_name);
    // Get the entity query object.
    $entity_query = $entity_storage->getQuery();
    // Get the viewport configs.
    $config_ids = $entity_query->execute();
    $configs = $entity_storage->loadMultiple($config_ids);

    // Create the array of configs.
    $config_list = [];
    foreach ($configs as $config) {
      $config_list[$config->id()] = $config->label();
//      dpm($entity_storage->load($config->id()));
    }
    return $config_list;
  }

  private function pathsDefault() {
    $paths_default = "bitmaps_reference|backstop_data/bitmaps_reference\n";
    $paths_default .= "bitmaps_test|backstop_data/bitmaps_test\n";
    $paths_default .= "html_report|backstop_data/html_report\n";
    $paths_default .= "ci_report|backstop_data/ci_report";
    return $paths_default;
  }

  public function generateBackstopFile() {
    $backstop = new \stdClass();
    $viewport_entities = $this->getConfigEntities('backstop_viewport');
    $scenario_entities = $this->getConfigEntities('backstop_scenario');

    $backstop->id = $this->entity->label();

    // Create the viewports array.
    $viewports = [];
    foreach ($this->entity->get('viewports') as $key => $id) {
      if ($id === 0) {
        continue;
      }
      $entity = $viewport_entities->load($id);
      $viewport = new \stdClass();
      $viewport->label = $id;
      $viewport->width = $entity->get('width');
      $viewport->height = $entity->get('height');
      $viewports[] = $viewport;
    }
    $backstop->viewports = $viewports;

    $backstop->onBeforeScript = $this->entity->get('onBeforeScript');

    // Create the scenarios array.
    $scenarios = [];
    foreach ($this->entity->get('scenarios') as $key => $id) {
      if ($id === 0) {
        continue;
      }
      $entity = $scenario_entities->load($id);
      $scenario = new \stdClass();
      $scenario->label = $entity->label();
      $scenario->url = $entity->get('url');
      $scenario->cookiePath = $entity->get('cookiePath');
      $scenario->referenceUrl = $entity->get('referenceUrl');
      $scenario->delay = $entity->get('delay');
      $scenario->hideSelectors = explode(',', $entity->get('hideSelectors'));
      $scenario->removeSelectors = explode(',', $entity->get('removeSelectors'));
      $scenarios[] = $scenario;
    }
    $backstop->scenarios = $scenarios;

    // Create the paths object.
    $paths_array = explode(PHP_EOL, $this->entity->get('paths'));
    $paths = new \stdClass();
    foreach ($paths_array as $path) {
      preg_match('/(\w+)\|([\w\/]+)/', $path, $path_parts);
      $paths->{$path_parts[1]} = $path_parts[2];
    }
    $backstop->paths = $paths;

    $backstop->report = explode(',', $this->entity->get('report'));
    $backstop->engine = $this->entity->get('engine');

    $engineOptions = new \stdClass();
    $engineOptions->args = explode(',', $this->entity->get('engineOptions'));
    $backstop->engineOptions = $engineOptions;

    $backstop->asyncCaptureLimit = $this->entity->get('asyncCaptureLimit');
    $backstop->asyncCompareLimit = $this->entity->get('asyncCompareLimit');
    $backstop->debug = $this->entity->get('debug');
    $backstop->debugWindow = $this->entity->get('debugWindow');

    // Create the backstop.json file.
    $backstop_file = fopen("/var/www/tests/backstop/backstop_{$this->entity->id()}.json", "w");
    fwrite($backstop_file, json_encode($backstop, JSON_PRETTY_PRINT));
    fclose($backstop_file);
  }

  private function getConfigEntities($config_name) {
    // Get the config entity manager.
    return \Drupal::service('entity_type.manager')
      ->getStorage($config_name);

  }
}
