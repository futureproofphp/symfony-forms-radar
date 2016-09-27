<?php
use Psr\Http\Message\ServerRequestInterface;
use Radar\Adr\Boot;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Symfony\Component\Form\Form;
use Zend\Diactoros\Response as Response;
use Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;

require __DIR__ . '/../vendor/autoload.php';

$boot = new Boot();
$adr = $boot->adr([
    'FutureProofPhp\Config',
]);

/** Middleware */
$adr->middle(new ResponseSender());
$adr->middle(new ExceptionHandler(new Response()));
$adr->middle('Radar\Adr\Handler\RoutingHandler');
$adr->middle('Radar\Adr\Handler\ActionHandler');

/** Routes */
$adr->get('getHome', '/', function (array $data) {
    return $data;
})
->input('FutureProofPhp\RegistrationInput')
->responder('FutureProofPhp\RegistrationResponder');

$adr->post('postHome', '/', function (array $data) {
    return $data;
})
->input('FutureProofPhp\RegistrationInput')
->responder('FutureProofPhp\RegistrationResponder');

/** Run */
$adr->run(ServerRequestFactory::fromGlobals(), new Response());
