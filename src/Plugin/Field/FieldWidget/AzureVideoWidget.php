<?php

namespace Drupal\itk_azure_video\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Annotation\FieldWidget;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Field\Plugin\Field\FieldWidget\UriWidget;

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

}
