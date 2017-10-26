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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicaController
{
    public function inicialitza(Application $app, Request $request)
    {
        $id_user = $app['session']->get('user')['id'];
        //validar si esta logejat o no
        if ($id_user == null) {
            $url = '/login';
            return new RedirectResponse($url);
        } else {
            $response = new Response();
            if ($request->isMethod('POST')) {
                $titol = $request->get('titol');
                $private = $request->get('private');

                if ($private == null) {
                    $private = 0;
                }
                try {
                    //*********************************************************
                    $original_filename = $request->files->get('imatge');
                    $filename = $original_filename->getClientOriginalName();
                    $path = 'assets/images/';
                    /** @var UploadedFile $original_filename */
                    try {
                        $original_filename->move($path, $filename);
                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                    }
                    //*********************************************************

                    $app['publica']->add($id_user, $titol, $private, "assets/images/".$filename, $app);
                    $url = '/';
                    return new RedirectResponse($url);
                } catch (Exeption $e) {
                    print_r("No se ha podido insertar correctamente");
                }
            }
            $content = $app['twig']->render('publica.twig');
            $response->setContent($content);
            return $response;
        }
    }

}