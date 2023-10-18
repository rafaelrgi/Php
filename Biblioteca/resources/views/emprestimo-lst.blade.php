@extends('layouts.app')

@section('content')
<div class="container d-none">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Emprestimos') }}</div>

        <div class="card-body">
          @if (! count($rows))
          <div class="text-center">
            <br>
            <h3>Nenhum registro encontrado!</h3>
            <br>
          </div>
          @endif

          <a href="/emprestimos/novo" class="btn btn-light mb-2">
            <i class="bi bi-plus"></i>&nbsp; Novo
          </a>

          <div class="table-responsive">
            <table class="table table-hover table-bordered {{ count($rows)? '' : 'd-none' }}">
              <thead>
                <tr>
                  <th scope="col">&nbsp;</th>
                  <th scope="col">LivroId</th>
                  <th scope="col">AlunoId</th>
                  <th scope="col">DtRetirada</th>
                  <th scope="col">DtDevolucao</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rows as $row)
                <tr>

                  <td width="1%">
                    <a href="/emprestimos/ver/{{ $row->id }}" class="btn btn-circle btn-success mb-0" title="Visualizar">
                      <i class="bi bi-eye"></i>
                    </a>
                    &emsp;
                    <a href="/emprestimos/editar/{{ $row->id }}" class="btn btn-circle btn-warning mb-0" title="Editar">
                      <i class="bi bi-pencil"></i>
                    </a>
                    &emsp;
                    <a href="#" class="btn btn-circle btn-danger mb-0" title="Excluir" onclick="confirma('Excluir o registro?', ()=>window.location='/emprestimos/remover/{{ $row->id }}')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>

                  <!-- <td><span data-mask="(##) #####-####">{{ $row->fone }}</span></td> -->
                  <td>{{ $row?->livro_id }}</td>
                  <td>{{ $row?->aluno_id }}</td>
                  <td>{{ $row?->dt_retirada }}</td>
                  <td>{{ $row?->dt_devolucao }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="text-center">
            <br>
            {{ $rows->links() }}
            <br>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection