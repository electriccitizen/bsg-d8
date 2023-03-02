<?php

namespace Drupal\backstop_js\Form;

use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;

class BackstopReportDeleteForm extends \Drupal\Core\Entity\EntityConfirmFormBase {

  /**
   * @inheritDoc
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete this %label backstop report?', [
      '%label' => $this->entity->label(),
    ]);
  }

  /**
   * @inheritDoc
   */
  public function getCancelUrl() {
    return $this->t('Delete Report');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var FileSystemInterface $file_system */
    $file_system = \Drupal::service('file_system');

    // Delete the backstop.json file and its parent directory.
    $project_dir = dirname(DRUPAL_ROOT);
    $backstop_dir = \Drupal::config('backstop_js.settings')->get('backstop_directory');
    $file_system->deleteRecursive($project_dir . "$backstop_dir/{$this->entity->id()}");

    // Delete the entity.
    $this->entity->delete();
    parent::submitForm($form, $form_state);
  }


}
