@extends('layouts.app')

@section('content')
<div class="container d-none">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Livros') }}</div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <form action="/livros" method="GET">
                  <tr class="border-0">
                    <td class="border-0">
                      @if ($user->is_admin)
                      <a href="/livros/novo" class="btn btn-light m-0 pt-1 pb-1"><i class="bi bi-plus-lg"></i>&nbsp; Novo</a>
                      @endif
                    </td>
                    <td class="border-0"><input type="text" class="form-control" name="titulo" placeholder="Filtrar título" value="{{app('request')->input('titulo')}}"></td>
                    <td class="border-0"><input type="text" class="form-control" name="autor" placeholder="Filtrar autor" value="{{app('request')->input('autor')}}"></td>
                    <td class="border-0"><input type="text" class="form-control" name="editora" placeholder="Filtrar editora" value="{{app('request')->input('editora')}}"></td>
                    <td class="border-0"><!-- <input type="text" class="form-control" name="situacao" placeholder="Filtrar situação"> --></td>
                    <td class="border-0 pe-0" width="1%"><button type="submit" class="btn btn-mini btn-light" title="Filtrar"><i class="bi bi-search flip"></i></button></td>
                  </tr>
                </form>

                <tr>
                  <th width="1%">&nbsp;</th>
                  <th width="28%" data-sort="titulo">Título</th>
                  <th width="27%" data-sort="autor">Autor</th>
                  <th width="20%" data-sort="editora">Editora</th>
                  <th width="1%" colspan="2">Situação</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($rows as $row)
                <tr>
                  <td width="1%">
                    <a href="/livros/ver/{{ $row->id }}" class="btn btn-circle btn-success mb-0" title="Visualizar">
                      <i class="bi bi-eye"></i>
                    </a>
                    @if ($user->is_admin)
                    &nbsp;
                    <a href="/livros/editar/{{ $row->id }}" class="btn btn-circle btn-warning mb-0" title="Editar">
                      <i class="bi bi-pencil"></i>
                    </a>
                    &nbsp;
                    <a href="/livros/remover/{{ $row->id }}" data-confirm="Excluir o registro?" class="btn btn-circle btn-danger mb-0" title="Excluir">
                      <i class="bi bi-trash"></i>
                    </a>
                    @endif
                  </td>

                  <td>{{ $row?->titulo }}</td>
                  <td>{{ $row?->autor }}</td>
                  <td>{{ $row?->editora }}</td>
                  <td width="1%" class="text-nowrap" colspan="2">
                    @if ($row?->situacao == 'Emprestado')
                    <span class="text-danger h5">⬤</span> {{ $row?->situacao }}
                    &nbsp; &emsp;
                    @if ($user->is_admin)
                    <a href="/emprestimos/prorrogar/{{ $row?->emprestimos[0]?->id }}" data-confirm="Prorrogar o empréstimo por mais {{ config('app.dias_emprestimo')  }} dias?" class="btn btn-circle btn-secondary mb-0" title="Prorrogar empréstimo">
                      <i class="bi bi-hourglass-top"></i>
                    </a>
                    &nbsp;
                    <a href="/emprestimos/devolver/{{ $row?->emprestimos[0]?->id }}" class="btn btn-circle btn-primary mb-0" title="Devolver livro">
                      <i class="bi bi-download"></i>
                    </a>
                    @endif
                    @elseif ($row?->situacao == 'Reservado')
                    <span class="text-warning h5">⬤</span> {{ $row?->situacao }}
                    &emsp; &emsp;
                    @if ($user->is_admin || $user->id == $row?->reservas[0]?->aluno->user->id)
                    <a href="/reservas/cancelar/{{ $row?->reservas[0]?->id }}')" data-confirm="Cancelar a reserva?" class="btn btn-circle btn-danger mb-0" title="Cancelar reserva">
                      <i class="bi bi-x-lg"></i>
                    </a>
                    @endif
                    @if ($user->is_admin)
                    &nbsp;
                    <a href="/reservas/retirar/{{ $row?->reservas[0]?->id }}" class="btn btn-circle btn-primary mb-0" title="Retirar livro ({{ $row?->reservas[0]?->aluno?->nome }})">
                      <i class="bi bi-upload"></i>
                    </a>
                    @endif
                    @else
                    <span class="text-success h5">⬤</span> {{ $row?->situacao }}
                    &emsp; &emsp;
                    <a href="/reservas/reservas/{{ $row->id }}" class="btn btn-circle btn-info mb-0" title="Reservar livro">
                      <i class="bi bi-calendar-plus"></i>
                    </a>
                    &nbsp;
                    @if ($user->is_admin)
                    <a href="/emprestimos/retirar/{{ $row->id }}" class="btn btn-circle btn-primary mb-0" title="Retirar livro">
                      <i class="bi bi-upload"></i>
                    </a>
                    @endif
                    @endif
                  </td>

                </tr>
                @endforeach
              </tbody>
            </table>

            @if (! count($rows))
            <div id="no-record" class="text-center">
              <br>
              <h3>Nenhum registro encontrado!</h3>
              <br>
            </div>
            @endif
          </div>

          <div class="text-center">
            <br>
            {{ $rows->appends($_GET)->links() }}
            <br>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="/js/listagem.js?vrs=0.4" defer></script>
@endsection