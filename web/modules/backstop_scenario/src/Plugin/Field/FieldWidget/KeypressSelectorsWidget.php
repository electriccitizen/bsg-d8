<?php

namespace Drupal\backstop_scenario\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'bs_keypress_selector' field widget.
 *
 * @FieldWidget(
 *   id = "bs_keypress_selector",
 *   label = @Translation("Keypress Selectors"),
 *   field_types = {"bs_keypress_selector"},
 * )
 */
class KeypressSelectorsWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['selector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Selector'),
      '#default_value' => isset($items[$delta]->selector) ? $items[$delta]->selector : NULL,
      '#size' => 20,
    ];

    $element['keyPress'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Key Press'),
      '#default_value' => isset($items[$delta]->keyPress) ? $items[$delta]->keyPress : NULL,
      '#size' => 20,
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'container-inline';
    $element['#attributes']['class'][] = 'bs-keypress-selector-elements';
    $element['#attached']['library'][] = 'backstop_scenario/bs_keypress_selector';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    return isset($violation->arrayPropertyPath[0]) ? $element[$violation->arrayPropertyPath[0]] : $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if ($value['selector'] === '') {
        $values[$delta]['selector'] = NULL;
      }
      if ($value['keyPress'] === '') {
        $values[$delta]['keyPress'] = NULL;
      }
    }
    return $values;
  }

}
