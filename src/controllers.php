<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage');

/**
 * Render The Form To Transfer Coins
 */
$app->get('/transfer', function () use ($app) {
    $balance = 'Err';
    $errorMsg = null;

    $username = $app['chaincoinuser'];
    $username = escapeshellarg($username);

    /* Find Our Scripts Directory */
    $path = str_replace('/web', '/', $_SERVER['DOCUMENT_ROOT']) . 'scripts/getInfo.sh';

    $resultVar = shell_exec('sudo -u ' . $username . ' ' . $path);

    $jsonResult = json_decode($resultVar, true);
    $balance = $jsonResult['balance'];

    return $app['twig']->render('transfer.html.twig', array('balance'=> $balance, 'errorMsg' => $errorMsg));
})
->bind('transfer_coins');

/**
 * Process The Form To Transfer Coins
 */
$app->post('/transfer', function (Request $request) use ($app) {
    $amount = $request->get('amount');
    $address = $request->get('address');

    $username = $app['chaincoinuser'];
    $username = escapeshellarg($username);

    /* Find Our Scripts Directory */
    $path = str_replace('/web', '/', $_SERVER['DOCUMENT_ROOT']) . 'scripts/transferCoins.sh';
    echo 'sudo -u ' . $username . ' ' . $path . ' ' . $address . ' ' . $amount . ' 2>&1';
    $resultVar = shell_exec('sudo -u ' . $username . ' ' . $path . ' ' . $address . ' ' . $amount . ' 2>&1');


    return $app['twig']->render('transfer_complete.html.twig', array('balance'=>1028.208, 'amount'=>$amount, 'address'=>$address, 'result' => $resultVar));
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
