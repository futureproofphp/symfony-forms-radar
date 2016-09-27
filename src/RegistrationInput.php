<?php
namespace FutureProofPhp;

use Psr\Http\Message\ServerRequestInterface as Request;

class RegistrationInput
{
    public function __invoke(Request $request)
    {
        return [$request->getParsedBody()];
    }
}
