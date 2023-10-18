<?php

namespace App\Http\Controllers;

use App\Lib\Lib;
use Illuminate\Http\Request;
use App\Models\User as Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

class User extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function listar(Request $request)
  {
    if (!Auth::user()->is_admin) return abort(401);

    $order = $request->input('order');
    $order = match ($order) {
      'nome' => 'name',
      'usuario' => 'login',
      'perfil' => 'is_admin',
      'bloqueado' => 'deleted_at',
      default => $order
    };

    $rows = Model::listar($order);
    $this->saveUrl();
    return view('user-lst', [
      'rows' => $rows,
      'user' => Auth::user(),
    ]);
  }

  public function bloquear(int $id)
  {
    if (!Auth::user()->is_admin || Auth::user()->id === $id) return abort(401);

    Model::bloquear($id);
    return redirect(session('Url'));
  }

  public function desbloquear(int $id)
  {
    if (!Auth::user()->is_admin || Auth::user()->id === $id) return abort(401);

    Model::desbloquear($id);
    return redirect(session('Url'));
  }

  public function criar()
  {
    if (!Auth::user()->is_admin) return abort(401);

    $row = Model::getModel();
    return view('user-frm', [
      'row' => $row,
      'canEdit' => true,
      'canChangePwd' => true, //inicial OK :: ~~Só pode alterar a própria senha~~
    ]);
  }

  public function ver(Model $row)
  {
    if (!Auth::user()->is_admin && Auth::user()->id != $row->id) return abort(401);

    return view('user-frm', [
      'row' => $row,
      'canEdit' => false,
    ]);
  }

  public function editar(Model $row)
  {
    if (!Auth::user()->is_admin && Auth::user()->id != $row->id) return abort(401);

    return view('user-frm', [
      'row' => $row,
      'canEdit' => true,
      //Só pode alterar a própria senha, ou admin alterar senha de alunos
      'canChangePwd' => (Auth::user()->id == $row->id || !$row->is_admin),
    ]);
  }


  public function salvar(Request $request)
  {
    $id = $request->input('id');
    if (!Auth::user()->is_admin && Auth::user()->id != $id) return abort(401);

    $row = $this->validate($request, [
      'id' => 'nullable',
      'email' => 'required',
      'login' => 'required',
      'name' => 'required',
      'password' => 'nullable',
      'password2' => 'nullable',
      'is_admin' => 'nullable',
    ]);

    $row->password = trim($row->password);
    $row->password2 = trim($row->password2);
    if ($row->password && $row->password !== $row->password2)
      return abort(400, 'As senhas não conferem');
    unset($row->password2);

    try {
      Model::persist($row);
    } catch (\Throwable $th) {
      return abort($th->getCode() ?: 500, $th->getMessage());
    }

    return redirect(session('Url'));
  }

  /*
  public function remover(int $id)
  {
    if (!Auth::user()->is_admin) return abort(401);

    $row = (new Model())->find($id);
    $row->delete();
    return redirect(session('Url'));
  }
  */
}
