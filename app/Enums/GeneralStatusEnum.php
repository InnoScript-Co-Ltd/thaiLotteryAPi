<?php

namespace App\Enums;

enum GeneralStatusEnum: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'ACTIVE';
    case BLOCK = 'COMPLETE';
}
