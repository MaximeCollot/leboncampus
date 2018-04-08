<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use User\UserProvider;

$app = new Application();

$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());

$app->register(new FormServiceProvider());
$app->register(new LocaleServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new ValidatorServiceProvider());

$app->register(new AssetServiceProvider());

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'lebojatk_asso',
        'user' => 'lebojatk_asso',
        'password' => 'leBonCampus',
    ),
));

$app['userProvider'] = function($app) {
     return new UserProvider($app['db']);
};

$app->register(new Silex\Provider\SessionServiceProvider(),
            ['session.storage.save_path' => __DIR__.'/var/sessions']
        );

$app->register(new CalendR\Extension\Silex\Provider\CalendRServiceProvider());
$eventManager = new CalendR\Event\Manager();
$eventProvider = new CalendR\Event\Provider\Basic();
$eventManager->addProvider('eventProvider', $eventProvider);
$app['calendr']->setEventManager($eventManager);

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
    	'login_firewall' => array(
    		'pattern' => '^/(login|register)$'
    	),
        'default' => array(
            'pattern' => '^.*$',
            'anonymous' => true,
            'form' => array('login_path' => 'login', 'check_path' => 'login_check'),
            'logout' => array('logout_path' => 'deconnexion'),
            'remember_me' => array(
                'secret'   => '%kernel.secret%',
                'lifetime' => 604800, // 1 week in seconds
                'path'     => '/',
            ),
            'users' => function() use ($app) {
                return new UserProvider($app['db']);
            }
        )
    ),
    'security.access_rules' => array(
        array('^/.+$', 'ROLE_USER')
    )
));

$app->register(new Silex\Provider\RememberMeServiceProvider());

$app['security.default_encoder'] = function ($app) {
	return new PlaintextPasswordEncoder();
	// TODO sécuriser le mdp
};

$app['debug'] = true;
if ((isset($app['debug'])) && $app['debug']) {
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), [
        'profiler.cache_dir'    => __DIR__ . '/../var/cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ]);
}

return $app;
