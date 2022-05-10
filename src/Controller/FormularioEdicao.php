<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Helper\RenderizadorDeHtmlTrait;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FormularioEdicao implements RequestHandlerInterface
{
    use RenderizadorDeHtmlTrait;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repositorioCursos;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositorioCursos = $entityManager
            ->getRepository(Curso::class);
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $cursoId = filter_var(
            $request->getQueryParams()['id'],
            FILTER_VALIDATE_INT
        );
        
        $resposta = new Response(302, ['Location' => '/listar-cursos']);
        if (is_null($cursoId) || $cursoId === false) {
            return $resposta;
        }

        $curso = $this->repositorioCursos->find($cursoId);

        $html = $this->renderizaHtml('/cursos/formulario.php', [
            'curso' => $curso,
            'titulo' => "Alterar curso {$curso->getDescricao()}",
        ]);
        return new Response(200, [], $html);
    }
}