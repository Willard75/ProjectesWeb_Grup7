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

class NotificacionsController
{
    public function inicialitza(Application $app)
    {
        $id_user = $app['session']->get('user')['id'];
        if ($id_user == null){
            $url = '/login';
            return new RedirectResponse($url);
        }
        else {
            $notif = $app['notificacions']->busca_notificacions($id_user, $app);
            $longitud = sizeof($notif);
            $noms_usuaris = array();
            $noms_imatges = array();
            for ($x = 0; $x < $longitud; $x++) {

                $nom_usuari = $app['notificacions']->busca_nom_usuari($notif[$x]['user_id'], $app);
                array_push($noms_usuaris, $nom_usuari[0]['username']);

                $nom_img = $app['notificacions']->busca_nom_img($notif[$x]['img_id'], $app);
                array_push($noms_imatges, $nom_img[0]['title']);
            }
            $content = $app['twig']->render('notificacions.twig', [
                'longitud' => $longitud,
                'consulta' => $notif,
                'noms_usuaris' => $noms_usuaris,
                'noms_imatges' => $noms_imatges
            ]);
            $response = new Response();
            $response->setStatusCode($response::HTTP_OK);
            $response->headers->set('Content-type', 'text/html');
            $response->setContent($content);
            return $response;
        }
    }

    public function getAction(Application $app, $num)
    {
        $id_user = $app['session']->get('user')['id'];
        $notif = $app['notificacions']->busca_notificacions($id_user, $app);
        $id_notificacio = $notif[$num]['id'];
        $app['notificacions']->notificacio_readed($id_notificacio, $app);
        $url = '/notificacions';
        return new RedirectResponse($url);
    }
}