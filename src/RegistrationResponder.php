<?php
namespace FutureProofPhp;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Symfony\Component\Form\FormError;
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
        array $payload
    ) {
        $form = $this->formFactory->create(RegistrationType::class);
        if (array_key_exists('data', $payload)) {
            $form->submit($payload['data'], true);
        }
        if (array_key_exists('failures', $payload)) {
            foreach ($payload['failures'] as $name => $messages) {
                $element = $form->get($name);
                foreach ($messages as $message) {
                    $element->addError(new FormError($message));
                }
            }
        }

        $response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write($this->twig->render('index.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData(),
            'valid' => $form->isValid(),
        )));
        return $response;
    }
}
