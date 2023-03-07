<?php

namespace Drupal\backstop_js;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of backstop viewports.
 */
class BackstopViewportListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['id'] = $this->t('Machine name');
    $header['size'] = $this->t('Size');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\backstop_viewport\BackstopViewportInterface $entity */
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['size'] = "{$entity->get('width')}w x {$entity->get('height')}h";
    return $row + parent::buildRow($entity);
  }

}
