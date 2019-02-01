<?php

namespace Drupal\itk_azure_video\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\Annotation\FieldType;
use Drupal\Core\Annotation\Translation;

/**
 * Plugin implementation of the 'itk_azure_video_field' field type.
 *
 * @FieldType(
 *   id = "itk_azure_video_field",
 *   label = @Translation("Azure Video field"),
 *   module = "itk_azure_video",
 *   description = @Translation("An entity field containing an Azure video URI."),
 *   default_formatter = "itk_azure_video_formatter",
 *   default_widget = "itk_azure_video_widget",
 * )
 */
class AzureVideo extends FieldItemBase implements FieldItemInterface {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 2048,
          'not null' => FALSE,
        ],
        'fallback' => [
          'type' => 'varchar',
          'length' => 2048,
          'not null' => FALSE,
        ]
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    $fallback = $this->get('fallback')->getValue();

    return ($value === NULL || $value === '') && ($fallback === NULL || $fallback === '');
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Azure Video Url'));
    $properties['fallback'] = DataDefinition::create('string')
      ->setLabel(t('Azure Video Fallback Url (.mp4)'));

    return $properties;
  }

}
