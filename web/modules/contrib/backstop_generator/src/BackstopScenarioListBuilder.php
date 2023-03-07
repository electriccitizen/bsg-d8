<?php

namespace Drupal\backstop_generator;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of backstop scenarios.
 */
class BackstopScenarioListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['id'] = $this->t('ID');
    $header['bundle'] = $this->t('Bundle');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\backstop_generator\BackstopScenarioInterface $entity */
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['bundle'] = $entity->get('bundle');
    return $row + parent::buildRow($entity);
  }

}
