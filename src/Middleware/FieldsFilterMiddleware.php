<?php declare(strict_types=1);


namespace App\Middleware;


use Laminas\Diactoros\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FieldsFilterMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $params = $request->getQueryParams();
        if (!isset($params['fields']) || !is_array($params['fields'])) {
            return $response;
        }

        try {
            $body = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (isset($body['data'])) {
                $body['data'] = $this->filter($body['data'], $params['fields']);
            } else {
                $body = $this->filter($body, $params['fields']);
            }

            $stream = new Stream('php://temp', 'wb+');
            $stream->write(json_encode($body));
            $stream->rewind();

            return $response->withBody($stream);
        } catch (\JsonException) {
            $response->getBody()->rewind();

            return $response;
        }
    }

    /**
     * @param array<string|int, mixed> $data
     * @param array<string> $fields
     * @return array<string|int, mixed>
     */
    private function filter(array $data, array $fields): array
    {
        $fieldsMap = [];
        foreach ($fields as $field) {
            $fieldsMap[$field] = true;
        }

        $result = [];

        if (isset($data[0])) {
            foreach ($data as $val) {
                $result[] = array_filter(
                    $val,
                    fn ($key) => array_key_exists($key, $fieldsMap),
                    ARRAY_FILTER_USE_KEY
                );
            }

            return $result;
        }

        $result = array_filter(
            $data,
            fn ($key) => array_key_exists($key, $fieldsMap),
            ARRAY_FILTER_USE_KEY
        );

        return $result;
    }
}