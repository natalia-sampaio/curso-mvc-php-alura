<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Exclusao implements RequestHandlerInterface
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
        $queryString = $request->getQueryParams();
        $entityId = filter_var(
            $queryString['id'],
            FILTER_VALIDATE_INT
        );

        if (is_null($entityId) || $entityId === false) {
            $this->defineMensagem('danger', 'Curso inexistente');
            return new Response(302, ['Location' => '/listar-cursos']);
        }

        $entity = $this->entityManager
            ->getReference(Curso::class, $entityId);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new Response(302, ['Location' => '/listar-cursos']);
    }
}