<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Ctrl;

Auth::routes([
  'register' => false, // Registration Routes...
  'verify' => false, // Email Verification Routes...
  'reset' => false, // Password Reset Routes...
]);


//---------------------------------------------
//     Biblioteca
Route::get('/', fn () => redirect('/livros'));
Route::get('/home', fn () => redirect('/livros'));

//---------------------------------------------
//     Cadastro de Usuários
Route::prefix('usuarios')->group(function () {
  Route::get('/', [Ctrl\User::class, 'listar']);
  Route::get('/novo', [Ctrl\User::class, 'criar']);
  Route::get('/ver/{row}', [Ctrl\User::class, 'ver']);
  Route::get('/editar/{row}', [Ctrl\User::class, 'editar']);
  Route::get('/bloquear/{id}', [Ctrl\User::class, 'bloquear']);
  Route::get('/desbloquear/{id}', [Ctrl\User::class, 'desbloquear']);
  Route::post('/', [Ctrl\User::class, 'salvar']);
});

//---------------------------------------------
//     Cep
Route::get('/cep/{cep}', [Ctrl\Cep::class, 'pesquisar']);

//---------------------------------------------
//     Cadastro de Alunos
Route::prefix('alunos')->group(function () {
  Route::get('/', [Ctrl\Aluno::class, 'listar']);
  Route::get('/pesquisar/{matricula}', [Ctrl\Aluno::class, 'pesquisar']);
  Route::get('/novo', [Ctrl\Aluno::class, 'criar']);
  Route::get('/ver/{row}', [Ctrl\Aluno::class, 'ver']);
  Route::get('/editar/{row}', [Ctrl\Aluno::class, 'editar']);
  Route::get('/remover/{id}', [Ctrl\Aluno::class, 'remover']);
  Route::post('/', [Ctrl\Aluno::class, 'salvar']);
});

//---------------------------------------------
//     Cadastro de Livros
Route::prefix('livros')->group(function () {
  Route::get('/', [Ctrl\Livro::class, 'listar']);
  Route::get('/novo', [Ctrl\Livro::class, 'criar']);
  Route::get('/ver/{row}', [Ctrl\Livro::class, 'ver']);
  Route::get('/editar/{row}', [Ctrl\Livro::class, 'editar']);
  Route::get('/remover/{id}', [Ctrl\Livro::class, 'remover']);
  Route::post('/', [Ctrl\Livro::class, 'salvar']);
});

//---------------------------------------------
//     Emprestimo de Livros
Route::prefix('emprestimos')->group(function () {
  Route::get('/retirar/{livro}', [Ctrl\Emprestimo::class, 'retirar']);
  Route::get('/prorrogar/{emprestimo}', [Ctrl\Emprestimo::class, 'prorrogar']);
  Route::get('/devolver/{emprestimo}', [Ctrl\Emprestimo::class, 'devolver']);
  Route::get('/ver/{row}', [Ctrl\Emprestimo::class, 'ver']);
  Route::post('/', [Ctrl\Emprestimo::class, 'salvar']);
});

//---------------------------------------------
//     Reservas de Livros
Route::prefix('reservas')->group(function () {
  Route::get('/reservas/{livro}/{mes?}/{ano?}', [Ctrl\Reserva::class, 'reservas']);
  Route::get('/reservar/{livro}/{dia}/{mes}/{ano}', [Ctrl\Reserva::class, 'reservar']);
  Route::get('/retirar/{reserva}', [Ctrl\Reserva::class, 'retirar']);
  Route::get('/cancelar/{reserva}', [Ctrl\Reserva::class, 'cancelar']);
  Route::post('/', [Ctrl\Reserva::class, 'salvar']);
});
