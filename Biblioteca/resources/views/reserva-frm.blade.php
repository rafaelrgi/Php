@extends('layouts.app')

@section('content')
<div class="container d-none">
  <div class="row justify-content-center">
    <div class="col-md-12 col-lg-6">
      <div class="card">
        <div class="card-header">{{ __('Reservar') }}: {{ $row?->titulo ?? 'Novo registro' }} <a href="javascript:history.back()" class="link-dark right" title="{{ __('Fechar') }}"> <i class="bi bi-x-lg font-weight-bold_"></i></a></div>
        <div class="card-body">

          <div class="container">
            <form method="POST" action="/reservas">
              @if ($canEdit)
              @csrf
              @endif

              <input type="hidden" id="id" name="id" value="{{ $row?->id }}">
              <input type="hidden" id="livro_id" name="livro_id" value="{{ $row?->livro_id }}">
              <input type="hidden" id="aluno_id" name="aluno_id" value="{{ $row?->aluno_id ?? $row?->aluno?->id }}">

              <div class="row mx-auto">
                <div class="col-md-4 mb-3">
                  <label>{{ __('Data') }}
                    <input type="text" id="data" name="data" class="form-control" value="{{ old('data') ?? $row?->data->format('d/m/Y') }}" readonly>
                  </label>
                  @error('data')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-8 mb-3">
                  <label>{{ __('Livro') }}
                    <input type="text" id="titulo" name="titulo" class="form-control" value="{{ old('titulo') ?? $row?->titulo }}" readonly>
                  </label>
                  @error('livro_id')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <!-- Admin -->
                @if (Auth::user()->is_admin)
                <div class="col-md-4 mb-3">
                  <label>{{ __('Aluno') }}
                    <div class="input-group mb-0">
                      <input type="text" id="matricula" name="matricula" placeholder="{{ __('Matrícula') }}" class="form-control @error('aluno_id') is-invalid @enderror" value="{{ old('matricula') ?? $row?->aluno?->matricula }}" autocomplete="off" required {{ $row?->aluno?->id? 'readonly' : 'autofocus' }}>
                      <div class="input-group-append">
                        <a href="#" class="input-group-text btn btn-light btn-mini {{ $row?->aluno?->id? 'disabled' : '' }}" id="aluno-search" title="Pesquisar aluno"><i class="bi bi-search"></i></a>
                      </div>
                    </div>
                    @error('aluno_id')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </label>
                </div>
                <div class="col-md-8 mb-3">
                  <label>&nbsp;
                    <input type="text" id="nome" name="nome" placeholder="{{ __('Nome') }}" class="form-control" value="{{ old('nome') ?? $row?->aluno?->nome }}" readonly>
                  </label>
                </div>
                @endif
              </div> <!-- <div class="row"> -->

              <br>
              <hr />
              <div class="text-center">
                @if ($canEdit)
                <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                &emsp;&emsp;&emsp;
                @endif
                <!-- <a href="{{ session('Url') }}" class="btn btn-light">{{ __('Fechar') }}</a> -->
                <a href="javascript:history.back()" class="btn btn-light">{{ __('Fechar') }}</a>
              </div>
            </form>
          </div> <!-- <div class="container"> -->

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // ---------------------------------------------------------
  //    matricula
  $("#matricula").on("input", (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
  });
  // ---------------------------------------------------------
  // Busca Aluno
  $("#aluno-search").click(() => {
    if (!$("#matricula").val())
      return;

    let matricula = $("#matricula").val().replace(/\D/g, "");
    $.get(`/alunos/pesquisar/${matricula}`)
      .done((aluno) => {
        $("#aluno_id").val(aluno.id);
        $("#matricula").val(aluno.matricula);
        $("#nome").val(aluno.nome);
      })
      .fail((jqXHR, textStatus, errorThrown) => {
        avisa("Matrícula não encontrada!");
        $("#aluno_id,#nome").val("");
        $("#matricula").focus();
      });
  });
</script>

@endsection