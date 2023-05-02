<?php

declare(strict_types=1);

namespace App\Model;

enum ImportState: int
{
    case NotImported = 0;
    case Importing = 1;
    case Ready = 2;
}
