<?php

namespace Drupal\backstop_generator\Form;

use Drupal\backstop_generator\Entity\BackstopReport;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Backstop Viewport form.
 *
 * @property \Drupal\backstop_generator\BackstopViewportInterface $entity
 */
class BackstopViewportForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the backstop viewport.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\backstop_generator\Entity\BackstopViewport::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

//    $form['status'] = [
//      '#type' => 'checkbox',
//      '#title' => $this->t('Enabled'),
//      '#default_value' => $this->entity->status(),
//    ];

//    $form['description'] = [
//      '#type' => 'textarea',
//      '#title' => $this->t('Description'),
//      '#default_value' => $this->entity->get('description'),
//      '#description' => $this->t('Description of the backstop viewport.'),
//      '#rows' => 2,
//    ];

    $form['width'] = [
      '#type' => 'number',
      '#title' => t('Viewport Width'),
      '#default_value' => $this->entity->get('width'),
    ];

    $form['height'] = [
      '#type' => 'number',
      '#title' => t('Viewport Height'),
      '#default_value' => $this->entity->get('height'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new backstop viewport %label.', $message_args)
      : $this->t('Updated backstop viewport %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));

    $updated_reports = $this->updateReports();
    $update_message = count($updated_reports) > 0 ?
      t('Updated %label backstop.json report file.', ['%label' => implode(', ', $updated_reports)]) :
      t('No reports needed to be updated.');
    $this->messenger->addMessage($update_message);

    return $result;
  }

  private function updateReports() {
    // Get the report config ids.
    $report_ids = \Drupal::entityTypeManager()
      ->getStorage('backstop_report')
      ->getQuery()
      ->execute();
    $updated_reports = [];

    foreach ($report_ids as $id) {
      // Get the report config.
      $report_config = \Drupal::configFactory()->getEditable("backstop_generator.report.$id");
      if (in_array($this->entity->id(), $report_config->get('viewports'), TRUE)) {
        // Update the backstop.json file.
        $report = BackstopReport::load($id);
        $report->generateBackstopFile($id);
        $updated_reports[] = $report->label();
      }
    }
    return $updated_reports;
  }

}
