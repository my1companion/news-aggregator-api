<?php

namespace App\Docs;

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * ),
     @OA\Server(
 *         url="{scheme}://{host}/{basePath}",
 *         description="Dynamic server",
 *         @OA\ServerVariable(
 *             serverVariable="scheme",
 *             enum={"http", "https"},
 *             default="http"
 *         ),
 *         @OA\ServerVariable(
 *             serverVariable="host",
 *             default="localhost:8000"
 *         ),
 *         @OA\ServerVariable(
 *             serverVariable="basePath",
 *             default="api"
 *         )
 *     )
 * )
 */
class SwaggerDefinitions
{
    // This file is used solely for Swagger annotations and doesn't require any methods.
}
