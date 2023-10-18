@extends('layouts.app')

@section('content')
<style>
  a {
    text-decoration: none;
    color: inherit;
  }

  #legenda {
    max-width: 1100px;
    margin: -0.5rem auto 0.1rem;
  }

  table {
    font-size: 12px;
    max-width: 1100px;
    margin: 0 auto;
  }

  table td {
    height: 2.6rem;
    width: 14.28%;
    text-align: left;
    line-height: 55%;
    padding: 8px 0px 0px 8px !important;
  }

  table tr th {
    font-weight: normal;
    color: #fff !important;
    background-color: #04a !important;

  }

  table tr td.bg-light {
    background-color: #eee !important;
  }

  td.text-primary,
  td.text-secondary {
    cursor: pointer;
  }

  h4 {
    display: inline-block;
    color: #04a;
  }

  i.mt-1 {
    display: inline-block;
  }

  .btn-mini {
    padding: 4px 8px;
    margin-bottom: 0.6rem;
  }


  .Emprestado {
    color: #ff4455 !important;
  }

  .Reservado {
    color: #ffbb66 !important;
  }

  .Disponivel {
    cursor: pointer;
  }

  span.Disponivel {
    color: green !important;
  }

  .Disponivel span {
    color: green !important;
    display: inline-block;
    width: 100%;
    text-align: center;
  }

  .bg-danger {
    background-color: #ffc0ce !important;
  }

  .bg-warning {
    background-color: #fff5ba !important;
  }

  .bg-success {
    background-color: #bffcc6 !important;
  }
</style>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><b>Livro: {{ $calendario->titulo }}</b></div>

        <div class="card-body text-center">
          <br>
          <input type="hidden" id="livro_id" name="livro_id" value="{{ $calendario->livro }}">
          <input type="hidden" id="mes" name="mes" value="{{ $calendario->mes }}">
          <input type="hidden" id="ano" name="ano" value="{{ $calendario->ano }}">

          <a href="/reservas/reservas/{{ $calendario->livro }}/{{ $calendario->mes-1 }}/{{ $calendario->ano}}" id="mes-menos" class="btn btn-light btn-mini  {{ $calendario->atual? 'disabled' : '' }}" title="Mês anterior" disabled><i class="bi bi-caret-left-fill"></i></a>
          <h4 class=''>{{ ucfirst(strftime('%B %Y', $calendario->data)) }}</h4>
          <a href="/reservas/reservas/{{ $calendario->livro }}/{{ $calendario->mes+1 }}/{{ $calendario->ano }}" id="mes-mais" class="btn btn-light btn-mini {{ $calendario->mesMax == $calendario->mes? 'disabled' : '' }}" title="Próximo mês"><i class="bi bi-caret-right-fill"></i></a>

          <table class="table table-bordered">
            <tr>
              <th>Segunda</th>
              <th>Terça</th>
              <th>Quarta</th>
              <th>Quinta</th>
              <th>Sexta</th>
              <th>Sábado</th>
              <th>Domingo</th>
            </tr>
            <!-- {{-- Calendário --}} -->
            @php($col = ($calendario->dia_semana_1 + 6) % 7 )
            <!-- {{-- Células vazias antes do dia 1º --}} -->
            <tr>
              @for ($i=0; $i<$col; $i++) <!-- {{-- Dias do mês --}} -->
                <td class="bg-light">&nbsp;</td>
                @endfor
                <!-- Dias do mês --}} -->
                @for ($i=1; $i<=$calendario->dias_no_mes; $i++, $col++)
                  @if ($col === 7)
                  @php($col = 0)
            </tr>
            <tr>
              @endif
              <!-- {{-- Fim de semana --}} -->
              @if ($col === 5 || $col === 6)
              <td class="bg-light">
                {{ $i }}
              </td>
              <!-- {{-- Emprestado --}} -->
              @elseif ($calendario->dias[$i] === 'E')
              <td class="bg-danger">
                {{ $i }}
              </td>
              <!-- {{-- Reservado --}} -->
              @elseif ($calendario->dias[$i] === 'R')
              <td class="bg-warning">
                {{ $i }}
              </td>
              <!-- {{-- Disponível --}} -->
              @elseif ($calendario->dias[$i] === 'D')
              <td class="Disponivel" data-dia='{{ $i }}'>
                {{ $i }} <br>
                <span> Reservar
                  <i class="bi bi-plus float-end h4 mb-0"></i>
                </span>
              </td>
              <!-- {{-- ???? --}} -->
              @else
              <td class="bg-light">
                {{ $i }}
              </td>
              @endif
              @endfor
              <!-- </tr>
            <tr> -->
              <!-- {{-- Células vazias depois do úlitmo dia --}} -->
              @for ($i=$col+1; $i <= 7; $i++) <td class="bg-light">&nbsp; </td> @endfor
            </tr>
          </table>
          <div id="legenda" class="text-end">
            <small>
              <span class="Emprestado">⬤</span> <small>Emprestado &emsp;</small>
              <span class="Reservado">⬤</span> <small>Reservado &emsp;</small>
              <span class="Disponivel">⬤</span> <small>Disponível &emsp;</small>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $("td.Disponivel").click((evt) => {
    let dia = $(evt.currentTarget).attr("data-dia");
    let mes = $("#mes").val();
    let ano = $("#ano").val();
    let livro = $("#livro_id").val();
    window.location = `/reservas/reservar/${livro}/${dia}/${mes}/${ano}`;
  });
</script>

@endsection