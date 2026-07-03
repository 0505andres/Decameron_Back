<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(title: 'Decameron API', version: '1.0.0')]
#[OA\Server(url: 'http://localhost:9000')]
final class ApiDocumentation
{
}
