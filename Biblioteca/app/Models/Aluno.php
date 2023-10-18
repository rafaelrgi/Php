<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Lib\Lib;

class Aluno extends BaseModel
{
  use SoftDeletes, HasFactory;

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function cep(): BelongsTo
  {
    return $this->belongsTo(Cep::class);
  }

  public function Emprestimos(): HasMany
  {
    return $this->hasMany(Emprestimo::class);
  }

  public function Reservas(): HasMany
  {
    return $this->hasMany(Reserva::class);
  }

  public static function pesquisar(int $matricula): object|null
  {
    if (!$matricula)
      return null;

    $row = self::where('matricula', $matricula)->first();
    return $row;
  }

  public static function listar(string|null $matricula = null, string|null $cpf = null, string|null $nome = null, string|null $email = null, string|null $fone = null): \Illuminate\Contracts\Pagination\Paginator
  {
    $qry = self
      ::with('user');

    if ($matricula)
      $qry = $qry->where('matricula', "$matricula");
    if ($cpf)
      $qry = $qry->where('cpf', 'like', "$cpf%");
    if ($nome)
      $qry = $qry->where('nome', 'like', "$nome%");
    if ($email)
      $qry = $qry->where('email', 'like', "$email%");
    if ($fone)
      $qry = $qry->where('fone', 'like', "$fone%");

    return $qry
      ->orderBy('nome')
      ->simplePaginate(config('app.results-per-page'));
  }

  public static function persist(object $row): int|null
  {
    $row->nome = ucwords($row->nome);

    DB::beginTransaction();
    try {
      $row->cep_id = ((int)$row->cep_id) ?: Cep::findOrSave(Lib::deepClone($row, ['cep', 'endereco', 'bairro', 'cidade', 'uf', 'manual']));

      $row->user_id = User::findOrSave((object)[
        'name' => $row->nome,
        'email' => $row->email,
        'login' => $row->matricula,
        'password' => $row->matricula,
      ]);

      $id = parent::persist($row);
      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
      throw $e;
    }
    return $id;
  }

  //protected $table = 'alunos';

  protected $fillable = [
    'user_id',
    'matricula',
    'cpf',
    'nome',
    'email',
    'fone',
    'cep_id',
    'numero',
  ];

  protected $casts = [];
}
