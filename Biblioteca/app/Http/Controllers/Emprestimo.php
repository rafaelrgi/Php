<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use App\Models\Emprestimo as Model;

class Emprestimo extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function listar()
  {
    $rows = Model::listar();
    $this->saveUrl();
    return view('emprestimo-lst', [
      'rows' => $rows,
    ]);
  }

  public function retirar(Livro $livro)
  {
    if (!$livro->podeRetirar()) {
      session()->flash('Message', 'Não é possível retirar o livro pois ele está reservado!');
      return redirect(session('Url'));
    }

    return $this->_retirar($livro);
  }

  public function retirarReserva(\App\Models\Reserva $reserva)
  {
    if (!$reserva?->livro || !$reserva?->aluno) {
      session()->flash('Message', 'Parâmetros inválidos!');
      return redirect(session('Url'));
    }

    return $this->_retirar($reserva->livro, $reserva->aluno, $reserva->id);
  }

  private function _retirar(\App\Models\Livro $livro = null, \App\Models\Aluno $aluno = null, int $reserva_id = 0)
  {
    $row = Model::getModel($livro, $aluno);

    return view('emprestimo-frm', [
      'row' => $row,
      'reserva_id' => $reserva_id,
      'canEdit' => true,
    ]);
  }

  public function prorrogar(Model $emprestimo)
  {
    $data = $emprestimo->prorrogar();
    if (!$data)
      session()->flash('Message', 'Não foi possível prorrogar o empréstimo pois o livro está reservado!');
    else
      session()->flash('Message', 'Empréstimo prorrogado até ' .  $data->format('d/m/Y'));

    return redirect(session('Url'));
  }

  public function devolver(Model $emprestimo)
  {
    $emprestimo->devolver();
    session()->flash('Message', 'Livro devolvido!');
    return redirect(session('Url'));
  }

  public function salvar(Request $request)
  {
    $row = $this->validate($request, [
      'id' => 'nullable',
      'livro_id' => 'required',
      'aluno_id' => 'required',
      'reserva_id' => 'nullable',
      'dt_retirada' => 'required',
      'dt_prevista' => 'required',
      'dt_devolucao' => 'nullable',
    ]);

    Model::persist($row);

    return redirect(session('Url'));
  }

  /*
  public function remover(int $id)
  {
    $row = (new Model())->find($id);
    $row->delete();
    return redirect(session('Url'));
  }
  public function remover(Request $request)
  {
    $id = (int)$request->id; //Request::isMethod('post')
    $row = (new Model())->find($id);
    $row->delete();
    return redirect('/livro-emprestimos');
  }
  */
}
