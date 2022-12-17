<?php

namespace App\Modules\Import\ImportProjectData\Enum;

enum RoleEnum: string
{
    case DIRETOR_GERAL = 'Diretor Geral';
    case DIRETOR_REGIONAL = 'Nome Diretoria';
    case VENDEDOR = 'Vendedor';
    case GERENTE = 'Gerente';
}
