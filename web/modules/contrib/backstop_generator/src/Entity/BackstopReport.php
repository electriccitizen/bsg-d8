<?php

namespace Drupal\backstop_generator\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\backstop_generator\BackstopReportInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\File\FileSystemInterface;

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
 *     "list_builder" = "Drupal\backstop_generator\BackstopReportListBuilder",
 *     "form" = {
 *       "add" = "Drupal\backstop_generator\Form\BackstopReportForm",
 *       "edit" = "Drupal\backstop_generator\Form\BackstopReportForm",
 *       "delete" = "Drupal\backstop_generator\Form\BackstopReportDeleteForm"
 *     }
 *   },
 *   config_prefix = "report",
 *   admin_permission = "administer backstop_generator",
 *   links = {
 *     "collection" = "/admin/config/development/backstop-generator/reports",
 *     "add-form" = "/admin/config/development/backstop-generator/report/add",
 *     "edit-form" = "/admin/config/development/backstop-generator/report/{backstop_report}",
 *     "delete-form" = "/admin/config/development/backstop-generator/report/{backstop_report}/delete"
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
 *     "use_globals",
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
   * Whether to use backstop default settings.
   *
   * @var bool
   */
  protected $use_globals = TRUE;

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
  public function generateBackstopFile($id = NULL) {
    $backstop = new \stdClass();
    $viewport_entities = $this->getConfigEntities('backstop_viewport');
    $scenario_entities = $this->getConfigEntities('backstop_scenario');
    $backstop_config = \Drupal::config('backstop_generator.settings');
    $report_config = $id ? \Drupal::config("backstop_report.$id") : NULL;

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

    $backstop->onBeforeScript = $this->use_globals ? $backstop_config->get('onBeforeScript') : $this->onBeforeScript;

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
      $scenario->hideSelectors = !empty($entity->get('hideSelectors')) ?
        explode(',', $entity->get('hideSelectors')) : [];
      $scenario->removeSelectors = !empty($entity->get('removeSelectors')) ?
        explode(',', $entity->get('removeSelectors')) : [];
      $scenarios[] = $scenario;
    }
    $backstop->scenarios = $scenarios;

    // Create the paths object.
    $paths_value = $this->paths ?? $backstop_config->get('paths');
    $paths_array = explode(PHP_EOL, $paths_value);
    $paths = new \stdClass();
    foreach ($paths_array as $path) {
      preg_match('/(\w+)\|([\w\/]+)/', $path, $path_parts);
      $paths->{$path_parts[1]} = $path_parts[2];
    }
    $backstop->paths = $paths;

    $report_value = $this->report ?? $backstop_config->get('report');
    $backstop->report = explode(',', $report_value);
    $backstop->engine = $this->engine ?? $backstop_config->get('engine');

    $engineOptions_value = $this->engineOptions ?? $backstop_config->get('engineOptions');
    $engineOptions = new \stdClass();
    $engineOptions->args = explode(',', $engineOptions_value);
    $backstop->engineOptions = $engineOptions;

    $backstop->asyncCaptureLimit = $this->asyncCaptureLimit ?? $backstop_config->get('asyncCaptureLimit');
    $backstop->asyncCompareLimit = $this->asyncCompareLimit ?? $backstop_config->get('asyncCompareLimit');
    $debug_value = $this->debug ?? $backstop_config->get('debug');
    $backstop->debug = $debug_value == 1 ? true : false;
    $debugWindow_value = $this->debugWindow ?? $backstop_config->get('debugWindow');
    $backstop->debugWindow = $debugWindow_value == 1 ? true : false;

    // Create and save the backstop.json file.
    // Create the report directory.
    /** @var \Drupal\Core\File\FileSystemInterface $file_system */
    $file_system = \Drupal::service('file_system');
    $project_root = dirname(DRUPAL_ROOT);
    $report_directory = "$project_root/{$backstop_config->get('backstop_directory')}/$this->id";
    $file_system->prepareDirectory($report_directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);

    // Create, write and save the backstop file.
    $backstop_file = fopen("$report_directory/backstop.json", "w");
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
