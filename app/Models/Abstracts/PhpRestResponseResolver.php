<?php
namespace App\Models\Abstracts;

use App\Models\Abstracts\Contracts\ResponseResolver;
use Psr\Http\Message\ResponseInterface;
use App\Exceptions\RestApi\ExistRestApiException;
use App\Exceptions\RestApi\UnknownRestApiException;
use App\Exceptions\RestApi\NotFoundRestApiException;
use App\Exceptions\RestApi\AuthorizeRestApiException;
use App\Exceptions\RestApi\UndefinedRestApiException;
use App\Exceptions\RestApi\StatusCodeRestApiException;
use App\Exceptions\RestApi\ResponseFormatRestApiException;
use App\Exceptions\RestApi\InvalidParameterRestApiException;

class PhpRestResponseResolver implements ResponseResolver
{
    public function resolve(ResponseInterface $response)
    {
        if (!in_array($response->getStatusCode(), [200, 201, 202, 203, 204, 205, 206])) {
            throw new StatusCodeRestApiException($response->getStatusCode());
        }
        $body = $response->getBody();
        $data = json_decode($body, true);

        return $data['data']??$data;
    }

}
