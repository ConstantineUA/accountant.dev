<?php

namespace Accountant;

use Silex\Provider;
use Symfony\Component\HttpFoundation\Request;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Saxulum\DoctrineOrmManagerRegistry\Silex\Provider\DoctrineOrmManagerRegistryProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Braincrafted\Bundle\BootstrapBundle\Twig as BootstrapTwigBundle;

/**
 * The accountant.dev application class,
 * extends Silex\Application, loads necessary providers,
 * performs tunning and configuration of the project
 *
 * @author Constantine
 *
 */
class Application extends \Silex\Application {
    use \Silex\Application\UrlGeneratorTrait;
    use \Silex\Application\TranslationTrait;

    /**
     * List of project-specific config options
     *
     * @var array
     */
    protected $config = array();

    /**
     * Extends base method, stores config array
     *
     * @param array $config configuation array
     * @param array $values
     * @see \Silex\Application::__construct()
     */
    public function __construct(array $config, $values = array())
    {
        parent::__construct($values);
        $this['config'] = $config;
        $this['debug'] = $config['isDevMode'];
    }

    /**
     * Loads DoctrineServiceProvider and performs necessary tunning
     */
    protected function initORM()
    {
        $this->register(new Provider\DoctrineServiceProvider, array(
            'db.options' => $this['config']['dbParams'],
        ));

        $this->register(new DoctrineOrmServiceProvider, array(
            'orm.proxies_dir' => $this['config']['pathCache'] . 'doctrine/',
            'orm.em.options' => array(
                'mappings' => array(
                    array(
                        'type' => 'annotation',
                        'namespace' => 'Accountant\Entity',
                        'path' => $this['config']['pathSrc'],
                    ),
                ),
            ),
        ));
    }

    /**
     * Loads TranslationServiceProvider and yaml-files with messages
     */
    protected function initLocales()
    {
        $this->register(new Provider\TranslationServiceProvider(), array());

        $this['translator'] = $this->share($this->extend('translator', function($translator, $this) {
            $translator->addLoader('yaml', new YamlFileLoader());

            $translator->addResource('yaml', $this['config']['pathLocales'] .'en.yml', 'en');

            return $translator;
        }));
    }

    /**
     * Loads various service providers necessary for work of the project
     */
    protected function initProviders()
    {
        $this->register(new Provider\FormServiceProvider());
        $this->register(new Provider\UrlGeneratorServiceProvider());
        $this->register(new Provider\ValidatorServiceProvider());
        $this->register(new Provider\ServiceControllerServiceProvider());
        $this->register(new DoctrineOrmManagerRegistryProvider());
        $this->register(new Provider\SessionServiceProvider());
    }

    /**
     * Configures service locator, stores references into
     * internal project's component like entities, forms, controllers
     */
    protected function initApplicationComponents()
    {
        // Entities
        $this['accountant.entity.category'] = function () {
            return new Entity\Category();
        };
        $this['accountant.entity.payment'] = function () {
            return new Entity\Payment();
        };

        // Forms
        $this['accountant.form.category'] = function () {
            return new Form\Type\CategoryType();
        };
        $this['accountant.form.payment'] = function () {
            return new Form\Type\PaymentType();
        };
        $this['accountant.form.statistics'] = function () {
            return new Form\Type\CustomStatisticsType();
        };

        // Controllers
        $this['accountant.controller.statistics'] = $this->share(function() {
            return new Controller\StatisticsController($this);
        });

        $this['accountant.controller.category'] = $this->share(function() {
            return new Controller\CategoryController($this);
        });
        $this['accountant.controller.payment'] = $this->share(function() {
            return new Controller\PaymentController($this);
        });
        $this['accountant.controller.user'] = $this->share(function() {
            return new Controller\UserController($this);
        });
    }

    /**
     * Initialize built-in security sub-system to allow login/logout feature
     */
    protected function initSecurity()
    {
        $users = array(
            $this['config']['security']['username'] => array(
                'ROLE_ADMIN', $this['config']['security']['password']
            )
        );

        $this->register(new Provider\SecurityServiceProvider(), array(
            'security.firewalls' => array(
                'login' => array(
                    'anonymous' => true,
                    'pattern' => '^/login/$',
                ),
                'secured' => array(
                    'pattern' => '^/.*',
                    'form' => array('login_path' => '/login/', 'check_path' => '/login_check/'),
                    'users' => $users,
                ),
            )
        ));
    }

    /**
     * Loads and configures Twig
     */
    protected function initTwig()
    {
        $this->register(new Provider\TwigServiceProvider(),
            array(
                'twig.path' => array(
                    $this['config']['pathViews'],
                    $this['config']['pathViews'] . 'blocks/',
                    $this['config']['pathProject'] .'vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/BootstrapBundle/Resources/views/Form',
                ),
            )
        );

        $this['twig'] = $this->share($this->extend('twig', function($twig) {
            $twig->addExtension(new BootstrapTwigBundle\BootstrapIconExtension('glyphicon'));
            $twig->addExtension(new BootstrapTwigBundle\BootstrapLabelExtension);
            $twig->addExtension(new BootstrapTwigBundle\BootstrapBadgeExtension);
            $twig->addExtension(new BootstrapTwigBundle\BootstrapFormExtension);
            return $twig;
        }));

        $this['twig']->getExtension('core')->setTimezone('Europe/Kiev');

        $this['twig']->addGlobal('DATE_FORMAT_GENERAL', $this['config']['outputDateFormatGeneral']);
        $this['twig']->addGlobal('DATE_FORMAT_SHORT', $this['config']['outputDateFormatShort']);
        $this['twig']->addGlobal('DATE_FORMAT_URL', Controller\AbstractController::DATE_FORMAT_URL);

        $this['twig']->addGlobal('FLASH_MESSAGE_SUCCESS', Controller\AbstractController::FLASH_MESSAGE_SUCCESS);
        $this['twig']->addGlobal('FLASH_MESSAGE_WARNING', Controller\AbstractController::FLASH_MESSAGE_WARNING);
        $this['twig']->addGlobal('FLASH_MESSAGE_ERROR', Controller\AbstractController::FLASH_MESSAGE_ERROR);

        $this->before(function (Request $request) {
            $this['twig']->addGlobal('currentRoute', $request->get('_route'));
        });
    }

    /**
     * Extends parent's method,
     * boots all components necessary for the work
     *
     * @see \Silex\Application::boot()
     */
    public function boot()
    {
        $this->initORM();
        $this->initLocales();
        $this->initProviders();
        $this->initApplicationComponents();
        $this->initTwig();
        $this->initSecurity();

        parent::boot();
    }
}
