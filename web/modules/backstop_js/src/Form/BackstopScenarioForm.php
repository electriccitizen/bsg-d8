<?php

namespace Drupal\backstop_js\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Backstop Scenario form.
 *
 * @property \Drupal\backstop_scenario\BackstopScenarioInterface $entity
 */
class BackstopScenarioForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
/*
 * label, url, referenceUrl, delay, hideSelectors, removeSelectors, misMatchThreshold, requireSameDimensions
 */
    $form = parent::form($form, $form_state);
    $server = $_SERVER;
    $site = "{$server['REQUEST_SCHEME']}://{$server['SERVER_NAME']}";

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('The title (or an easy reference name) of the page you want to test. This value is arbitrary.'),
      '#description_display' => 'before',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => 'Page Title',
      ]
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\backstop_js\Entity\BackstopScenario::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL'),
      '#default_value' => $this->entity->get('url') ?? $site,
      '#description' => $this->t('The URL of the page you want to test in this scenario.'),
      '#description_display' => 'before',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => 'http://dev-site.com/node/[nid]',
      ],
    ];
    $form['referenceUrl'] = [
      '#type' => 'textfield',
      '#title' => $this->t('referenceUrl'),
      '#default_value' => $this->entity->get('referenceUrl'),
      '#description' => $this->t('The URL of the page to use as a test reference (source of truth).'),
      '#description_display' => 'before',
      '#attributes' => [
        'placeholder' => 'http://prod-site.com/node/[nid]',
      ],
    ];

//    $form['status'] = [
//      '#type' => 'checkbox',
//      '#title' => $this->t('Enabled'),
//      '#default_value' => $this->entity->status(),
//    ];

//    $form['description'] = [
//      '#type' => 'textarea',
//      '#title' => $this->t('Description'),
//      '#default_value' => $this->entity->get('description'),
//      '#description' => $this->t('Description of the backstop scenario.'),
//      '#rows' => 2,
//    ];
//
    $form['basic'] = [
      '#type' => 'details',
      '#description' => t('Modify these defaults to create a basic scenario for your report.'),
      '#title' => t('Basic Settings'),
      '#open' => TRUE,
    ];

    $form['advanced'] = [
      '#type' => 'details',
      '#description' => t('Test more complex functionality in your report.'),
      '#title' => t('Advanced'),
      '#open' => FALSE,
      'dom_events' => [
        '#type' => 'details',
        '#title' => t('DOM Events'),
        '#description' => t('Modify the this scenarios based on DOM events.'),
        '#open' => FALSE,
      ],
      'user_behavior' => [
        '#type' => 'details',
        '#title' => t('User Behaviors'),
        '#description' => t('Test specific user behaviors.'),
        '#open' => FALSE,
      ],
    ];
    $form['basic']['delay'] = [
      '#type' => 'number',
      '#title' => $this->t('Delay'),
      '#default_value' => $this->entity->get('delay') ?? 3000,
      '#description' => $this->t('Wait for x milliseconds.'),
      '#field_suffix' => 'ms',
      '#description_display' => 'before',
    ];
    $form['basic']['hideSelectors'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hide Selectors'),
      '#default_value' => $this->entity->get('hideSelectors'),
      '#description' => $this->t('A comma-separated list of selectors you want to hide (visibility set to <em>hidden</em>).'),
      '#description_display' => 'before',
      '#attributes' => [
        'placeholder' => 'apple,banana,carrot',
      ],
    ];
    $form['basic']['removeSelectors'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Remove Selectors'),
      '#default_value' => $this->entity->get('removeSelectors'),
      '#description' => $this->t('A comma-separated list of selectors you want to remove (display set to <em>none</em>).'),
      '#description_display' => 'before',
      '#attributes' => [
        'placeholder' => 'coffee,donut,cinnamon-roll',
      ],
    ];
    $form['basic']['misMatchThreshold'] = [
      '#type' => 'number',
      '#title' => $this->t('Mismatch Threshold'),
      '#default_value' => $this->entity->get('misMatchThreshold') ?? 10,
      '#description' => $this->t('Percentage of different pixels allowed to pass test.'),
      '#description_display' => 'before',
      '#field_suffix' => '%',
    ];
    $form['basic']['requireSameDimensions'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Require same dimension'),
      '#default_value' => $this->entity->get('requireSameDimension'),
      '#description' => $this->t('Set to true, any change in selector size will trigger a test failure.'),
//      '#description_display' => 'before',
      '#return_value' => 1,
    ];

    $form['advanced']['cookiePath'] = [
      '#type' => 'textfield',
      '#title' => $this->t('cookiePath') ?? '/this/cookie/file/path',
      '#default_value' => $this->entity->get('cookiePath'),
      '#description' => $this->t('Import cookies in JSON format to get around the <em>Accept Cookies</em> screen.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['dom_events']['readyEvent'] = [
      '#type' => 'textfield',
      '#title' => $this->t('readyEvent'),
      '#default_value' => $this->entity->get('readyEvent'),
      '#description' => $this->t('Wait until this string has been logged to the console.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['dom_events']['readySelector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('readySelector'),
      '#default_value' => $this->entity->get('readySelector'),
      '#description' => $this->t('Wait until this selector exists before continuing.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['dom_events']['readyTimeout'] = [
      '#type' => 'number',
      '#title' => $this->t('readyTimeout'),
      '#default_value' => $this->entity->get('readyTimeout'),
      '#description' => $this->t('Timeout for readyEvent and readySelector.'),
      '#field_suffix' => 'ms',
      '#description_display' => 'before',
    ];

    $form['advanced']['dom_events']['onReadyScript'] = [
      '#type' => 'textfield',
      '#title' => $this->t('onReadyScript'),
      '#default_value' => $this->entity->get('onReadyScript'),
      '#description' => $this->t('Script to modify UI state prior to screen shots e.g. hovers, clicks etc.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['dom_events']['onBeforeScript'] = [
      '#type' => 'textfield',
      '#title' => $this->t('onBeforeScript'),
      '#default_value' => $this->entity->get('onBeforeScript'),
      '#description' => $this->t('Used to set up browser state e.g. cookies.'),
      '#description_display' => 'before',
    ];

//    $form['advanced']['user_behavior'] = [
//      '#type' => 'details',
//      '#title' => t('User Behaviors'),
//      '#description' => t('Test specific user behaviors.'),
//      '#open' => FALSE,
//    ];

    // todo: create a custom, multi-column field.
    $form['advanced']['user_behavior']['keyPressSelectors'] = [
      '#type' => 'textfield', //'bs_keypress_selector',
      '#title' => $this->t('keyPressSelectors'),
      '#default_value' => $this->entity->get('keyPressSelectors'),
      '#description' => $this->t('List of selectors to simulate multiple sequential keypress interactions.'),
      '#description_display' => 'before',
//      '#multiple' => TRUE,
      ];

    $form['advanced']['user_behavior']['hoverSelector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('hoverSelector'),
      '#default_value' => $this->entity->get('hoverSelector'),
      '#description' => $this->t('Move the pointer over the specified DOM element prior to screen shot.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['user_behavior']['hoverSelectors'] = [
      '#type' => 'textfield',
      '#title' => $this->t('hoverSelectors'),
      '#default_value' => $this->entity->get('hoverSelectors'),
      '#description' => $this->t('Selectors to simulate multiple sequential hover interactions.'),
      '#description_display' => 'before',
//      '#multiple' => TRUE,
    ];

    $form['advanced']['user_behavior']['clickSelector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('clickSelector'),
      '#default_value' => $this->entity->get('clickSelector'),
      '#description' => $this->t('Click the specified DOM element prior to screen shot.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['user_behavior']['clickSelectors'] = [
      '#type' => 'textfield',
      '#title' => $this->t('clickSelectors'),
      '#default_value' => $this->entity->get('clickSelectors'),
      '#description' => $this->t('Description of the backstop scenario.'),
      '#description_display' => 'before',
//      '#multiple' => TRUE,
    ];

    $form['advanced']['user_behavior']['postInteractionWait'] = [
      '#type' => 'number',
      '#title' => $this->t('postInteractionWait'),
      '#default_value' => $this->entity->get('postInteractionWait'),
      '#description' => $this->t('Wait for a selector after interacting with hoverSelector or clickSelector.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['scrollToSelector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('scrollToSelector'),
      '#default_value' => $this->entity->get('scrollToSelector'),
      '#description' => $this->t('Scrolls the specified DOM element into view prior to screen shot.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['selectors'] = [
      '#type' => 'textfield',
      '#title' => $this->t('selectors'),
      '#default_value' => $this->entity->get('selectors'),
      '#description' => $this->t('List of selectors to capture.'),
      '#description_display' => 'before',
//      '#multiple' => TRUE,
    ];

    $form['advanced']['selectorExpansion'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('selectorExpansion'),
      '#default_value' => $this->entity->get('selectorExpansion'),
      '#description' => $this->t('Whether to take screenshots of designated selectors.'),
      '#description_display' => 'before',
      '#return_value' => 1,
    ];

    $form['advanced']['expect'] = [
      '#type' => 'number',
      '#title' => $this->t('expect'),
      '#default_value' => $this->entity->get('expect'),
      '#description' => $this->t('The number of selector elements to test for.'),
      '#description_display' => 'before',
    ];


    // todo: Query the Viewports entity to create a select list.
    $form['advanced']['viewports'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Viewports'),
//      '#default_value' => $this->entity->get('viewports'),
      '#description' => $this->t('Viewports to test your page in.'),
      '#description_display' => 'before',
      '#options' => [],
    ];

    $form['advanced']['goToParameters-table'] = [
      '#type' => 'table',
      '#title' => 'goToParameters List',
      '#header' => ['Parameter', 'Actions'],
      '#prefix' => '<div id=goToParameters-table>',
      '#suffix' => '</div>',
    ];

    // todo: custom field with multiple columns for goToParameter values.
    $form['advanced']['goToParameters'] = [
      '#type' => 'textfield',
      '#title' => $this->t('goToParameter'),
      '#default_value' => $this->entity->get('goToParameters'),
      '#description' => $this->t('A list of settings passed to page.goto(url, parameters) function.'),
      '#description_display' => 'before',
    ];

    $form['advanced']['actions']['add_page'] = [
      '#type' => 'submit',
      '#value' => t('Add one more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'goToParameters',
      ],
      '#weight' => 2,
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
      ? $this->t('Created new backstop scenario %label.', $message_args)
      : $this->t('Updated backstop scenario %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
