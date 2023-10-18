<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Rgi Sistemas {{ session('App')? ' :: '. session('App')->nome : ''; }}</title>

  <!-- Scripts -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body>
  <div id="app">
    @auth
    <nav class="navbar navbar-expand-md bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="{{ session('App')?->url; }}">
          <i class="bi bi-book-half" style="color: purple"></i>&nbsp; Biblioteca
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ms-auto">
            @includeIf('layouts.menus.' . 'biblioteca')

            <!-- Authentication Links -->
            @guest
            @if (Route::has('login'))
            <li class="nav-item login">
              <a class="nav-link" href="{{ route('login') }}"> <i class="bi bi-box-arrow-in-right"></i>&nbsp; {{ __('Login') }}</a>
            </li>
            @endif

            @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link register" href="{{ route('register') }}">{{ __('Registre-se') }}</a>
            </li>
            @endif
            @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="bi bi-person-circle"></i>&nbsp; {{ auth()->user()->name }}
              </a>

              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="/usuarios/editar/{{ auth()->user()->id }}">&nbsp; Meus dados</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>&nbsp; {{ __('Sair') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                </li>
              </ul>
            </li>

            @endguest
          </ul>
        </div>
      </div>
    </nav>
    @else
    <br><br><br>
    @endauth

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.3/jquery.inputmask.bundle.min.js"></script>

    <main class="py-4">
      @yield('content')
    </main>


    <!-- FOOTER -->
    <div id="loading" class="modal">
      <div class="spinner-border text-info" role="status"></div>
    </div>

    <script type="text/javascript" src="/js/app.js?vrs=0.4" defer></script>

    @if (session('Message'))
    <div id="toast">
      <?= session('Message'); ?>
    </div>

    <script>
      document.getElementById("toast").classList.add("toast-in");
      setTimeout(() => {
        document.getElementById("toast").classList.remove("toast-in");
        document.getElementById("toast").classList.add("toast-out");
      }, 5000);
    </script>
    @endif


    <div id="dlg" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <b id="dlg-title" class="modal-title">Modal title</b>
            <a href="#" class="link-dark right" data-dismiss="modal" title="Fechar">
              <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
            </a>
          </div>
          <div id="dlg-body" class="modal-body"></div>
          <div id="dlg-footer" class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button> &nbsp;
            <button type="button" class="btn btn-primary">OK</button>
          </div>
        </div>
      </div>
    </div>

</body>

</html>