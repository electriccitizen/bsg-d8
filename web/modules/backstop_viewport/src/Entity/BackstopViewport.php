<?php

namespace Drupal\backstop_viewport\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\backstop_viewport\BackstopViewportInterface;

/**
 * Defines the backstop viewport entity type.
 *
 * @ConfigEntityType(
 *   id = "backstop_viewport",
 *   label = @Translation("Backstop Viewport"),
 *   label_collection = @Translation("Backstop Viewports"),
 *   label_singular = @Translation("backstop viewport"),
 *   label_plural = @Translation("backstop viewports"),
 *   label_count = @PluralTranslation(
 *     singular = "@count backstop viewport",
 *     plural = "@count backstop viewports",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\backstop_viewport\BackstopViewportListBuilder",
 *     "form" = {
 *       "add" = "Drupal\backstop_viewport\Form\BackstopViewportForm",
 *       "edit" = "Drupal\backstop_viewport\Form\BackstopViewportForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "backstop_viewport",
 *   admin_permission = "administer backstop_viewport",
 *   links = {
 *     "collection" = "/admin/structure/backstop-viewport",
 *     "add-form" = "/admin/structure/backstop-viewport/add",
 *     "edit-form" = "/admin/structure/backstop-viewport/{backstop_viewport}",
 *     "delete-form" = "/admin/structure/backstop-viewport/{backstop_viewport}/delete"
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
class BackstopViewport extends ConfigEntityBase implements BackstopViewportInterface {

  /**
   * The backstop viewport ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The backstop viewport label.
   *
   * @var string
   */
  protected $label;

  /**
   * The backstop viewport status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The backstop_viewport description.
   *
   * @var string
   */
  protected $description;

}
