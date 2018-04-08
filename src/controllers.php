<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array('pageName' => 'Home'));
})
->bind('homepage')
;

$app->get('/login', function (Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'pageName' => 'Login',
        'error' => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username')
    ));
})
->bind('login')
;

$app->match('/register', 'RegistrationController::registerAction')
->bind('user_registration');

$app->match('/kit', 'KitController::registerAction')
->bind('kit');

$app->get('/permanence', function () use ($app) {
    $year = date("Y");
    $month = date("n");
    return $app->redirect("permanence/".$year."/".$month);
})
->bind('permanence')
;

$app->get('/permanence/{year}/{month}', function($year, $month) use($app) {
    $mois = array('','Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
    $calendRMonth = $app['calendr']->getMonth($year, $month);
    $providers = $app['calendr']->getEventManager()->getProviders();
    $provider = end($providers);
    foreach ($calendRMonth as $week) {
        foreach ($week as $day) {
            if ($day->format('w') == 4) {
                $event = new CalendR\Event\Event(uniqid(),$day->getBegin(),$day->getBegin());
                $provider->add($event);
            }
        }
    }

    return $app['twig']->render('calendar.html.twig', array(
        'month'  => $calendRMonth,
        'currentMonth' => $month,
        'year' => $year,
        'previousMonth' => $month - 1,
        'nextMonth' => $month + 1,
        'date' => $mois[$month].' - '.$year,
        'events' => $app['calendr']->getEvents($calendRMonth)
    ));
});

$app->get('/permanence/{year}/{month}/{day}', function($year, $month, $day) use($app) {
    return $app['twig']->render('permanence.html.twig');
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
