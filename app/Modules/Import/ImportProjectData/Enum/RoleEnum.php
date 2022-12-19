<?php

namespace App\Modules\Import\ImportProjectData\Enum;

enum RoleEnum: string
{
    case GENERAL_DIRECTOR = 'Diretor Geral';
    case REGIONAL_DIRECTOR = 'Diretor';
    case SELLER = 'Vendedor';
    case MANAGER = 'Gerente';
}
