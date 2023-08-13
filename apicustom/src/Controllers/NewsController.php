<?php

namespace App\Controllers;

use App\Core\Attributes\Route;
use App\Core\Response;

class NewsController
{
    /**
     * @return Response
     */
    public function list(): Response
    {
        return new Response('List', "List!!!");
    }

    /**
     * @return Response
     */
    #[Route("addition")]
    public function add(): Response
    {
        return new Response('list', "Addition");
    }

    /**
     * @return Response
     */
    // /api/news/home
    #[Route("home")]
    public function index(): Response
    {
        return new Response('list', "Home");
    }
}