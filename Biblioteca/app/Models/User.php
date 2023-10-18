<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

  public static function getModel(): User
  {
    $obj = parent::getModel();
    $obj->is_admin = 1;
    return $obj;
  }

  public static function listar(string|null $order = null): \Illuminate\Contracts\Pagination\Paginator
  {
    return self
      ::withTrashed()
      ->orderBy($order ?? 'login')
      ->simplePaginate(config('app.results-per-page'));
  }

  public static function findOrSave(object $row)
  {
    $id = self
      ::where('login', $row->login)
      ->value('id');

    if (!$id) {
      $row->password = Hash::make($row->password);
      $data = (array)$row;
      $data['is_admin'] = $row->is_admin ?? 0;
      $id = self::create($data)->id;
    }

    return $id;
  }

  public static function bloquear(int $id)
  {
    $row = self::find($id);
    if (!$row)
      return false;

    $row->delete();
  }

  public static function desbloquear(int $id)
  {
    $row = self::withTrashed()->find($id);
    if (!$row)
      return false;

    $row->restore();
  }

  public static function persist(object $row): int|null
  {
    //cadastrando Aluno pelo cadastro de Usuários
    if (!$row->id && !$row->is_admin)
      throw new Exception('', 400);

    $row->name = ucwords($row->name);

    if ($row->password)
      $row->password = Hash::make(trim($row->password));
    else
      unset($row->password);

    $salvarAluno = false;
    $aluno = $row->id ? Aluno::where('user_id', $row->id)->first() : null;
    if ($aluno) {
      $row->login = $aluno->matricula;

      if ($aluno->email !== $row->email || $aluno->nome !== $row->name) {
        $salvarAluno = true;
        $aluno->email = $row->email;
        $aluno->nome = $row->name;
      }
    }

    DB::beginTransaction();
    try {
      if ($salvarAluno)
        $aluno->update();

      if ($row->id) {
        $id = $row->id;
        parent::where('id', $id)->update((array)$row);
      } else $id = parent::create((array)$row)->id;
      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
      throw new Exception($e->getMessage(), 500, $e);
    }
    return $id;
  }

  public function getIsBloqueadoAttribute(): bool
  {
    return (bool)($this?->deleted_at);
  }

  protected $appends = ['is_bloqueado'];

  protected $fillable = [
    'name',
    'login',
    'email',
    'password',
    'is_admin',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];
}
