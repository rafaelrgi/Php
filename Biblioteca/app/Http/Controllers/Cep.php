<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cep as Model;
use App\Lib\Lib;

class Cep extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function pesquisar($cep)
  {
    $cep = Lib::numericOnly($cep);
    if (!$cep)
      return abort(400);

    $row = Model::pesquisar($cep);

    if (!$row)
      return abort(404);
    return $row;
  }
}
