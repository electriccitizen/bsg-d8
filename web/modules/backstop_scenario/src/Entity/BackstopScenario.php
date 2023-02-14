<?php

namespace Drupal\backstop_scenario\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\backstop_scenario\BackstopScenarioInterface;

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
 *     "list_builder" = "Drupal\backstop_scenario\BackstopScenarioListBuilder",
 *     "form" = {
 *       "add" = "Drupal\backstop_scenario\Form\BackstopScenarioForm",
 *       "edit" = "Drupal\backstop_scenario\Form\BackstopScenarioForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "backstop_scenario",
 *   admin_permission = "administer backstop_scenario",
 *   links = {
 *     "collection" = "/admin/structure/backstop-scenario",
 *     "add-form" = "/admin/structure/backstop-scenario/add",
 *     "edit-form" = "/admin/structure/backstop-scenario/{backstop_scenario}",
 *     "delete-form" = "/admin/structure/backstop-scenario/{backstop_scenario}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description"
 *   }
 * )
 */
class BackstopScenario extends ConfigEntityBase implements BackstopScenarioInterface {

  /**
   * The backstop scenario ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The backstop scenario label.
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

}
