<?php

use Alura\Cursos\Controller\InterfaceControladorRequisicao;

require_once __DIR__ . '/../vendor/autoload.php';

$caminho = $_SERVER['PATH_INFO'];
$rotas = require __DIR__ . '/../config/routes.php';

if (!array_key_exists($caminho, $rotas)) {
    http_response_code(404);
    exit();
} //create not found page

session_start();

$ehRotaDeLogin = str_contains($caminho, 'login');
if(!isset($_SESSION['logado']) && !$ehRotaDeLogin)
{
    header('Location: /login');
    exit();
}

$classeControladora = $rotas[$caminho];
/** @var InterfaceControladorRequisicao $controlador */
$controlador = new $classeControladora();
$controlador->processaRequisicao();