<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\Lib;
use App\Models\Aluno as Model;

class Aluno extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function listar(Request $request)
  {
    $matricula  = $request->input('matricula');
    $cpf = $request->input('cpf');
    $nome = $request->input('nome');
    $email = $request->input('email');
    $fone = $request->input('fone');

    $rows = Model::listar($matricula, $cpf, $nome, $email, $fone);
    $this->saveUrl();
    return view('aluno-lst', [
      'rows' => $rows,
    ]);
  }

  public function pesquisar(int $matricula)
  {
    $row = Model::pesquisar($matricula);
    if (!$row)
      return abort(404);
    return $row;
  }

  public function criar()
  {
    $row = new Model();
    return view('aluno-frm', [
      'canEdit' => true,
      'row' => $row
    ]);
  }

  public function ver(Model $row)
  {
    return view('aluno-frm', [
      'canEdit' => false,
      'row' => $row
    ]);
  }

  public function editar(Model $row)
  {
    return view('aluno-frm', [
      'canEdit' => true,
      'row' => $row
    ]);
  }

  public function remover(int $id)
  {
    $row = (new Model())->find($id);
    $row->delete();
    return redirect(session('Url'));
  }

  public function salvar(Request $request)
  {
    $row = $this->validate($request, [
      'id' => 'nullable',
      'user_id' => 'nullable',
      'matricula' => 'required',
      'cpf' => 'required',
      'nome' => 'required',
      'email' => 'required',
      'fone' => 'nullable',
      'cep_id' => 'nullable',
      'numero' => 'nullable',
      'cep' => 'nullable',
      'endereco' => 'nullable',
      'bairro' => 'nullable',
      'cidade' => 'nullable',
      'uf' => 'nullable',
      'manual' => 'nullable',
    ]);

    $row->fone = Lib::numericOnly($row->fone);
    $row->matricula = Lib::numericOnly($row->matricula);
    $row->cpf = Lib::numericOnly($row->cpf);

    Model::persist($row);

    return redirect('/alunos');
  }
}
