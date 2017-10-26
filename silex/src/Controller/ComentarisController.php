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

class ComentarisController
{
    public function inicialitza(Application $app, Request $request, $id)
    {
        if ($app['session']->get('user')['id'] == $id) {
            $comentaris = $app['comenta']->comentaris_usuari($id, $app);
            $longitud = sizeof($comentaris);
            $a_com = array();
            $a_id = array();
            for ($x = 0; $x < $longitud; $x++) {
                array_push($a_com, $comentaris[$x]['content']);
                array_push($a_id, $comentaris[$x]['id']);
                $nom_usuari = $app['home']->busca_nom_usuari($comentaris[$x]['user_id'], $app);
                $nom = $nom_usuari[0]['username'];
                $img = $nom_usuari[0]['img_path'];
            }
            $response = new Response();
            $content = $app['twig']->render('comentaris.twig', [
                'a_com' => $a_com,
                'a_id' => $a_id,
                'id_user' => $id,
                'nom' => $nom,
                'img' => $img,
                'longitud' => $longitud
            ]);
            $response->setContent($content);
            return $response;
        }
        else {
            //printa error
            $response = new Response();
            $content = $app['twig']->render('error.twig', [
                'error' => "No tienes permisos para acceder a esta pagina"
            ]);
            $response->setContent($content);
            return $response;
        }
    }
}