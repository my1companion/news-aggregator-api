<?php

namespace App\Docs;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="News Aggregator API ",
 *         version="1.0.0",
 *         description="Comprehensive API documentation with grouped paths and tags.",
 *         @OA\Contact(
 *             email="adebowalehassan@yahoo.com"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @OA\Server(
 *         url="{scheme}://{host}/{basePath}",
 *         description="Dynamic server configuration",
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
 *     ),
 *     @OA\Tag(
 *         name="Authentication",
 *         description="Endpoints related to user authentication, such as login and logout."
 *     ),
 *     @OA\Tag(
 *         name="Password",
 *         description="Endpoints for password management, such as resetting and sending reset links."
 *     ),
 *     @OA\Tag(
 *         name="Articles",
 *         description="Endpoints for managing and retrieving news articles."
 *     ),
 *     @OA\Tag(
 *         name="User Preferences",
 *         description="Endpoints for managing user-specific settings and preferences."
 *     )
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter 'Bearer' followed by a space and your token."
 * )
 */
class SwaggerDefinitions
{
    // This file is used solely for Swagger annotations and doesn't require any methods.
}
