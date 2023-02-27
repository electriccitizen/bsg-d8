<?php

namespace Drupal\backstop_scenario\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'bs_keypress_selector_default' formatter.
 *
 * @FieldFormatter(
 *   id = "bs_keypress_selector_default",
 *   label = @Translation("Default"),
 *   field_types = {"bs_keypress_selector"}
 * )
 */
class KeypressSelectorsDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {

      if ($item->selector) {
        $element[$delta]['selector'] = [
          '#type' => 'item',
          '#title' => $this->t('Selector'),
          '#markup' => $item->selector,
        ];
      }

      if ($item->keyPress) {
        $element[$delta]['keyPress'] = [
          '#type' => 'item',
          '#title' => $this->t('Key Press'),
          '#markup' => $item->keyPress,
        ];
      }

    }

    return $element;
  }

}
