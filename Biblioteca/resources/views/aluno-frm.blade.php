@extends('layouts.app')

@section('content')
<div class="container d-none">
  <div class="row justify-content-center">
    <div class="col-md-12 col-lg-8">
      <div class="card">
        <div class="card-header">Alunos: {{ $row?->nome ?? 'Novo registro' }} <a href="javascript:history.back()" class="link-dark right" title="{{ __('Fechar') }}"> <i class="bi bi-x-lg font-weight-bold_"></i></a></div>
        <div class="card-body">

          <div class="container">
            <form method="POST" action="/alunos">
              @if ($canEdit)
              @csrf
              @endif

              <input type="hidden" id="id" name="id" value="{{ $row?->id }}">
              <div class="row mx-auto">
                <div class="col-md-6 mb-3">
                  <label>{{ __('Matrícula') }}*
                    <input type="text" id="matricula" name="matricula" placeholder="{{ __('Matrícula') }}" class="form-control @error('matricula') is-invalid @enderror" value="{{ old('matricula') ?? $row?->matricula }}" autocomplete="matrícula" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('matricula')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label>{{ __('Cpf') }}*
                    <input type="text" id="cpf" name="cpf" placeholder="{{ __('Cpf') }}" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf') ?? $row?->cpf }}" data-mask="999.999.999-99" autocomplete="cpf" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('cpf')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label>{{ __('Nome') }}*
                    <input type="text" id="nome" name="nome" placeholder="{{ __('Nome') }}" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') ?? $row?->nome }}" autocomplete="nome" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('nome')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label>{{ __('E-mail') }}*
                    <input type="email" id="email" name="email" placeholder="{{ __('E-mail') }}" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') ?? $row?->email }}" autocomplete="e-mail" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label>{{ __('Fone') }}
                    <input type="text" id="fone" name="fone" placeholder="{{ __('Fone') }}" class="form-control @error('fone') is-invalid @enderror" value="{{ old('fone') ?? $row?->fone }}" data-mask="(99) 99999-9999" {{$canEdit? '' : 'readonly'}} autocomplete="telefone">
                  </label>
                  @error('fone')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-12 mx-auto px-4">
                  <fieldset class="row border rounded-3 px-1">
                    <legend class="float-none w-auto px-3">Endereço</legend>
                    <div class="col-md-5 mb-3">
                      <label>{{ __('Cep') }}
                        <input type="hidden" id="cep_id" name="cep_id" value="{{ $row->cep?->id }}">
                        <input type="hidden" id="manual" name="manual" value="{{ $row->cep?->manual }}">
                        <div class="input-group mb-0">
                          <input type="text" id="cep" name="cep" placeholder="{{ __('Cep') }}" class="form-control @error('cep') is-invalid @enderror" value="{{ old('cep') ?? $row->cep?->cep }}" data-mask="99.999-999" {{$canEdit? '' : 'readonly'}} autocomplete="cep">
                          <div class="input-group-append">
                            <a href="#" class="input-group-text btn btn-light btn-mini" id="cep-search"><i class="bi bi-search"></i></a>
                          </div>
                        </div>
                      </label>
                      @error('cep')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                      <label>{{ __('Número') }}
                        <input type="text" id="numero" name="numero" placeholder="{{ __('Número') }}" class="form-control @error('numero') is-invalid @enderror" value="{{ old('numero') ?? $row->numero }}" autocomplete="off" {{$canEdit? '' : 'readonly'}} maxlength="16">
                      </label>
                      @error('numero')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>

                    <div class="col-md-5 mb-3">
                      <label>{{ __('Endereço (rua, avenida, etc)') }}
                        <input type="text" id="endereco" name="endereco" placeholder="{{ __('Endereço (rua, avenida, etc)') }}" class="form-control @error('endereco') is-invalid @enderror" value="{{ old('endereco') ?? $row->cep?->endereco }}" autocomplete="endereco" maxlength="64" readonly>
                      </label>
                      @error('endereco')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>

                    <div class="col-md-5 mb-3">
                      <label>{{ __('Bairro') }}
                        <input type="text" id="bairro" name="bairro" placeholder="{{ __('Bairro') }}" class="form-control @error('bairro') is-invalid @enderror" value="{{ old('bairro') ?? $row->cep?->bairro }}" autocomplete="bairro" maxlength="64" readonly>
                      </label>
                      @error('bairro')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>

                    <div class="col-md-5 mb-3">
                      <label>{{ __('Cidade') }}
                        <input type="text" id="cidade" name="cidade" placeholder="{{ __('Cidade') }}" class="form-control @error('cidade') is-invalid @enderror" value="{{ old('cidade') ?? $row->cep?->cidade }}" autocomplete="cidade" maxlength="64" readonly>
                      </label>
                      @error('cidade')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                      <label>{{ __('Uf') }}
                        <input type="text" id="uf" name="uf" placeholder="{{ __('Uf') }}" class="form-control @error('uf') is-invalid @enderror" value="{{ old('uf') ?? $row->cep?->uf }}" autocomplete="uf" maxlength="2" readonly>
                      </label>
                      @error('uf')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </fieldset>
                </div>


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

<!-- TODO: arquivo JS -->
<script>
  // ---------------------------------------------------------
  //    matricula
  $("#matricula").on("input", (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
  });
  // ---------------------------------------------------------
  //    cep search
  $("#cep-search").click(() => {
    if (!$("#cep").val()) {
      $("#cep_id, #numero, #address, #bairro, #cidade, #uf").val("").prop('readonly', false);
      return;
    }

    let code = $("#cep").val().replace(/\D/g, "");

    $.get(`/cep/${code}`)
      .done((cep) => {
        $("#endereco, #bairro, #cidade, #uf").prop('readonly', true);

        $("#numero").val("");
        $("#cep_id").val(cep.id);
        $("#cep").val(cep.cep);
        $("#endereco").val(cep.endereco);
        $("#bairro").val(cep.bairro);
        $("#cidade").val(cep.cidade);
        $("#uf").val(cep.uf);
        $("#manual").val(cep.manual);

        $("#numero").focus();
      })
      .fail((jqXHR, textStatus, errorThrown) => {
        avisa("Cep não encontrado!");
        $("#cep_id, #numero, #endereco, #bairro, #cidade, #uf").val("").prop('readonly', false);
        $("#manual").val("1");
        $("#numero").focus();
      });
  });
  // ---------------------------------------------------------
</script>

@endsection