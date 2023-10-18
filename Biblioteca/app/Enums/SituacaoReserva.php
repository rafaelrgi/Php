<?php

namespace App\Enums;

enum SituacaoReserva: string
{
  case Solicitada = 'Solicitada';
  case Confirmada = 'Confirmada';
  case Paga = 'Paga';
  case Cancelada = 'Cancelada';
}
