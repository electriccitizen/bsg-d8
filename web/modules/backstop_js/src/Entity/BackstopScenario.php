<?php

namespace Drupal\backstop_js\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\backstop_js\BackstopScenarioInterface;

/**
 * Defines the backstop scenario entity type.
 *
 * @ConfigEntityType(
 *   id = "backstop_scenario",
 *   label = @Translation("Backstop Scenario"),
 *   label_collection = @Translation("Backstop Scenarios"),
 *   label_singular = @Translation("backstop scenario"),
 *   label_plural = @Translation("backstop scenarios"),
 *   label_count = @PluralTranslation(
 *     singular = "@count backstop scenario",
 *     plural = "@count backstop scenarios",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\backstop_js\BackstopScenarioListBuilder",
 *     "form" = {
 *       "add" = "Drupal\backstop_js\Form\BackstopScenarioForm",
 *       "edit" = "Drupal\backstop_js\Form\BackstopScenarioForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "scenario",
 *   admin_permission = "administer backstop_scenario",
 *   links = {
 *     "collection" = "/admin/config/development/backstop-js/scenarios",
 *     "add-form" = "/admin/config/development/backstop-js/scenario/add",
 *     "edit-form" = "/admin/config/development/backstop-js/scenario/{backstop_scenario}",
 *     "delete-form" = "/admin/config/development/backstop-js/scenario/{backstop_scenario}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "page",
 *     "id",
 *     "label",
 *     "description",
 *     "bundle",
 *     "onBeforeScript",
 *     "cookiePath",
 *     "url",
 *     "referenceUrl",
 *     "readyEvent",
 *     "readySelector",
 *     "readyTimeout",
 *     "delay",
 *     "hideSelectors",
 *     "removeSelectors",
 *     "onReadyScript",
 *     "keyPressSelectors",
 *     "hoverSelector",
 *     "hoverSelectors",
 *     "clickSelector",
 *     "clickSelectors",
 *     "postInteractionWait",
 *     "scrollToSelector",
 *     "selectors",
 *     "selectorExpansion",
 *     "expect",
 *     "misMatchThreshold",
 *     "requireSameDimensions",
 *     "viewports",
 *     "gotoParameters"
 *   }
 * )
 */
class BackstopScenario extends ConfigEntityBase implements BackstopScenarioInterface {

  /**
   * The nid of the page to test.
   *
   * @var int
   */
  protected $page;

  /**
   * The backstop scenario ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The backstop scenario label.
   * Also the tag saved with your reference images.
   *
   * @var string
   */
  protected $label;

  /**
   * The backstop scenario status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The backstop_scenario description.
   *
   * @var string
   */
  protected $description;

  /**
   * The bundle name of the referenced node.
   *
   * @var string
   */
  protected $bundle;

  /**
   * Used to set up browser state e.g. cookies.
   *
   * @var string
   */
  protected $onBeforeScript;

  /**
   * Import cookies in JSON format.
   *
   * @var string
   */
  protected $cookiePath;

  /**
   * The url of your app state.
   *
   * @var string
   */
  protected $url;

  /**
   * Specify a different state or environment when creating reference.
   *
   * @var string
   */
  protected $referenceUrl;

  /**
   * Wait until this string has been logged to the console.
   *
   * @var string
   */
  protected $readyEvent;

  /**
   * Wait until this selector exists before continuing.
   *
   * @var string
   */
  protected $readySelector;

  /**
   * Timeout for readyEvent and readySelector.
   *
   * @var int
   */
  protected $readyTimeout;

  /**
   * Wait for x milliseconds.
   *
   * @var int
   */
  protected $delay;

  /**
   * Array of selectors set to visibility: hidden.
   *
   * @var array
   */
  protected $hideSelectors;

  /**
   * Array of selectors set to display: none.
   *
   * @var array
   */
  protected $removeSelectors;

  /**
   * Script to modify UI state prior to screen shots e.g. hovers, clicks etc.
   *
   * @var string
   */
  protected $onReadyScript;

  /**
   * List of selectors to simulate multiple sequential keypress interactions.
   *
   * @var array
   */
  protected $keyPressSelectors;

  /**
   * Move the pointer over the specified DOM element prior to screen shot.
   *
   * @var string
   */
  protected $hoverSelector;

  /**
   * Selectors to simulate multiple sequential hover interactions.
   *
   * @var array
   */
  protected $hoverSelectors;

  /**
   * Click the specified DOM element prior to screen shot.
   *
   * @var string
   */
  protected $clickSelector;

  /**
   * Selectors to simulate multiple sequential click interactions.
   *
   * @var array
   */
  protected $clickSelectors;

  /**
   * Wait for a selector after interacting with hoverSelector or clickSelector.
   *
   * @var string
   */
  protected $postInteractionWait;

  /**
   * Scrolls the specified DOM element into view prior to screen shot.
   * (available with default onReadyScript)
   *
   * @var string
   */
  protected $scrollToSelector;

  /**
   * Array of selectors to capture.
   *
   * @var array
   */
  protected $selectors;

  /**
   * Whether to take screenshots of designated selectors.
   *
   * @var bool
   */
  protected $selectorExpansion;

  /**
   * The number of selector elements to test for.
   *
   * @var int
   */
  protected $expect;

  /**
   * Percentage of different pixels allowed to pass test.
   *
   * @var int
   */
  protected $misMatchThreshold;

  /**
   * If set to true -- any change in selector size will trigger a test failure.
   *
   * @var bool
   */
  protected $requireSameDimensions;

  /**
   * An array of screen size objects your DOM will be tested against.
   *
   * @var array
   */
  protected $viewports;

  /**
   * An array of settings passed to page.goto(url, parameters) function.
   *
   * @var array
   */
  protected $gotoParameters;


}
