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
    $summary[] = $this->t('Controls: @controls.', ['@controls' =>
      $this->getSetting('controls') ? $this->t('Yes'): $this->t('No')
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

    if ($this->getSetting('controls')) {
      $settings[] = 'controls';
    }

    $settingsString = implode(' ', $settings);

    foreach ($items as $delta => $item) {
      $source = $item->value;
      $pathInfo = pathinfo($source);

      if (!empty($source) && (!isset($pathInfo['extension']) ||  $pathInfo['extension'] == '')) {
        $source .= '(format=mpd-time-csf)';
      }

      $classes = ['itk-azure-video'];

      if ($this->getSetting('responsive')) {
        $classes[] = 'itk-azure-video-responsive';
      }

      $classesString = implode(' ', $classes);

      $fallback = $item->fallback;

      if (!empty($source) || !empty($fallback)) {
        $markup =
          '<div class="'.$classesString.'">' .
          '<video data-dashjs-player disablePictureInPicture '.$settingsString.'>' .
            (!empty($source) ? '<source src="'.$source.'" type="application/dash+xml">' : '') .
            (!empty($fallback) ? '<source src="'.$fallback.'" type="video/mp4">' : '') .
          '</video>' .
          '</div>';

        $element[$delta] = [
          '#type' => 'inline_template',
          '#template' => $markup,
        ];

        // Only attach dash library if MPEG-DASH source set.
        if (isset($source)) {
          $element[$delta]['#attached'] = ['library'=> ['itk_azure_video/azure-video']];
        }
      }
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
        'controls' => true,
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
    $element['controls'] = [
      '#title' => $this->t('Controls'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('controls'),
    ];

    return $element;
  }
}
