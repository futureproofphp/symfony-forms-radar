<?php
namespace FutureProofPhp;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Validator\Validation;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class Config extends ContainerConfig
{
    const DEFAULT_FORM_THEME = 'bootstrap_3_horizontal_layout.html.twig';
    const VENDOR_DIR = __DIR__ . '/../vendor';
    const VENDOR_FORM_DIR = self::VENDOR_DIR . '/symfony/form';
    const VENDOR_VALIDATOR_DIR = self::VENDOR_DIR . '/symfony/validator';
    const VENDOR_TWIG_BRIDGE_DIR = self::VENDOR_DIR . '/symfony/twig-bridge';
    const VIEWS_DIR = __DIR__ . '/../views';

    public function define(Container $di)
    {
        /** Services */
        $di->set('symfony/form:factory:builder', $di->lazyNew(FormFactoryBuilder::class));
        $di->set('symfony/form:factory', $di->lazyGetCall('symfony/form:factory:builder', 'getFormFactory'));
        $di->set('twig:environment', $di->lazyNew(Twig_Environment::class));

        $di->params[RegistrationInput::class] = [
            'formFactory' => $di->lazyGet('symfony/form:factory'),
        ];

        $di->params[RegistrationResponder::class] = [
            'twig' => $di->lazyGet('twig:environment'),
        ];

        /** SessionTokenStorage */
        $di->params[SessionTokenStorage::class]['session'] = $di->lazyNew(Session::class);

        /** CsrfTokenManager */
        $di->params[CsrfTokenManager::class] = [
            'generator' => $di->lazyNew(UriSafeTokenGenerator::class),
            'storage' => $di->lazyNew(SessionTokenStorage::class),
        ];

        /** CsrfExtension */
        $di->params[CsrfExtension::class] = [
            'tokenManager' => $di->lazyNew(CsrfTokenManager::class),
        ];

        /** Twig_Loader_Filesystem */
        $di->params[Twig_Loader_Filesystem::class]['paths'] = [
            self::VIEWS_DIR,
            self::VENDOR_TWIG_BRIDGE_DIR . '/Resources/views/Form',
        ];

        /** Twig_Environment */
        $di->params[Twig_Environment::class] = [
            'loader' => $di->lazyNew(Twig_Loader_Filesystem::class),
            'options' => ['debug' => true],
        ];

        $di->setters[Twig_Environment::class]['setExtensions'] = new LazyArray([
            $di->lazyNew(TranslationExtension::class),
            $di->lazyNew(Twig_Extension_Debug::class),
            $di->lazyNew(FormExtension::class),
        ]);

        /** TwigRendererEngine */
        $di->params[TwigRendererEngine::class]['defaultThemes'] = [self::DEFAULT_FORM_THEME];

        /** TwigRenderer */
        $di->params[TwigRenderer::class]['engine'] = $di->lazyNew(TwigRendererEngine::class);
        $di->params[TwigRenderer::class]['csrfTokenManager'] = $di->lazyNew(CsrfTokenManager::class);

        /** FormExtension */
        $di->params[FormExtension::class]['renderer'] = $di->lazyNew(TwigRenderer::class);

        /** Translator */
        $di->params[Translator::class]['locale'] = 'en';
        $di->setters[Translator::class]['addLoaders'] = new LazyArray([
            new LazyArray(['xlf', $di->lazyNew(XliffFileLoader::class)]),
        ]);
        $di->setters[Translator::class]['addResources'] = new LazyArray([
            new LazyArray([
                'xlf',
                self::VENDOR_FORM_DIR . '/Resources/translations/validators.en.xlf',
                'en',
                'validators',
            ]),
            new LazyArray([
                'xlf',
                self::VENDOR_VALIDATOR_DIR . '/Resources/translations/validators.en.xlf',
                'en',
                'validators',
            ]),
        ]);

        /** TranslationExtension */
        $di->params[TranslationExtension::class]['translator'] = $di->lazyNew(Translator::class);

        /** ValidatorExtension */
        $di->params[ValidatorExtension::class] = [
            'validator' => $di->lazy([Validation::class, 'createValidator']),
        ];

        /** FormFactoryBuilder */
        $di->setters[FormFactoryBuilder::class]['addExtensions'] = new LazyArray([
            $di->lazyNew(CoreExtension::class),
            $di->lazyNew(CsrfExtension::class),
            $di->lazyNew(ValidatorExtension::class),
        ]);
    }

    public function modify(Container $di)
    {

    }
}
