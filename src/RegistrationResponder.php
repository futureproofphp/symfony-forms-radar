<?php
namespace FutureProofPhp;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Symfony\Component\Form\FormFactory;
use Twig_Environment;

class RegistrationResponder
{
    private $twig;
    private $formFactory;

    public function __construct(Twig_Environment $twig, FormFactory $formFactory)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
    }

    public function __invoke(
        Request $request,
        Response $response,
        array $data
    ) {
        $form = $this->formFactory->create(RegistrationType::class);
        $form->handleRequest();

        $response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write($this->twig->render('index.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData(),
            'valid' => $form->isValid(),
        )));
        return $response;
    }
}
