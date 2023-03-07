<?php

namespace Drupal\backstop_js\Form;

use Drupal\Core\Config\Entity\ConfigEntityStorage;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Backstop Report form.
 *
 * @property \Drupal\backstop_js\BackstopReportInterface $entity
 */
class BackstopReportForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $scenario_url = Url::fromRoute('entity.backstop_scenario.add_form');
    $scenario_link = Link::fromTextAndUrl($this->t('Add a Scenario'), $scenario_url);
    $viewport_url = Url::fromRoute('entity.backstop_viewport.add_form');
    $viewport_link = Link::fromTextAndUrl($this->t('Add a Viewport'), $viewport_url);
    $backstop_config = \Drupal::config('backstop_js.settings');

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
        'exists' => '\Drupal\backstop_js\Entity\BackstopReport::load',
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
      '#description' => t('Select the viewports to include in this report'),
      '#description_display' => 'before',
      '#options' => $this->getConfig('backstop_viewport'),
      '#default_value' => $this->entity->get('viewports') ?? [],
      '#suffix' => $viewport_link->toString()->getGeneratedLink(),
    ];
    $form['scenarios'] = [
      '#type' => 'checkboxes',
      '#title' => t('Scenarios'),
      '#description' => t('Select the scenarios to include in this report.'),
      '#description_display' => 'before',
      '#options' => $this->getConfig('backstop_scenario'),
      '#default_value' => ($this->entity->get('scenarios')) ?? [],
      '#suffix' => $scenario_link->toString()->getGeneratedLink(),
    ];
    $form['use_globals'] = [
      '#type' => 'checkbox',
      '#title' => t('Use global settings'),
      '#default_value' => $this->entity->get('use_globals') ?? TRUE,
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
      '#default_value' => $this->entity->get('use_defaults') ? $backstop_config->get('onBeforeScript') : $this->entity->get('onBeforeScript'), //'puppet/onBefore.js',
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['paths'] = [
      '#type' => 'textarea',
      '#title' => t('Paths'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('paths') ?? $backstop_config->get('paths'),
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['report'] = [
      '#type' => 'textfield',
      '#title' => t('Report'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('report') ?? $backstop_config->get('report'),
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['engine'] = [
      '#type' => 'textfield',
      '#title' => t('Engine'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('engine') ?? $backstop_config->get('engine'),
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['engineOptions'] = [
      '#type' => 'textfield',
      '#title' => t('engineOptions'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('engineOptions') ?? $backstop_config->get('engineOptions'),
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['asyncCaptureLimit'] = [
      '#type' => 'number',
      '#title' => t('asyncCaptureLimit'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('asyncCaptureLimit') ?? $backstop_config->get('asyncCaptureLimit'),
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['asyncCompareLimit'] = [
      '#type' => 'number',
      '#title' => t('asyncCompareLimit'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('asyncCompareLimit') ?? $backstop_config->get('asyncCompareLimit'),
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['debug'] = [
      '#type' => 'checkbox',
      '#title' => t('debug'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('debug') ?? $backstop_config->get('debug'),
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
    ];
    $form['advanced_settings']['debugWindow'] = [
      '#type' => 'checkbox',
      '#title' => t('debugWindow'),
      '#description' => t('TODO: Need description here.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->get('debugWindow') ?? $backstop_config->get('debugWindow'),
      '#attributes' => [
        'disabled' => $this->entity->get('use_defaults') ? 'disabled' : NULL,
        'class' => ['advanced-setting'],
      ],
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
    $this->entity->generateBackstopFile();
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
      if ($config_name == 'backstop_scenario') {
        $config_list[$config->id()] = ucfirst($config->get('bundle')) . ": {$config->label()}";
        continue;
      }
      $config_list[$config->id()] = $config->label();
    }
    asort($config_list);
    return $config_list;
  }

  private function pathsDefault() {
    $paths_default = "bitmaps_reference|backstop_data/bitmaps_reference\n";
    $paths_default .= "bitmaps_test|backstop_data/bitmaps_test\n";
    $paths_default .= "html_report|backstop_data/html_report\n";
    $paths_default .= "ci_report|backstop_data/ci_report";
    return $paths_default;
  }

}
