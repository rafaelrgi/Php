@extends('layouts.app')

@section('content')
<div class="container d-none">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Alunos') }}</div>

        <div class="card-body">
          @if (! count($rows))
          <div class="text-center">
            <br>
            <h3>Nenhum registro encontrado!</h3>
            <br>
          </div>
          @endif

          <div class="table-responsive">
            <table class="table table-hover table-bordered {{ count($rows)? '' : 'd-none' }}">
              <thead>
                <form action="/alunos" method="GET">
                  <tr class="border-0">
                    <td class="border-0">
                      <a href="/alunos/novo" class="btn btn-light m-0 pt-1 pb-1"><i class="bi bi-plus-lg"></i>&nbsp; Novo</a>
                    </td>
                    <td class="border-0"><input type="text" class="form-control" name="matricula" value="{{app('request')->input('matricula')}}" placeholder="Filtrar matrícula"></td>
                    <td class="border-0"><input type="text" class="form-control" name="cpf" value="{{app('request')->input('cpf')}}" placeholder="Filtrar cpf"></td>
                    <td class="border-0"><input type="text" class="form-control" name="nome" value="{{app('request')->input('nome')}}" placeholder="Filtrar nome"></td>
                    <td class="border-0"><input type="text" class="form-control" name="email" value="{{app('request')->input('email')}}" placeholder="Filtrar e-mail"> </td>
                    <td class="border-0"><input type="text" class="form-control" name="fone" value="{{app('request')->input('fone')}}" placeholder="Filtrar telefone"> </td>
                    <td class="border-0 pe-0" width="1%"><button type="submit" class="btn btn-mini btn-light" title="Filtrar"><i class="bi bi-search flip"></i></button></td>
                  </tr>
                </form>

                <tr>
                  <th width="1%">&nbsp;</th>
                  <th width="12%">Matrícula</th>
                  <th width="12%">CPF</th>
                  <th width="25%">Nome</th>
                  <th width="23%">Email</th>
                  <th width="18%" colspan="2">Fone</th>
                </tr>

              </thead>
              <tbody>
                @foreach ($rows as $row)
                <tr>
                  <td width="1%">
                    <a href="/alunos/ver/{{ $row->id }}" class="btn btn-circle btn-success mb-0" title="Visualizar">
                      <i class="bi bi-eye"></i>
                    </a>
                    &nbsp;
                    <a href="/alunos/editar/{{ $row->id }}" class="btn btn-circle btn-warning mb-0" title="Editar">
                      <i class="bi bi-pencil"></i>
                    </a>
                    &nbsp;
                    <a href="#" class="btn btn-circle btn-danger mb-0" title="Excluir" onclick="confirma('Excluir o registro?', ()=>window.location='/alunos/remover/{{ $row->id }}')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>

                  <td>{{ $row?->matricula }}</td>
                  <td>{{ $row?->cpf }}</td>
                  <td>{{ $row?->nome }}</td>
                  <td>{{ $row?->email }}</td>
                  <td colspan="2"><span data-mask="(##) #####-####">{{ $row?->fone }}</span></td>
                </tr>
                @endforeach
              </tbody>
            </table>
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
@endsection