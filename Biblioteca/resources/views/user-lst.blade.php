@extends('layouts.app')

@section('content')
<div class="container d-none">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Usuários') }}</div>

        <div class="card-body">
          @if (! count($rows))
          <div class="text-center">
            <br>
            <h3>Nenhum registro encontrado!</h3>
            <br>
          </div>
          @endif

          <a href="/usuarios/novo" class="btn btn-light mb-2">
            <i class="bi bi-plus"></i>&nbsp; Novo
          </a>

          <div class="table-responsive">
            <table class="table table-hover table-bordered {{ count($rows)? '' : 'd-none' }}">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th data-sort="usuario">Usuário</th>
                  <th data-sort="nome">Nome</th>
                  <th data-sort="email">E-mail</th>
                  <th data-sort="perfil">Perfil</th>
                  <th data-sort="bloqueado" width="1%" class="text-nowrap">Bloqueado?</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rows as $row)
                <tr>
                  <td width="1%">
                    <a href="/usuarios/ver/{{ $row->id }}" class="btn btn-circle btn-success mb-0" title="Visualizar">
                      <i class="bi bi-eye"></i>
                    </a>
                    &nbsp;
                    <a href="/usuarios/editar/{{ $row->id }}" class="btn btn-circle btn-warning mb-0" title="Editar">
                      <i class="bi bi-pencil"></i>
                    </a>
                    &nbsp;
                    @if ($row?->is_bloqueado)
                    <a href="#" class="btn btn-circle btn-danger mb-0 {{ $user->id === $row?->id? 'disabled' : '' }}" title="Desbloquear o usuário" onclick="confirma('Desbloquear o usuário?', ()=>window.location='/usuarios/desbloquear/{{ $row->id }}')">
                      <i class="bi bi-unlock"></i>
                    </a>
                    @else
                    <a href="#" class="btn btn-circle btn-danger mb-0 {{ $user->id === $row?->id? 'disabled' : '' }}" title="Bloquear o usuário" onclick="confirma('Bloquear o usuário?', ()=>window.location='/usuarios/bloquear/{{ $row->id }}')">
                      <i class="bi bi-lock"></i>
                    </a>
                    @endif
                  </td>

                  <td>{{ $row?->login }}</td>
                  <td>{{ $row?->name }}</td>
                  <td>{{ $row?->email }}</td>
                  <td>{{ $row?->is_admin? 'Administrador' : 'Aluno' }}</td>
                  <td class='h5'>{{ $row?->is_bloqueado? '☑' : '☐' }}</td>
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

<script type="text/javascript" src="/js/listagem.js?vrs=0.4" defer></script>
@endsection