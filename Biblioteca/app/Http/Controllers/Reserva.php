<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use App\Models\Reserva as Model;

class Reserva extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function listar()
  {
    $rows = Model::listar();
    $this->saveUrl();
    return view('reserva-lst', [
      'rows' => $rows,
    ]);
  }

  public function ver(Model $row)
  {
    return view('reserva-frm', [
      'canEdit' => false,
      'row' => $row
    ]);
  }

  public function reservas(Livro $livro, int $mes = 0, int $ano = 0)
  {
    if ($mes > 12)
      return redirect("/reservas/reservas/$livro->id/1/" . $ano + 1);
    else if ($mes < 1 && $ano != 0)
      return redirect("/reservas/reservas/$livro->id/12/" . $ano - 1);

    try {
      $calendario = Model::calendario($livro, $mes, $ano);
    } catch (\Throwable $th) {
      session()->flash('Message', $th->getMessage());
      return redirect("/reservas/reservas/$livro->id");
    }

    return view('reserva-lst', [
      'calendario' => $calendario
    ]);
  }

  public function reservar(Livro $livro, int $dia, int $mes, int $ano)
  {
    $row = new Model();
    //TODO: se usuário for aluno: $row->aluno_id = ....
    $row->livro_id = $livro->id;
    $row->titulo = $livro->titulo;
    $row->data = mktime(0, 0, 0, $mes, $dia, $ano);
    return view('reserva-frm', [
      'row' => $row,
      'canEdit' => true,
    ]);
  }

  public function retirar(Model $reserva)
  {
    $dias = config('app.dias_emprestimo') - 1;
    if ($reserva->data->timestamp > strtotime("today midnight +$dias days")) {
      session()->flash('Message', 'Esta reserva é para o dia ' . $reserva->data->format('d/m/Y'));
      return redirect(session('Url'));
    }

    return (new Emprestimo())->retirarReserva($reserva);
  }

  public function cancelar(Model $reserva)
  {
    $reserva->delete();
    return redirect(session('Url'));
  }

  public function salvar(Request $request)
  {
    $row = $this->validate($request, [
      'id' => 'nullable',
      'livro_id' => 'required',
      'aluno_id' => 'required',
      'data' => 'required',
    ]);

    Model::persist($row);

    return redirect(session('Url'));
  }
}
