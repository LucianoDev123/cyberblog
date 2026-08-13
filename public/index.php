<?php

// Iniciamos la sesión PHP.
// Esto permite almacenar información del usuario entre diferentes peticiones.
session_start();

// Cargamos e iniciamos nuestra aplicación MVC.
require_once dirname(__DIR__) . '/bootstrap/app.php';