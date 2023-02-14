<?php

namespace Drupal\backstop_report\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\backstop_report\BackstopReportInterface;

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
 *     "description"
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

}
