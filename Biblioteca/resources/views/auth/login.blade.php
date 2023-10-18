@extends('layouts.app')

<style>
  .login {
    display: none !important;
  }
</style>

@section('content')
@if (! session('is_local'))
<!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
@endif

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
      <div class="card mt-5">
        <div class="card-header text-center"><i class="bi bi-book-half" style="color: purple"></i>&nbsp; {{ __('Biblioteca') }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('login') }}" class="mb-0">
            @csrf

            <div class="row mb-3">
              <div class="col-md-12 mx-auto">
                <label for="email">{{ __('Usuário') }}
                  <input id="login" type="text" placeholder="{{ __('Usuário') }}" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login', config('app.debug')? 'Admin' : '') }}" required autocomplete="login" autofocus>
                  @error('login')
                  <span class="invalid-feedback" role="alert">
                    Não foi possível acessar o sistema, verifique usuário e senha informados!
                  </span>
                  @enderror
                </label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-12 mx-auto">
                <label for="password">{{ __('Senha') }}
                  <input id="password" type="password" placeholder="{{ __('Senha') }}" class="form-control @error('login') is-invalid @enderror" name="password" required autocomplete="off" value="{{ config('app.debug')? '1234' : ''  }}">
                  @error('login')
                  <span class="invalid-feedback" role="alert">
                    Não foi possível acessar o sistema, verifique usuário e senha informados!
                  </span>
                  @enderror
                </label>
              </div>

              <!--
              <div class="row mb-0">
                <div class="col-md-4 offset-md-8">
                  <div class="form-check right">
                    <br>
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                      {{ __('Remember Me') }}
                    </label>
                  </div>
                </div>
              </div>
            -->

              <div class="col-md-11 mx-auto pr-0 mb-0 text-center">
                @if (! session('is_local'))
                <!-- <div class="g-recaptcha" data-sitekey="6Lf_EB4oAAAAAOGltfXVA_ksL2_IZQBJxD0rkkpK"></div> -->
                @endif
                <br>
                <button type="submit" class="btn btn-primary">
                  {{ __('Entrar') }}
                </button>

                @if (Route::has('password.request'))
                <a class="btn btn-link right" href="{{ route('password.request') }}">
                  {{ __('Esqueci a senha') }}
                </a>
                @endif
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection