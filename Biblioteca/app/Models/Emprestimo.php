<?php

namespace App\Models;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use \App\Lib\Lib;

class Emprestimo extends BaseModel
{
  use SoftDeletes, HasFactory;

  public static function getModel(Livro $livro = null, Aluno $aluno = null): BaseModel
  {
    $obj = parent::getModel();
    if (!$livro)
      return $obj;

    $obj->livro = $livro;
    if ($aluno)
      $obj->aluno = $aluno;
    $obj->dt_retirada = strtotime('today midnight');
    $obj->dt_prevista = self::dataPrevista($obj->dt_retirada->timestamp);
    return $obj;
  }

  public function livro(): BelongsTo
  {
    return $this->belongsTo(Livro::class);
  }
  public function aluno(): BelongsTo
  {
    return $this->belongsTo(Aluno::class);
  }

  public static function listar()
  {
    return self::simplePaginate(config('app.results-per-page'));
  }

  public static function persist(object $row): int|null
  {
    DB::beginTransaction();
    try {
      $id = parent::persist($row);

      if ($row->reserva_id)
        Reserva::setRetirada($row->reserva_id, $id);

      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
      throw $e;
    }
    return $id;
  }

  public function devolver()
  {
    $this->dt_devolucao = self::dbDate(strtotime('today midnight'));
    $this->update();
  }

  public function prorrogar(): DateTime|false
  {
    //não pode prorrogar se passa de 60 dias no futuro
    if ($this->dt_prevista->timestamp > strtotime('+60 days'))
      return false;

    //não pode prorrogar se reservado
    $situacao = Livro::getSituacao($this->livro_id, $this->dt_prevista->timestamp);
    if ($situacao !== 'Disponível')
      return false;

    $this->dt_prevista = $this->dataPrevista($this->dt_prevista->timestamp);
    $this->update();
    return $this->dt_prevista;
  }

  public static function dataPrevista(int $dt_retirada = null): int
  {
    $dt_retirada ??= strtotime('today midnight');
    $dias = config('app.dias_emprestimo');
    $dt_prevista = strtotime("+$dias days", $dt_retirada);

    return Lib::proximoDiaUtil($dt_prevista);
  }

  public static function aplicarFiltrosData(object $query, int $data = 0)
  {
    $hoje = strtotime('today midnight');
    $data = $data ?: $hoje;

    //Empréstimo bloqueia até data prevista devolução
    return $query
      //->whereBetween('dt_retirada', [$ini_bloqueio, self::dbDate($data)])
      ->whereNull('dt_devolucao')
      ->where('dt_prevista', '>', self::dbDate($data))
      ->where('dt_retirada', '<=', self::dbDate($hoje)); //Empréstimos futuros não existem
  }

  //protected $table = 'livro_emprestimos';

  protected $fillable = [
    'livro_id',
    'aluno_id',
    'dt_retirada',
    'dt_prevista',
    'dt_devolucao',
  ];

  protected $casts = [
    'dt_retirada' => 'datetime',
    'dt_prevista' => 'datetime',
    'dt_devolucao' => 'datetime',
  ];
}
