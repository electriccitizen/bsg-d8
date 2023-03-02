<?php

namespace Drupal\backstop_js\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for BackstopJS routes.
 */
class BackstopJsController extends ControllerBase {

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The controller constructor.
   *
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(FileSystemInterface $file_system) {
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('file_system')
    );
  }

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('For each report, run the Terminal commands under each heading from within in your project root directory.'),
    ];
    $build['info'] = [
      '#markup' => $this->detailMarkup(),
    ];


    return $build;
  }


  /**
   * Returns the report directories within the backstop directory.
   *
   * @return array|false
   */
  private function getBackstopReportDirectories() {
    $directory = \Drupal::config('backstop_js.settings')->get('backstop_directory');
    $directory = dirname(DRUPAL_ROOT) . $directory;

    $files = is_dir($directory) ? scandir($directory) : [];

    foreach ($files as $key => $file) {
      if (preg_match('/^\./', $file)) {
        unset($files[$key]);
        continue;
      }
      if (is_file("$directory/$file")) {
        unset($files[$key]);
      }
    }
    return $files;
  }

  /**
   * Generates the text for the commands to run backstop tests.
   *
   * @return string
   */
  private function detailMarkup() {
    $report_dirs = $this->getBackstopReportDirectories();
    $directory = \Drupal::config('backstop_js.settings')->get('backstop_directory');

    $markup = "<div class=\"test-code-paths\">";
    foreach ($report_dirs as $dir) {
      $report_name = ucwords(str_replace('_', ' ', $dir));
      $markup .= "<h2>Run the \"$report_name\" report</h2>";
      $markup .= "<pre>";
      $markup .= "cd $directory/$dir\n";
      $markup .= "backstop reference\n";
      $markup .= "backstop test";
      $markup .= "</pre>";
    }
    $markup .= "</div>";

    return $markup;
  }

}
