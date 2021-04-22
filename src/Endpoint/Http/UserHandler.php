<?php declare(strict_types=1);


namespace App\Endpoint\Http;


use App\Domain\Entity\DTO\InsertedId;
use App\Domain\Entity\User;
use App\Domain\UseCase\ActiveUserInterface;
use App\Domain\UseCase\UserInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserHandler
{
    public function __construct(
        private UserInterface $user,
        private ActiveUserInterface $activeUser,
    ) {
    }

    /**
     * @OA\Get(
     *   path="/users/{id}",
     *   summary="Get user by id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="User ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="User entity",
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Empty body"),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function findById(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->user->findById((int)$request->getAttribute('id'));
        if ($user !== null) {
            return new JsonResponse($user->getAttributes(), ResponseStatus::HTTP_OK);
        }

        return new EmptyResponse(ResponseStatus::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *   path="/sign-up",
     *   summary="Create user",
     *   @OA\RequestBody(
     *     description="User entity",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *   @OA\Response(response=201, description="User has been created"),
     *   @OA\Response(
     *     response=400,
     *     description="Validation errors"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     *
     * @throws \JsonException
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $user = new User();
        $user->setAttributes($body);

        $this->user->create($user);

        return new JsonResponse(new InsertedId($user->getId()), ResponseStatus::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *   path="/user/{id}",
     *   summary="Update user",
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     description="User entity",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *   @OA\Response(response=200, description="User has been updated"),
     *   @OA\Response(response=400, description="Validation errors"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="User not found"),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     *
     *
     * @throws \App\Domain\Exception\GatewayException
     * @throws \App\Domain\Exception\PasswordHashException
     * @throws \App\Domain\Exception\ValidationException
     */
    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $user = new User();
        $user->setAttributes($body);
        $user->setId((int)$request->getAttribute('id'));

        $this->user->update($user);

        return new JsonResponse($user, ResponseStatus::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *   path="/users/{id}",
     *   summary="Delete user by id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="User ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="User has been deleted"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="User not found"),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     *
     * @throws \App\Domain\Exception\UnexpectedActiveUserException
     */
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $this->user->delete($this->activeUser->getUser());

        return new EmptyResponse(ResponseStatus::HTTP_OK);
    }
}