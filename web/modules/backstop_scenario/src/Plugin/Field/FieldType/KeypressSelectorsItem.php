<?php

namespace Drupal\backstop_scenario\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'bs_keypress_selector' field type.
 *
 * @FieldType(
 *   id = "bs_keypress_selector",
 *   label = @Translation("Keypress Selectors"),
 *   category = @Translation("General"),
 *   default_widget = "bs_keypress_selector",
 *   default_formatter = "bs_keypress_selector_default"
 * )
 */
class KeypressSelectorsItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->selector !== NULL) {
      return FALSE;
    }
    elseif ($this->keyPress !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['selector'] = DataDefinition::create('string')
      ->setLabel(t('Selector'));
    $properties['keyPress'] = DataDefinition::create('string')
      ->setLabel(t('Key Press'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    // @todo Add more constraints here.
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'selector' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'keyPress' => [
        'type' => 'varchar',
        'length' => 255,
      ],
    ];

    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {

    $random = new Random();

    $values['selector'] = $random->word(mt_rand(1, 255));

    $values['keyPress'] = $random->word(mt_rand(1, 255));

    return $values;
  }

}
