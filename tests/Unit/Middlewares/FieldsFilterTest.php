<?php


namespace App\Tests\Unit\Middlewares;


use App\Endpoint\Http\ResponseStatus;
use App\Middleware\FieldsFilterMiddleware;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FieldsFilterTest extends TestCase
{
    public function testProcess_emptyFields()
    {
        $data = ['a' => 'b'];
        $response = new JsonResponse($data, ResponseStatus::HTTP_OK);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn([]);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $filter = new FieldsFilterMiddleware();
        $response = $filter->process($request, $handler);

        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getBody()->getContents());
    }

    public function testProcess_filterFields()
    {
        $data = [
            'field_1' => 'value_1',
            'field_2' => 'value_2',
            'field_3' => 'value_3',
            'field_4' => 'value_4',
        ];

        $response = new JsonResponse($data, ResponseStatus::HTTP_OK);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['fields' => ['field_1', 'field_3']]);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $filter = new FieldsFilterMiddleware();
        $response = $filter->process($request, $handler);

        $actualData = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(['field_1' => 'value_1', 'field_3' => 'value_3'], $actualData);
    }

    public function testProcess_filterFieldsArray()
    {
        $data = [
            [
                'field_1' => 'value_1',
                'field_2' => 'value_2',
                'field_3' => 'value_3',
                'field_4' => 'value_4',
            ],
            [
                'field_1' => 'value_11',
                'field_2' => 'value_21',
                'field_3' => 'value_31',
                'field_4' => 'value_41',
            ]
        ];

        $response = new JsonResponse($data, ResponseStatus::HTTP_OK);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['fields' => ['field_4']]);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $filter = new FieldsFilterMiddleware();
        $response = $filter->process($request, $handler);

        $actualData = json_decode($response->getBody()->getContents(), true);
        $expected = [
            [
                'field_4' => 'value_4',
            ],
            [
                'field_4' => 'value_41'
            ]
        ];
        $this->assertEquals($expected, $actualData);
    }

    public function testProcess_notJsonResponse()
    {
        $body = 'body';
        $response = new Response\TextResponse($body, ResponseStatus::HTTP_OK);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['fields' => ['field_1']]);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $filter = new FieldsFilterMiddleware();
        $response = $filter->process($request, $handler);

        $this->assertEquals($body, $response->getBody()->getContents());
    }
}