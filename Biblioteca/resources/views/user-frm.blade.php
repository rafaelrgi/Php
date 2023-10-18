@extends('layouts.app')

@section('content')
<div class="container d-none">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
      <div class="card">
        <div class="card-header">Usuário: {{ $row?->name ?? 'Novo registro' }} <a href="javascript:history.back()" class="link-dark right" title="{{ __('Fechar') }}"> <i class="bi bi-x-lg font-weight-bold_"></i></a></div>
        <div class="card-body">

          <div class="container">
            <form method="POST" action="/usuarios">
              @if ($canEdit)
              @csrf
              @endif

              <input type="hidden" id="id" name="id" value="{{ $row?->id }}">
              <input type="hidden" id="isAdmin" value="{{ $row?->is_admin }}">

              <div class="row mx-auto">
                <div class="text-end">
                  <span class="border p-1">
                    Perfil: {{ $row?->is_admin? 'Administrador' : 'Aluno' }}
                  </span>
                </div>
                <br>

                <div class=" col-md-12 mb-3">
                  <label>{{ __('E-mail') }}*
                    <input type="email" id="email" name="email" placeholder="{{ __('E-mail') }}" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') ?? $row?->email }}" autocomplete="e-mail" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label>{{ __('Nome de usuário') }}
                    @if ($row?->is_admin) (E-mail)
                    @else
                    (Matrícula)
                    @endif
                    <input type="text" id="login" name="login" placeholder="{{ __('Nome de usuário') }}" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') ?? $row?->login }}" autocomplete="matrícula" readonly>
                  </label>
                </div>

                <div class="col-md-12 mb-3">
                  <label>{{ __('Nome') }}*
                    <input type="text" id="name" name="name" placeholder="{{ __('Nome') }}" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $row?->name }}" autocomplete="nome" {{$canEdit? '' : 'readonly'}} required>
                  </label>
                  @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <?php if ($canChangePwd) : ?>
                  <div class="col-md-12 mb-3">
                    <div class="form-check">
                      <input type="checkbox" id="altera-senha" class="form-check-input" {{ $row?->id ? '' : 'checked'  }}>
                      <label class=" form-check-label" for="altera-senha">{{ $row?->id ? 'Alterar' : 'Informar'  }} senha</label>
                    </div>
                    <label id="frm-senha" class="{{ $row?->id ? 'd-none' : ''  }} ">Senha*
                      <input type="password" id="password" name="password" placeholder="Digite a senha" class="form-control mb-2" autocomplete="off" {{$canEdit? '' : 'readonly'}} minlength="4">
                      <input type="password" id="password2" name="password2" placeholder="Confirme a senha" class="form-control" autocomplete="off" {{$canEdit? '' : 'readonly'}} minlength="4">
                    </label>
                  </div>
                <?php endif; ?>

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
  $("#altera-senha").click(() => {
    $("#frm-senha").toggleClass("d-none");
    let onOff = $("#altera-senha").prop("checked");
    $("#senha").prop("required", onOff);
    $("#senha2").prop("required", onOff);
  });

  $('form').submit((evt) => {
    $("#password,#password2").removeClass("is-invalid");
    if (!$("#altera-senha").prop("checked"))
      return;
    if ($("#password").val() === $("#password2").val())
      return;

    evt.preventDefault();
    $("#password,#password2").addClass("is-invalid");
    avisa("As senhas devem ser iguais!");
    $("#password").focus();
  });

  $("#email").on('input', () => {
    if ($("#isAdmin").val() != "1")
      return;
    $("#login").val($("#email").val());
  });
</script>

@endsection