<?php
namespace FutureProofPhp;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Twig_Environment;

class RegistrationResponder
{
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(
        Request $request,
        Response $response,
        $form
    ) {
        $response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write($this->twig->render('index.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData(),
            'valid' => $form->isValid(),
        )));
        return $response;
    }
}
