<?php declare(strict_types=1);


namespace App\Endpoint\Http\Handler;


use OpenApi\Annotations as OA;
use App\Domain\Entity\Advertising;
use App\Domain\Entity\Cursor;
use App\Domain\Entity\DTO\InsertedId;
use App\Endpoint\Http\ResponseStatus;
use App\Domain\Exception\UnexpectedActiveUserException;
use App\Domain\UseCase\ActiveUserInterface;
use App\Domain\UseCase\AdvertisingInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AdvertisingHandler
{
    public function __construct(
        private AdvertisingInterface $advertising,
        private ActiveUserInterface $activeUser,
    ) {
    }

    /**
     * @OA\Get(
     *   path="/advertisements",
     *   summary="List of user advertisements",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(name="fields[]", in="query", @OA\Schema(ref="#/components/schemas/FieldsParam")),
     *   @OA\Parameter(name="limit", in="query", @OA\Schema(type="integer", default=10)),
     *   @OA\Parameter(name="value", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="cursor", in="query",@OA\Schema(type="string", default="next", enum={"next", "prev"})),
     *   @OA\Response(
     *     response=200,
     *     description="List of advertising entities",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Advertising")),
     *       @OA\Property(property="prevCursor", type="integer", format="int64"),
     *       @OA\Property(property="nextCursor", type="integer", format="int64")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function findAllWithCursor(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $limit = $params['limit'] ?? 10;

        $cursor = (new Cursor())
            ->setLimit((int)$limit)
            ->setValue($params['value'] ?? null)
            ->setType($params['cursor'] ?? Cursor::NEXT_CURSOR);

        $cursorAds = $this->advertising->findAllWithCursor($cursor);

        return new JsonResponse($cursorAds, ResponseStatus::HTTP_OK);
    }

    /**
     * @OA\Get(
     *   path="/advertisements/{id}",
     *   summary="Get advertisements by id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Advertising ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Advertising entity",
     *     @OA\JsonContent(ref="#/components/schemas/Advertising")
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Advertising not found"),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function findById(ServerRequestInterface $request): ResponseInterface
    {
        $advertising = $this->advertising->findById((int)$request->getAttribute('id'));
        if ($advertising !== null) {
            return new JsonResponse($advertising, ResponseStatus::HTTP_OK);
        }

        return new Response\EmptyResponse(ResponseStatus::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *   path="/advertisements",
     *   summary="Create advertising",
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     description="Advertising entity",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Advertising")
     *   ),
     *   @OA\Response(response=201, description="Advertising has been created"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=400, description="Validation errors"),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     *
     * @throws UnexpectedActiveUserException
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $user = $this->activeUser->getUser();

        $advertising = new Advertising();
        $advertising->setAttributes($body);
        $advertising->setUser($user);

        $this->advertising->create($advertising);

        return new JsonResponse(new InsertedId($advertising->getId()), ResponseStatus::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *   path="/advertisements/{id}",
     *   summary="Update advertising",
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     description="Advertising entity",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Advertising")
     *   ),
     *   @OA\Response(response=200, description="Advertising has been updated"),
     *   @OA\Response(response=400, description="Validation errors"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Advertising not found"),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     *
     * @throws UnexpectedActiveUserException
     */
    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $user = $this->activeUser->getUser();

        $advertising = new Advertising();
        $advertising->setId((int)$request->getAttribute('id'));
        $advertising->setAttributes($body);
        $advertising->setUser($user);

        $this->advertising->update($advertising);

        return new Response\EmptyResponse(ResponseStatus::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *   path="/advertisements/{id}",
     *   summary="Delete advertising by id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="User ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="Advertising has been deleted"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Advertising not found"),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     *
     * @throws UnexpectedActiveUserException
     */
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $advertisingId = $request->getAttribute('id');
        $userId = $this->activeUser->getUserId();

        $this->advertising->delete((int)$advertisingId, $userId);

        return new Response\EmptyResponse(ResponseStatus::HTTP_OK);
    }
}