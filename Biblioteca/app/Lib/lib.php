<?php

namespace App\Lib;

class Lib
{
  public static function numericOnly(?string $s): ?string
  {
    if ($s === null)
      return null;
    return preg_replace('/\D/', '', $s);
  }

  public static function getClassName(object $obj, bool $withNamespace = false): string
  {
    $s = get_class($obj);
    if ($withNamespace)
      return $s;
    return substr($s, strrpos($s, '\\') + 1);
  }

  public static function deepClone(object $src, array $propsToCopy = null): object
  {
    $arraySrc = (array)$src;
    //todas as propriedades
    if (!$propsToCopy)
      $propsToCopy = array_keys($arraySrc);

    $dst = array_intersect_key($arraySrc, array_flip($propsToCopy));
    /*
    $dst = [];
    if (!$propsToCopy) {
      foreach ($src as $key => $val)
        $dst[$key] = $val;
    } else {
      foreach ($src as $key => $val) {
        if (in_array($key, $propsToCopy))
          $dst[$key] = $val;
      }
    }
    */
    return (object)$dst;
  }

  public static function randomStr(int $len, string $chars = null): string
  {
    $chars = $chars ?? '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
    $charsLen = strlen($chars);
    $s = '';
    for ($i = 0; $i < $len; $i++) {
      $s .= $chars[random_int(0, $charsLen - 1)];
    }
    return $s;
  }

  public static function proximoDiaUtil(int $data): int
  {
    return match ((int)date('w', $data)) {
      //domingo?
      0 => strtotime("+1 days", $data),
      //sábado?
      6 => strtotime("+2 days", $data),
      default => $data
    };
  }
}
