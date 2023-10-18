@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">{{ __('Reset Password') }}</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="row mb-3">
              <div class="col-md-12 max-auto">
                <label for="email" class="">{{ __('E-mail') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror

                <br>
                <button type="submit" class="btn btn-primary right">
                  {{ __('Send Password Reset Link') }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection