<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use function PHPUnit\Framework\returnSelf;

class Livro extends BaseModel
{
  use SoftDeletes, HasFactory;

  public function emprestimos(): HasMany
  {
    //return $this->hasMany(Emprestimo::class);
    return Emprestimo::aplicarFiltrosData($this->hasMany(Emprestimo::class));
  }

  public function reservas(): HasMany
  {
    //return $this->hasMany(Reserva::class);
    return Reserva::aplicarFiltrosData($this->hasMany(Reserva::class));
  }

  public static function listar(?string $titulo = '', ?string $autor = '', ?string $editora = '', ?string $order = ''): \Illuminate\Contracts\Pagination\Paginator
  {
    $qry = self::with(['reservas.aluno.user', 'emprestimos']);

    if ($titulo)
      $qry = $qry->where('titulo', 'like', "$titulo%");
    if ($autor)
      $qry = $qry->where('autor', 'like', "$autor%");
    if ($editora)
      $qry = $qry->where('editora', 'like', "$editora%");

    return $qry
      ->orderBy($order ?? 'titulo')
      ->simplePaginate(config('app.results-per-page'));
  }

  public static function persist(object $row): int|null
  {
    $row->titulo = ucwords($row->titulo);
    $row->editora = ucwords($row->editora);
    $row->autor = ucwords($row->autor);

    return parent::persist($row);
  }

  public function podeRetirar(): bool
  {
    return ($this->getSituacao($this->id) === 'Disponível');
  }

  public function getSituacaoAttribute(): string
  {
    if (count($this->emprestimos))
      return 'Emprestado';

    if (count($this->reservas))
      return 'Reservado';

    return 'Disponível';
  }

  public static function getSituacao(int $id, int $data = 0): string
  {
    $bloqueio = Emprestimo::aplicarFiltrosData(Emprestimo::where('livro_id', $id), $data)->count();
    if ($bloqueio)
      return 'Emprestado';

    $bloqueio = Reserva::aplicarFiltrosData(Reserva::where('livro_id', $id), $data)->count();
    if ($bloqueio)
      return 'Reservado';

    return 'Disponível';
  }

  //protected $table = 'livros';

  protected $appends = ['situacao'];

  protected $fillable = [
    'isbn',
    'titulo',
    'autor',
    'editora',
    'is_retirado',
  ];

  protected $casts = [
    'isbn' => 'integer',
  ];
}
