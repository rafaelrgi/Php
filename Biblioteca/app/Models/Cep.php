<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Lib\Lib;

class Cep extends BaseModel
{
  use HasFactory;

  public function pessoas(): HasMany
  {
    return $this->hasMany(Pessoas::class);
  }

  public static function findOrSave(object $row): ?int
  {
    if (!$row->cep)
      return null;

    $row->cep = Lib::numericOnly($row->cep);

    $id = self
      ::where('cep', $row->cep)
      ->value('id');

    if (!$id)
      $id = self::persist($row);

    return $id ?: null;
  }

  public static function persist(object $row): int
  {
    if (!$row->cep)
      return 0;

    $row->bairro = ucwords($row->bairro);
    $row->cidade = ucwords($row->cidade);
    $row->uf = strtoupper($row->uf);

    return parent::persist($row);
  }

  public static function pesquisar($code)
  {
    return self::pesquisarDb($code) ?? self::pesquisarWs($code);
  }

  private static function pesquisarDb($code)
  {
    return self
      ::where('cep', $code)
      ->where('manual', false)
      ->first();
  }

  private static function pesquisarWs($code)
  {
    $url = "https://brasilapi.com.br/api/cep/v2/$code";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // For HTTPS
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // For HTTPS
    $result = self::formatWsResult(curl_exec($curl));
    curl_close($curl);

    return $result;
  }

  private static function formatWsResult($result)
  {
    $result = json_decode($result);
    if (!($result->cep ?? null))
      return null;

    $zip = (object)[
      'id' => 0,
      'cep' => $result->cep,
      'endereco' => $result->street,
      'bairro' => $result->neighborhood,
      'cidade' => $result->city,
      'uf' => $result->state,
      'manual' => 0,
    ];

    return $zip;
  }

  protected $fillable = [
    'cep',
    'endereco',
    'bairro',
    'cidade',
    'uf',
    'manual',
  ];

  protected $casts = [
    'cep' => 'integer'
  ];
}
