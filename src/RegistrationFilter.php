<?php
namespace FutureProofPhp;

use Aura\Filter\SubjectFilter;

class RegistrationFilter extends SubjectFilter
{
    public function init()
    {
        $this->validate('firstName')->isNotBlank();
        $this->validate('firstName')->is('strlenMin', 4);
        $this->sanitize('firstName')->to('string');
        $this->useFieldMessage('firstName', 'Minimum length of 4 is required.');

        $this->validate('lastName')->isNotBlank();
        $this->validate('lastName')->is('strlenMin', 4);
        $this->sanitize('lastName')->to('string');
        $this->useFieldMessage('lastName', 'Minimum length of 4 is required.');

        $this->validate('gender')->isNotBlank();
        $this->validate('gender')->is('inValues', ['male', 'female']);
        $this->sanitize('gender')->to('string');
        $this->useFieldMessage('gender', 'Invalid value.');

        $this->validate('newsletter')->isBlankOr('equalToValue', 1);
        $this->useFieldMessage('newsletter', 'Invalid value.');

        $this->validate('_token')->isNotBlank();
        $this->useFieldMessage('_token', 'Invalid value.');
    }
}
