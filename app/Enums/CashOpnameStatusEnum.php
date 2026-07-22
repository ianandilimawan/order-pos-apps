<?php

namespace App\Enums;

enum CashOpnameStatusEnum: string
{
    case Matched = 'matched';
    case Shortage = 'shortage';
    case Overage = 'overage';
}
