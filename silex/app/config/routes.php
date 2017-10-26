<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$app->match('/', 'SilexApp\Controller\HomeController::inicialitza');
$app->post('/comenta/home/{id}', 'SilexApp\Controller\HomeController::comenta_home');
$app->post('/comenta/perfil/{id}/{id_user_perf}', 'SilexApp\Controller\PerfilController::comenta_perfil');
$app->post('/comenta/imageviewer/{id}', 'SilexApp\Controller\ImageViewerController::comenta_imageviewer');

$app->match('/singup', 'SilexApp\Controller\SingUpController::postAction');

$app->match('/login', 'SilexApp\Controller\LoginController::postAction');
$app->match('/logout', 'SilexApp\Controller\LogoutController::logout');

$app->match('/publica', 'SilexApp\Controller\PublicaController::inicialitza');

$app->post('/editacomentari/{id_comentari}', 'SilexApp\Controller\EditaComentariController::modifica');
$app->get('/editacomentari/{id}', 'SilexApp\Controller\EditaComentariController::inicialitza');
$app->post('/borracomentari/{id_comentari}', 'SilexApp\Controller\EditaComentariController::borra');

$app->match('/editaperfil/{id}', 'SilexApp\Controller\EditaPerfilController::edita');
$app->post('/mesimatges','SilexApp\Controller\HomeController::mostraImatges');

$app->match('/editafoto/{id}', 'SilexApp\Controller\EditaFotoController::inicialitza');
$app->match('/modificafoto/{id}', 'SilexApp\Controller\EditaFotoController::modifica');
$app->match('/borrafoto/{id}', 'SilexApp\Controller\EditaFotoController::borra');

$app->get('/like/home/{id}', 'SilexApp\Controller\HomeController::like_home');
$app->get('/like/perfil/{id}/{id_user_perf}', 'SilexApp\Controller\PerfilController::like_perfil');
$app->get('/like/imageviewer/{id}', 'SilexApp\Controller\ImageViewerController::like_imageviewer');

$app->match('/notificacions', 'SilexApp\Controller\NotificacionsController::inicialitza');
$app->get('/notificacions/read/{num}', 'SilexApp\Controller\NotificacionsController::getAction');

$app->match('/perfil/{id}', 'SilexApp\Controller\PerfilController::inicialitza');
$app->match('/imageviewer/{img_id}', 'SilexApp\Controller\ImageViewerController::inicialitza');

$app->match('/error', 'SilexApp\Controller\ErrorController::inicialitza');
$app->error(function (\Exception $e, Request $request, $code) {
    switch ($code) {
        case 404:
            header('Location: http://www.grup7.com/error');
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
            break;
    }

    die();
});

$app->match('/validamail/{hash}', 'SilexApp\Controller\ValidaMailController::validamail');
$app->match('/comentaris/{id}', 'SilexApp\Controller\ComentarisController::inicialitza');

$app->post('/mescomentaris', 'SilexApp\Controller\ImageViewerController::mescomentaris');
