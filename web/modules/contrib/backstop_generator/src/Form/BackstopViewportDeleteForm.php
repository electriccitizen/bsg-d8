<?php

namespace Drupal\backstop_generator\Form;

use Drupal\backstop_generator\Entity\BackstopReport;
use Drupal\Core\Form\FormStateInterface;

class BackstopViewportDeleteForm extends \Drupal\Core\Entity\EntityConfirmFormBase {

  /**
   * @inheritDoc
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the %label viewport?', [
      '%label' => $this->entity->label(),
    ]);

  }

  /**
   * @inheritDoc
   */
  public function getCancelUrl() {
    return $this->t('Delete Viewport');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Delete the entity.
    $this->entity->delete();
    $this->messenger()->addMessage(t('The %name viewport has been deleted.', ['%name' => $this->entity->label()]));
    parent::submitForm($form, $form_state);
    // Update any reports using this viewport.
    $updated_reports = $this->updateReports();
    $update_message = count($updated_reports) > 0 ?
      t('Updated %label backstop.json report file.', ['%label' => implode(', ', $updated_reports)]) :
      t('No reports needed to be updated.');
    $this->messenger()->addMessage($update_message);
  }

  private function updateReports() {
    $updated_reports = [];

    // Get the report config ids.
    $report_ids = \Drupal::entityTypeManager()
      ->getStorage('backstop_report')
      ->getQuery()
      ->execute();

    foreach ($report_ids as $id) {
      // Get the report config.
      $report_config = $this->configFactory()->getEditable("backstop_generator.report.$id");

      if (in_array($this->entity->id(), $report_config->get('viewports'), TRUE)) {
        // Remove the viewport from the report config.
        $viewports = $report_config->get('viewports');
        unset($viewports[$this->entity->id()]);
        $report_config->set('viewports', $viewports);
        $report_config->save();

        // Update the backstop.json file.
        $report = BackstopReport::load($id);
        $report->generateBackstopFile($id);
        $updated_reports[] = $report->label();
      }
    }

    return $updated_reports;
  }


}
