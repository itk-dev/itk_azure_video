<?php

namespace Drupal\itk_azure_video\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Plugin\Field\FieldWidget\UriWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'itk_azure_video_widget' widget.
 *
 * @FieldWidget(
 *   id = "itk_azure_video_widget",
 *   label = @Translation("Azure URI field"),
 *   module = "itk_azure_video",
 *   field_types = {
 *     "itk_azure_video_field",
 *   }
 * )
 */
class AzureVideoWidget extends UriWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = $element + [
      '#type' => 'url',
      '#default_value' => $items[$delta]->value ?? NULL,
      '#size' => $this->getSetting('size'),
      '#placeholder' => $this->getSetting('placeholder'),
      '#maxlength' => $this->getFieldSetting('max_length'),
    ];
    $fallback = $element + [
      '#type' => 'url',
      '#default_value' => $items[$delta]->fallback ?? NULL,
      '#size' => $this->getSetting('size'),
      '#placeholder' => $this->getSetting('placeholder'),
      '#maxlength' => $this->getFieldSetting('max_length'),
    ];

    $element['value'] = $value;
    $element['fallback'] = $fallback;

    $element['fallback']['#title'] = $element['fallback']['#title'] . ' - ' . $this->t('Fallback .mp4');
    return $element;
  }

}
