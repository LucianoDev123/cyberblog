<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        echo "<h1>Bienvenido a CyberBlog</h1>";
    }
}