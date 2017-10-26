<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 3/5/17
 * Time: 11:20
 */

namespace SilexApp\Controller;


use Exception;
use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    public function inicialitza(Application $app, Request $request)
    {
        //printa error
        $response = new Response();
        $content = $app['twig']->render('error.twig', [
            'error' => "Aquesta pagina no existeix"
        ]);
        $response->setContent($content);
        return $response;
    }
}