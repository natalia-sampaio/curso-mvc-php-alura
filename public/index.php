<?php

// fazer log de todas as requisições
switch ($_SERVER['PATH_INFO']) {
    case '/listar-cursos':
        require 'listar-cursos.php';
        break;
    case '/novo-curso':
        require 'form-novo-curso.php';
        break;
    default:
        echo "Erro 404";
        break;
}
