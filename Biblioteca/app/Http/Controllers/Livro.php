<?php

namespace App\Http\Controllers;

use App\Models as Models;
use Illuminate\Http\Request;
use App\Models\Livro as Model;
use Illuminate\Support\Facades\Auth;

class Livro extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function listar(Request $request)
  {
    $titulo = $request->input('titulo');
    $autor = $request->input('autor');
    $editora = $request->input('editora');
    $situacao = $request->input('situacao');
    $order = $request->input('order');

    $rows = Model::listar($titulo, $autor, $editora, $situacao, $order);
    $this->saveUrl();
    return view('livro-lst', [
      'rows' => $rows,
      'user' => Auth::user(),
    ]);
  }

  public function criar()
  {
    if (!Auth::user()->is_admin) return abort(401);

    $row = new Model();
    return view('livro-frm', [
      'row' => $row,
      'canEdit' => true,
      'user' => Auth::user(),
    ]);
  }

  public function ver(Model $row)
  {
    return view('livro-frm', [
      'row' => $row,
      'canEdit' => false,
      'user' => Auth::user(),
    ]);
  }

  public function editar(Model $row)
  {
    if (!Auth::user()->is_admin) return abort(401);

    return view('livro-frm', [
      'row' => $row,
      'canEdit' => true,
      'user' => Auth::user(),
    ]);
  }

  public function remover(int $id)
  {
    if (!Auth::user()->is_admin) return abort(401);

    $row = (new Model())->find($id);
    $row->delete();
    return redirect(session('Url'));
  }
  /*
  public function remover(Request $request)
  {
    $id = (int)$request->id; //Request::isMethod('post')
    $row = (new Model())->find($id);
    $row->delete();
    return redirect('/livros');
  }
*/

  public function salvar(Request $request)
  {
    if (!Auth::user()->is_admin) return abort(401);

    $row = $this->validate($request, [
      'id' => 'nullable',
      'isbn' => 'required',
      'titulo' => 'required',
      'autor' => 'required',
      'editora' => 'required',
      'is_disponivel' => 'nullable',
    ]);

    Model::persist($row);

    return redirect(session('Url'));
  }
}
