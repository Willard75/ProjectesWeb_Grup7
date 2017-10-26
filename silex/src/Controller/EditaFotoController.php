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

class EditaFotoController
{
    public function inicialitza(Application $app, Request $request, $id)
    {
        $response = new Response();
        //pillar l'id de l'usuari de la foto i comprarlo amb el del usuari logejat si esta loguejat sino fora
        $cont = $app ['visualitzacions']->visualitzacions_img($id, $app);
        if ($cont != null){
            if ($cont['user_id'] == $app['session']->get('user')['id']) {
                $content = $app['twig']->render('editafoto.twig', [
                    'id' => $id,
                    'titol' => $cont['title'],
                    'private' => $cont['private'],
                    'path' => $cont['img_path']
                ]);
                $response->setContent($content);
                return $response;
            }
            else {
                //printa error
                $response = new Response();
                $content = $app['twig']->render('error.twig', [
                    'error' => "Esta imagen no te pertenece"
                ]);
                $response->setContent($content);
                return $response;
            }
        }
        else {
            //printa error
            $response = new Response();
            $content = $app['twig']->render('error.twig', [
                'error' => "Esta imagen no existe"
            ]);
            $response->setContent($content);
            return $response;
        }
    }

    public function modifica(Application $app, Request $request, $id)
    {
        $response = new Response();
        if ($request->isMethod('POST')) {
            //UPDATE del comentari
            $id_imatge = $id;
            $titol = $request->get('titol');
            $private = $request->get('private');
            if ($private == null) {
                $private = 0;
            }
            try {
                $app['publica']->modifica($id_imatge, $titol, $private, $app);
                $url = '/';
                return new RedirectResponse($url);
            } catch (Exeption $e) {
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                $content = $app['twig']->render('editafoto.twig', [
                    'errors' => [
                        'unespected' => 'An error has ocurred'
                    ]
                ]);
                $response->setContent($content);
                return $response;
            }
        }
    }

    public function borra(Application $app, $id)
    {
        $id_imatge = $id;
        print_r($id_imatge);
        //borrar els comentaris d'aquella foto i likes
        $comentaris = $app['home']->busca_comentaris($id, $app);
        $longitud_com = sizeof($comentaris);
        for($j = 0; $j < $longitud_com; $j++){
            $app['comenta'] -> delete($comentaris[$j]['id'], $app);
        }

        $likes = $app['like']->busca_likes($id, $app);
        $longitud_likes = sizeof($likes);
        for($j = 0; $j < $longitud_likes; $j++){
            $app ['like']->delete_likes($id_imatge, $app);
        }
        $notif = $app['notificacions']->busca_notificacions_imatge($id, $app);
        $longitud_notif = sizeof($notif);
        for($j = 0; $j < $longitud_likes; $j++){
            $app['notificacions']->delete_notificacions($id, $app);
        }
        $app['publica']->delete($id_imatge, $app);
        $url = '/';
        return new RedirectResponse($url);

    }
}