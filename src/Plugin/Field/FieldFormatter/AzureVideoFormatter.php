<?php

namespace Drupal\itk_azure_video\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Annotation\FieldFormatter;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormStateInterface;

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
    $summary[] = $this->t('Responsive: @responsive.', ['@responsive' =>
      $this->getSetting('responsive') ? $this->t('Yes'): $this->t('No')
    ]);
    $summary[] = $this->t('Muted: @muted.', ['@muted' =>
      $this->getSetting('muted') ? $this->t('Yes'): $this->t('No')
    ]);
    $summary[] = $this->t('Autoplay: @autoplay.', ['@autoplay' =>
      $this->getSetting('autoplay') ? $this->t('Yes'): $this->t('No')
    ]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $settings = [];

    if ($this->getSetting('muted')) {
      $settings[] = 'muted';
    }

    if ($this->getSetting('autoplay')) {
      $settings[] = 'autoplay';
    }

    $settingsString = implode(' ', $settings);

    foreach ($items as $delta => $item) {
      $url = $item->value;
      $pathInfo = pathinfo($url);

      if ($pathInfo['extension'] == '') {
        $url .= '(format=mpd-time-csf)';
      }

      $classes = ['itk-azure-video'];

      if ($this->getSetting('responsive')) {
        $classes[] = 'itk-azure-video-responsive';
      }

      $classesString = implode(' ', $classes);

      $markup =
        '<div class="'.$classesString.'">' .
        '<video data-dashjs-player '.$settingsString.' src="'.$url.'" controls></video>' .
        '</div>';

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

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'responsive' => true,
        'muted' => false,
        'autoplay' => false,
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = [];
    $element['responsive'] = [
      '#title' => $this->t('Responsive'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('responsive'),
    ];
    $element['muted'] = [
      '#title' => $this->t('Muted'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('muted'),
    ];
    $element['autoplay'] = [
      '#title' => $this->t('Autoplay'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('autoplay'),
    ];

    return $element;
  }
}
