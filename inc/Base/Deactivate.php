<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Base;

/**
 * Class with deativate callback
 */
final class Deactivate
{
  public static function deactivate() {
    flush_rewrite_rules();
  }
}