<?php

/**
 * @OA\Info(
 *  description="This is a sample Classified advertisements app.",
 *  version="1.0.0",
 *  title="Swagger classified advertisements",
 *  @OA\License(
 *    name="Apache 2.0",
 *    url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *  ),
 * ),
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   in="header",
 *   name="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   name="Authorization",
 *   bearerFormat="JWT"
 * ),
 * @OA\Server(
 *    description="Service host",
 *    url="http://127.0.0.1:8080/v1"
 * ),
 * @OA\Schema(
 *     schema="FieldsParam",
 *     type="array",
 *     @OA\Items(type="string")
 * ),
 * @OA\Schema(schema="ErrorResponse", @OA\Property(property="error", type="string")),
 * @OA\Schema(schema="CreatedResponse", @OA\Property(property="inserted_id", type="int", format="int64"))
 */
