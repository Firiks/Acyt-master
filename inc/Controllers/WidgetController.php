<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Controllers;

use AcytMaster\Base\BaseClass;
use AcytMaster\Api\Widgets\MediaWidget;

/**
* Widget Class
*/
class WidgetController extends BaseClass
{

  public function register() {
    if ( ! $this->activated( 'media_widget' ) ) return;

    $media_widget = new MediaWidget();
    $media_widget->register();
  }

}