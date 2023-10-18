<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests;
  use ValidatesRequests {
    validate as protected validate_trait;
  }

  public function validate(Request $request, array $rules, array $messages = [], array $attributes = []): object
  {
    return (object)$this->validate_trait($request, $rules, $messages, $attributes);
  }

  protected function saveUrl($url = '', int $page = -1): void
  {
    $url = $url ? $url . ($page === -1 ? '' : "?page=$page") : url()->full();
    session(['Url' => $url]);
  }
}
