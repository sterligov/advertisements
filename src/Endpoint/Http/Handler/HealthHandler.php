<?php declare(strict_types=1);


namespace App\Endpoint\Http\Handler;


use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HealthHandler
{
    /**
     * @OA\Get(
     *   path="/health",
     *   summary="Health check",
     *   @OA\Response(
     *     response=200,
     *     description="Service alived",
     *     @OA\JsonContent(
     *       @OA\Property(property="health", type="string", enum={"alive"})
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function check(RequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['health' => 'alive'], ResponseStatus::HTTP_OK);
    }
}