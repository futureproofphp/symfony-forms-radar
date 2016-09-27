<?php
namespace FutureProofPhp;

class RegistrationPost
{
    private $filter;

    public function __construct(RegistrationFilter $filter)
    {
        $this->filter = $filter;
    }

    public function __invoke($data)
    {
        $payload = [
            'success' => $this->filter->apply($data['registration']),
        ];
        if (!$payload['success']) {
            $failures = $this->filter->getFailures();
            $payload['failures'] = $failures->getMessages();
        }
        $payload['data'] = $data['registration'];
        return $payload;
    }
}
