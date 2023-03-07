<?php

namespace Drupal\backstop_generator\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\backstop_generator\BackstopViewportInterface;

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
 *     "list_builder" = "Drupal\backstop_generator\BackstopViewportListBuilder",
 *     "form" = {
 *       "add" = "Drupal\backstop_generator\Form\BackstopViewportForm",
 *       "edit" = "Drupal\backstop_generator\Form\BackstopViewportForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "viewport",
 *   admin_permission = "administer backstop_viewport",
 *   links = {
 *     "collection" = "/admin/config/development/backstop-js/viewports",
 *     "add-form" = "/admin/config/development/backstop-js/viewport/add",
 *     "edit-form" = "/admin/config/development/backstop-js/viewport/{backstop_viewport}",
 *     "delete-form" = "/admin/config/development/backstop-js/viewport/{backstop_viewport}/delete"
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
 *     "height",
 *     "width"
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

  /**
   * The height in pixels of the viewport.
   *
   * @var int
   */
  protected $height;

  /**
   * The width in pixels of the viewport.
   *
   * @var int
   */
  protected $width;

}
