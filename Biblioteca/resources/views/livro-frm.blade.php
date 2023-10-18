@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12 col-lg-7">
      <div class="card">
        <div class="card-header">Livros: {{ $row?->titulo ?? ' Novo registro' }} <a href="javascript:history.back()" class="link-dark right" title="{{ __('Fechar') }}"> <i class="bi bi-x-lg font-weight-bold_"></i></a></div>
        <div class="card-body">

          <div class="container">
            <form method="POST" action="/livros">
              @if ($canEdit)
              @csrf
              @endif

              <input type="hidden" id="id" name="id" value="{{ $row?->id }}">
              <div class="row mx-auto">

                @if ($row?->id)
                <div class="col-md-12 mb-3 mt-2">
                  @if ($row?->situacao === 'Emprestado')
                  <span class="text-danger h5">⬤</span> <span class="h5">{{ $row?->situacao }} <a href="/alunos/ver/{{ $row?->emprestimos[0]?->aluno?->id }}"><small>{{ $user->is_admin? '  ('. $row?->emprestimos[0]?->aluno?->nome .')' : '' }}</small></a></span>
                  @elseif ($row?->situacao === 'Reservado')
                  <span class="text-warning h5">⬤</span> <span class="h5">{{ $row?->situacao }} <a href="/alunos/ver/{{ $row?->reservas[0]?->aluno?->id }}"><small>{{ $user->is_admin?  '  ('. $row?->reservas[0]?->aluno?->nome .')' : ''  }}</small></a></span>
                  @else
                  <span class="text-success h5">⬤</span> <span class="h5">{{ $row?->situacao }}</span>
                  @endif
                </div>
                @endif

                <div class="col-md-12 mb-3">
                  <label>{{ __('Titulo') }}*
                    <input type="text" id="titulo" name="titulo" placeholder="{{ __('Titulo') }}" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo') ?? $row?->titulo }}" autocomplete="titulo" {{$canEdit? '' : 'readonly'}} required autofocus>
                  </label>
                  @error('titulo')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label>{{ __('Autor') }}*
                    <input type="text" id="autor" name="autor" placeholder="{{ __('Autor') }}" class="form-control @error('autor') is-invalid @enderror" value="{{ old('autor') ?? $row?->autor }}" autocomplete="autor" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('autor')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label>{{ __('Isbn') }}*
                    <input type="text" id="isbn" name="isbn" placeholder="{{ __('Isbn') }}" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn') ?? $row?->isbn }}" data-mask="999–99–999–9999–9" autocomplete="isbn" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('isbn')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label>{{ __('Editora') }}*
                    <input type="text" id="editora" name="editora" placeholder="{{ __('Editora') }}" class="form-control @error('editora') is-invalid @enderror" value="{{ old('editora') ?? $row?->editora }}" autocomplete="editora" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('editora')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

              </div> <!-- <div class="row"> -->

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

@endsection