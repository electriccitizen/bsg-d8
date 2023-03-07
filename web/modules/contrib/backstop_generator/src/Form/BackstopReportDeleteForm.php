<?php

namespace Drupal\backstop_generator\Form;

use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityConfirmFormBase;

class BackstopReportDeleteForm extends EntityConfirmFormBase {

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

  /**
   * @inheritdoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Delete the entity.
    if ($this->removeReportFile()) {
      $this->entity->delete();
      parent::submitForm($form, $form_state);
    }
  }

  /**
   * Remove the backstop.json file from the file system.
   *
   * @return mixed
   */
  private function removeReportFile() {
    /** @var FileSystemInterface $file_system */
    $file_system = \Drupal::service('file_system');

    // Delete the backstop.json file and its parent directory.
    $project_dir = dirname(DRUPAL_ROOT);
    $backstop_dir = \Drupal::config('backstop_generator.settings')->get('backstop_directory');
    return $file_system->deleteRecursive($project_dir . "$backstop_dir/{$this->entity->id()}");
  }

}
