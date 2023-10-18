<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Lib\Lib;
use Hamcrest\Type\IsNumeric;

class BaseModel extends Model
{
  use SoftDeletes;

  public static function getModel(): BaseModel
  {
    $obj = new static();
    return $obj;
  }

  public static function persist(object $row): int|null
  {
    $model = self::getModel();

    //remove propiedades dinâmicas
    //foreach ($model->attributes()->get() as $attribute) { unset($model->attributes[$attribute->code]); }

    //fillabe
    if ($model->fillable) {
      $array = array_intersect_key((array)$row, array_flip($model->fillable));
      if (!isset($array['id']))
        $array['id'] = 0;
      $rec = (object)$array;
      $rec->id = $row->id ?? 0;
    } else
      $rec = $row;

    //casts
    if ($model->casts) {
      foreach ($model->casts as $key => $val) {
        if (!isset($rec->$key))
          continue;
        $rec->$key = match ($val) {
          'datetime' => $model->dbDate($rec->$key),
          'integer' => Lib::numericOnly($rec->$key),
          default => $rec->$key,
        };
      }
    }

    $editing = (bool)($rec->id ?? 0);
    if (!$editing)
      return parent::create((array)$rec)->id;

    parent::where('id', $rec->id)->update((array)$rec);
    return $rec->id;
  }

  /** @param string|int|null $dt Timestamp ou string no format dd/mm/aaaa */
  protected static function dbDate(string|int|null $dt): \DateTime|null
  {
    if (!$dt) return null;

    if (is_numeric($dt))
      $dt = date('d/m/Y', $dt);

    if (strlen($dt) < 19)
      $dt .= ' 00:00:00';
    return \DateTime::createFromFormat('d/m/Y H:i:s', $dt);
  }


  protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
