<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use \App\Lib\Lib;

class Reserva extends BaseModel
{
  use SoftDeletes, HasFactory;

  public function livro(): BelongsTo
  {
    return $this->belongsTo(Livro::class);
  }
  public function aluno(): BelongsTo
  {
    return $this->belongsTo(Aluno::class);
  }

  public static function setRetirada($reserva_id, $retirada_id)
  {
    $reserva = self::find($reserva_id);
    $reserva->emprestimo_id = $retirada_id;
    $reserva->update();
  }

  public static function aplicarFiltrosData(object $query, int $data = 0)
  {
    $hoje = strtotime('today midnight');
    $data = $data ?: $hoje;
    $dias = config('app.dias_emprestimo');
    //Reserva bloqueia 15 dias antes e 15 dias depois
    $ini_bloqueio = date('Y-m-d', strtotime("-$dias days", $data));
    $fim_bloqueio = date('Y-m-d', strtotime("+$dias days", $data));

    return $query
      ->whereBetween('data', [$ini_bloqueio, $fim_bloqueio])
      //Reservas já retiradas não valem mais
      ->whereNull('emprestimo_id')
      //Reservas canceladas não valem
      ->whereNull('deleted_at')
      //Reservas passadas não valem mais
      ->where('data', '>=', date('Y-m-d', $hoje));
  }

  //UNDONE: quebrar método
  public static function calendario(Livro $livro, int $mes = 0, int $ano = 0): object
  {
    $hoje = strtotime('today midnight');
    $mesAtual = (int)date('m');
    $anoAtual = (int)date('Y');

    $ano = $ano ?: $anoAtual;
    $mes = $mes ?: $mesAtual;

    //não pode consultar meses passados
    if ($ano < $anoAtual || ($ano === $anoAtual && $mes < $mesAtual))
      throw new Exception('Data inválida!');

    //só pode consultar até 2 meses para frente
    $max = strtotime('+60days');
    $mesMax = (int)date('m', $max);
    $anoMax = (int)date('Y', $max);
    if ($max < mktime(0, 0, 0, $mes, 1, $ano))
      throw new Exception('Data inválida!');

    //calendario
    $dia = 1;
    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    $dias_emprestimo = config('app.dias_emprestimo');

    $dias = [];
    for ($i = $dia; $i <= $dias_no_mes; $i++)
      $dias[$i] = 'D';
    //dias passados não pode reservar
    if ($mes === $mesAtual) {
      $max = (int)date('d');
      for ($i = 1; $i < $max; $i++)
        $dias[$i] = 'X';
    }

    //livro retirado? quando volta?
    $retirada = Emprestimo
      ::where('livro_id', $livro->id)
      ->where('dt_prevista', '>', self::dbDate(mktime(0, 0, 0, $mes, 1, $ano)))
      ->whereNull('dt_devolucao')
      ->first();

    //reservas que impactam o mês em questão
    $dt_ini = self::dbDate(mktime(0, 0, 0, $mes, -2 - $dias_emprestimo, $ano));
    $dt_fim = self::dbDate(mktime(0, 0, 0, $mes, $dias_no_mes + $dias_emprestimo, $ano));
    $reservas = Reserva
      ::where('livro_id', $livro->id)
      ->whereBetween('data', [$dt_ini, $dt_fim])
      ->whereNull('deleted_at')   //Reservas canceladas não valem
      ->whereNull('emprestimo_id')   //Reservas já retiradas não valem mais
      ->where('data', '>=', date('Y-m-d', $hoje)) //Reservas passadas não valem mais
      ->get();

    //aplica reservas, se houver
    $anoMes = (int)($ano . str_pad($mes, 2, '0', STR_PAD_LEFT));
    foreach ($reservas as $reserva) {
      $anoMesReserva = (int)($reserva->data->format('Ym'));
      //reserva do mês atual?
      if ($anoMes === $anoMesReserva) {
        $min = max(1, (int)($reserva->data->format('d')) - $dias_emprestimo);
        $max = min($dias_no_mes, (int)($reserva->data->format('d')) + $dias_emprestimo);
      }
      //reserva do mês anterior?
      else if ($anoMes > $anoMesReserva) {
        $min = 1;
        $max = (int)date('d', Lib::proximoDiaUtil(strtotime("+$dias_emprestimo days", $reserva->data->timestamp)));
      }
      //reserva do mês seguinte?
      else if ($anoMes < $anoMesReserva) {
        $min = (int)date('d', strtotime("-$dias_emprestimo days", $reserva->data->timestamp));
        $max = $dias_no_mes;
      }
      //aplica reserva no calendário
      for ($i = $min; $i <= $max; $i++)
        $dias[$i] = 'R';
    }

    //aplica emprestimo, se houver
    if ($retirada) {
      $max = (int)date('d', Lib::proximoDiaUtil($retirada->dt_prevista->timestamp));
      for ($i = $dia; $i <= $max; $i++)
        $dias[$i] = 'E';
    }

    $data_dia_1 = mktime(0, 0, 0, $mes, 1, $ano);

    return (object)[
      'livro' => $livro->id,
      'titulo' => $livro->titulo,
      'mes' => $mes,
      'ano' => $ano,
      'dias_no_mes' => $dias_no_mes,
      'dia_semana_1' => date('w', $data_dia_1),
      'hoje' => $hoje,
      'data' => $data_dia_1,
      'mesAtual' => $mesAtual,
      'anoAtual' => $anoAtual,
      'atual' => (bool)($mes === $mesAtual && $ano === $anoAtual),
      'mesMax' => $mesMax,
      'anoMax' => $anoMax,
      'dias' => $dias,
    ];
  }

  //protected $table = 'livro_reservas';

  protected $fillable = [
    'livro_id',
    'aluno_id',
    'data',
  ];

  protected $casts = [
    'data' => 'datetime',
  ];
}
