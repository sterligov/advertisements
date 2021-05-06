<?php declare(strict_types=1);


namespace App\Endpoint\Http\Handler;


use App\Domain\Entity\DTO\Token;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\UseCase\AuthInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Endpoint\Http\ResponseStatus;

class AuthHandler
{
    public function __construct(
        private AuthInterface $auth
    ) {
    }

    /**
     * @OA\Post(
     *   path="/sign-in",
     *   summary="App sing-in",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         type="object",
     *         @OA\Property(
     *           property="email",
     *           description="User email",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="password",
     *           description="User password",
     *           type="string"
     *         ),
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Bearer token",
     *     @OA\JsonContent(ref="#/components/schemas/Token")
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Invalid username or password",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     *
     * @throws InvalidCredentialsException
     */
    public function signIn(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $token = $this->auth->signIn($body['email'] ?? '', $body['password'] ?? '');

        return new JsonResponse(new Token($token), ResponseStatus::HTTP_OK);
    }
}