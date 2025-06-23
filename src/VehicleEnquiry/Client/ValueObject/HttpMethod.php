<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry\Client\ValueObject;

enum HttpMethod: string
{
    case GET = 'GET';

    case POST = 'POST';

    case PUT = 'PUT';

    case PATCH = 'PATCH';

    case DELETE = 'DELETE';
}
