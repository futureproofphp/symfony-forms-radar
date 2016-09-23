<?php
namespace FutureProofPhp;

use Aura\Di\Injection\LazyInterface;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator extends SymfonyTranslator
{
    public function addLoaders(array $loaders)
    {
        foreach ($loaders as $l) {
            if ($l[1] instanceof LazyInterface) {
                $l[1] = $l[1]();
            }
            $this->addLoader($l[0], $l[1]);
        }
    }

    public function addResources(array $resources)
    {
        foreach ($resources as $r) {
            $this->addResource($r[0], $r[1], $r[2], $r[3]);
        }
    }
}
