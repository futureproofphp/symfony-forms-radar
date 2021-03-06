<?php
namespace FutureProofPhp;

use Psr\Http\Message\ServerRequestInterface as PsrRequest;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\Form\FormFactory;

class RegistrationInput
{
    private $formFactory;

    public function __construct($formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function __invoke(PsrRequest $psrRequest)
    {
        $form = $this->formFactory->create(RegistrationType::class);
        $form->handleRequest();

        return [$form];
    }
}
