<?php

namespace Drupal\backstop_report\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\backstop_report\BackstopReportInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Defines the backstop report entity type.
 *
 * @ConfigEntityType(
 *   id = "backstop_report",
 *   label = @Translation("Backstop Report"),
 *   label_collection = @Translation("Backstop Reports"),
 *   label_singular = @Translation("backstop report"),
 *   label_plural = @Translation("backstop reports"),
 *   label_count = @PluralTranslation(
 *     singular = "@count backstop report",
 *     plural = "@count backstop reports",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\backstop_report\BackstopReportListBuilder",
 *     "form" = {
 *       "add" = "Drupal\backstop_report\Form\BackstopReportForm",
 *       "edit" = "Drupal\backstop_report\Form\BackstopReportForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "backstop_report",
 *   admin_permission = "administer backstop_report",
 *   links = {
 *     "collection" = "/admin/structure/backstop-report",
 *     "add-form" = "/admin/structure/backstop-report/add",
 *     "edit-form" = "/admin/structure/backstop-report/{backstop_report}",
 *     "delete-form" = "/admin/structure/backstop-report/{backstop_report}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "viewports",
 *     "onBeforeScript",
 *     "scenarios",
 *     "paths",
 *     "report",
 *     "engine",
 *     "engineOptions",
 *     "asyncCaptureLimit",
 *     "asyncCompareLimit",
 *     "debug",
 *     "debugWindow"
 *   }
 * )
 */
class BackstopReport extends ConfigEntityBase implements BackstopReportInterface {

  /**
   * The backstop report ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The backstop report label.
   *
   * @var string
   */
  protected $label;

  /**
   * The backstop report status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The backstop_report description.
   *
   * @var string
   */
  protected $description;

  /**
   * List of viewports included in this report.
   *
   * @var array
   */
  protected $viewports;

  /**
   *
   *
   * @var string
   */
  protected $onBeforeScript;

  /**
   * The list of scenarios (pages) in this report.
   *
   * @var array
   */
  protected $scenarios;

  /**
   *
   *
   * @var string
   */
  protected $paths;

  /**
   *
   *
   * @var string
   */
  protected $report;

  /**
   *
   *
   * @var string
   */
  protected $engine;

  /**
   *
   *
   * @var string
   */
  protected $engineOptions;

  /**
   *
   *
   * @var int
   */
  protected $asyncCaptureLimit;

  /**
   *
   *
   * @var int
   */
  protected $asyncCompareLimit;

  /**
   *
   *
   * @var bool
   */
  protected $debug;

  /**
   *
   *
   * @var bool
   */
  protected $debugWindow;

  /**
   * @inheritdoc
   */
  public function generateBackstopFile() {
    $backstop = new \stdClass();
    $viewport_entities = $this->getConfigEntities('backstop_viewport');
    $scenario_entities = $this->getConfigEntities('backstop_scenario');

    $backstop->id = $this->label;

    // Create the viewports array.
    $viewports = [];
    foreach ($this->viewports as $key => $id) {
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

    $backstop->onBeforeScript = $this->onBeforeScript;

    // Create the scenarios array.
    $scenarios = [];
    foreach ($this->scenarios as $key => $id) {
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
    $paths_array = explode(PHP_EOL, $this->paths);
    $paths = new \stdClass();
    foreach ($paths_array as $path) {
      preg_match('/(\w+)\|([\w\/]+)/', $path, $path_parts);
      $paths->{$path_parts[1]} = $path_parts[2];
    }
    $backstop->paths = $paths;

    $backstop->report = explode(',', $this->report);
    $backstop->engine = $this->engine;

    $engineOptions = new \stdClass();
    $engineOptions->args = explode(',', $this->engineOptions);
    $backstop->engineOptions = $engineOptions;

    $backstop->asyncCaptureLimit = $this->asyncCaptureLimit;
    $backstop->asyncCompareLimit = $this->asyncCompareLimit;
    $backstop->debug = $this->debug == 1 ? true : false;
    $backstop->debugWindow = $this->debugWindow == 1 ? true : false;

    // Create the backstop.json file.
    $backstop_file = fopen("/var/www/tests/backstop/backstop_{$this->id}.json", "w");
    fwrite($backstop_file, json_encode($backstop, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    fclose($backstop_file);
  }

  /**
   * Return the storage interface for the specified config entity.
   *
   * @param string $config_name
   *
   * @return \Drupal\Core\Entity\EntityStorageInterface
   */
  private function getConfigEntities(string $config_name): EntityStorageInterface {
    // Get the config entity manager.
    return \Drupal::service('entity_type.manager')
      ->getStorage($config_name);
  }

}
