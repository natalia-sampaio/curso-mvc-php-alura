<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Persistencia implements RequestHandlerInterface
{
    use FlashMessageTrait;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $descricao = filter_var(
            $request->getParsedBody()['descricao'],
            FILTER_SANITIZE_SPECIAL_CHARS
        );

        $curso = new Curso();
        $curso->setDescricao($descricao);

        $cursoId = filter_var(
            $request->getQueryParams()['id'],
            FILTER_VALIDATE_INT
        );

        $tipo = 'success';

        if (!is_null($cursoId) && $cursoId !== false) {
            $curso->setId($cursoId);
            $this->entityManager->merge($curso);
            $this->defineMensagem($tipo, 'Curso atualizado com sucesso');
        } else {
            $this->entityManager->persist($curso);
            $this->defineMensagem($tipo, 'Curso inserido com sucesso');
        }

        $this->entityManager->flush();

        return new Response(302, ['Location' => '/listar-cursos']);

    }
}