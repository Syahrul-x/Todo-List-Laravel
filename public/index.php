<?php
include_once '../exceptions/NotFoundException.php';
include_once '../exceptions/InternalServerErrorException.php';

try {
    $c = $_GET['c'] ?? "auth"; // controller
    $m = $_GET['m'] ?? "login"; // method
    $id = $_GET['id'] ?? null; // parameter ID

    if (!ctype_alnum($c) || !ctype_alnum($m)) {
        throw new Exception("Invalid characters in route.");
    }

    // Validasi ID jika ada
    if ($id !== null && !ctype_digit($id)) {
        throw new Exception("Invalid ID parameter.");
    }

    $controllerFile = '../app/Http/Controllers/' . ucfirst($c) . 'Controller.php';
    $controllerClass = ucfirst($c) . 'Controller';

    if (!file_exists($controllerFile)) {
        throw new NotFoundException("Controller file {$controllerFile} not found.");
    }

    include_once '../app/Http/Controllers/Controller.php';
    include_once $controllerFile;

    if (!class_exists($controllerClass)) {
        throw new InternalServerErrorException("Controller class {$controllerClass} not found.");
    }

    $controller = new $controllerClass();
    
    if (!method_exists($controller, $m) || !is_callable([$controller, $m])) {
        throw new InternalServerErrorException("Method {$m} not found in controller {$controllerClass}.");
    }
    
    // Daftar method yang membutuhkan parameter ID
    $methodsWithId = ['update', 'saveUpdate', 'delete', 'show', 'edit'];
    
    // Panggil method dengan atau tanpa ID
    if (in_array($m, $methodsWithId) && $id !== null) {
        $controller->$m($id);
    } else {
        $controller->$m();
    }

} catch (NotFoundException $e) {
    http_response_code(404);
    include '../resources/views/errors/404.php';
    exit();
} catch (InternalServerErrorException $e) {
    http_response_code(500);
    include '../resources/views/errors/500.php';
    exit();
} catch (Exception $e) {
    http_response_code(500);
    include '../resources/views/errors/500.php';
    exit();
}