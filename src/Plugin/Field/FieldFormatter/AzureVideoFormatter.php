<?php

namespace Drupal\itk_azure_video\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Annotation\FieldFormatter;
use Drupal\Core\Annotation\Translation;

/**
 * Plugin implementation of the 'itk_azure_video_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "itk_azure_video_formatter",
 *   module = "itk_azure_video",
 *   label = @Translation("Azure Video formatter"),
 *   field_types = {
 *     "itk_azure_video_field"
 *   }
 * )
 */
class AzureVideoFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays the azure video.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $markup = '<div class="itk-azure-video"><video data-dashjs-player muted src="' . $item->value . '(format=mpd-time-csf)" controls></video></div>';

      // Render each element as markup.
      $element[$delta] = ['#markup' => $markup];

      $element[$delta] = [
        '#type' => 'inline_template',
        '#template' => $markup,
        '#attached' => ['library'=> ['itk_azure_video/azure-video']],
      ];
    }

    return $element;
  }

}
