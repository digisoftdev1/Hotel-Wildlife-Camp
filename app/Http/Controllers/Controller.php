<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "CFR Content API",
    version: "1.0.0",
    description: "Public API for retrieving website content",
    contact: new OA\Contact(email: "admin@example.com")
)]
#[OA\Server(
    url: "/api/v1",
    //url: 'https://www.hotelwildlife.com.np/admin/api/v1',

    description: "API V1"
)]
abstract class Controller
{
    //
}
