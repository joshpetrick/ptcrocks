<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    $chainCoinInfo = array();
    $resultVar = null;
    echo 'Result:';
    echo shell_exec('sudo -u jpetrick /var/www/html/test.sh 2>&1');
    print_r($chainCoinInfo);
    echo 'ResultVar:' . $resultVar;
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage');

/**
 * Render The Form To Transfer Coins
 */
$app->get('/transfer', function () use ($app) {
    return $app['twig']->render('transfer.html.twig', array('balance'=>1028.208));
})
->bind('transfer_coins');

/**
 * Process The Form To Transfer Coins
 */
$app->post('/transfer', function (Request $request) use ($app) {
    $amount = $request->get('amount');
    $address = $request->get('address');
    return $app['twig']->render('transfer_complete.html.twig', array('balance'=>1028.208, 'amount'=>$amount, 'address'=>$address));
})
->bind('transfer_coins_post');


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
