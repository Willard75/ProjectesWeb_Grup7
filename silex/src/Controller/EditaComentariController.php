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

class EditaComentariController
{
    public function inicialitza(Application $app, Request $request, $id)
    {
        $response = new Response();

        $comentaris = $app['home']->busca_comentari($id, $app);
        if ($comentaris != null) {
            if ($comentaris[0]['user_id'] == $app['session']->get('user')['id']) {
                $id_comentari = $comentaris[0]['id'];
                $content = $app['twig']->render('editacomentari.twig', [
                    'id_comentari' => $id_comentari,
                    'contingut' => $comentaris[0]['content']
                ]);
                $response->setContent($content);
                return $response;
            }
            else {
                //printa error
                $response = new Response();
                $content = $app['twig']->render('error.twig', [
                    'error' => "Aquest comentari no pertany a aquest usuari"
                ]);
                $response->setContent($content);
                return $response;
            }
        }
        else {
            //printa error
            $response = new Response();
            $content = $app['twig']->render('error.twig', [
                'error' => "Aquest comentari no existeix"
            ]);
            $response->setContent($content);
            return $response;
        }
    }

    public function borra(Application $app, $id_comentari)
    {

        //buscar id de la img referent al comentari
        $img_com = $app['comenta'] -> imatge_del_comentari_id($id_comentari, $app);
        //busca els comentaris que te la imatge
        $img_coment = $app['comenta'] -> imatge_del_comentari($img_com[0]['img_id'], $app);
        $cont_act = ($img_coment[0]['comentaris'] - 1);
        $app['comenta']->actualitza($cont_act, $img_coment[0]['id'], $app);
        $app['comenta'] -> delete($id_comentari, $app);

        $url = '/';
        return new RedirectResponse($url);
    }

    public function modifica(Application $app, Request $request, $id_comentari)
    {
        $response = new Response();
        if ($request->isMethod('POST')) {
            //UPDATE del comentari
            $coment = $request->get('comenta2');
            try{
                $app['comenta'] -> modifica($coment, $id_comentari, $app);
                $url = '/';
                return new RedirectResponse($url);
            } catch (Exeption $e) {
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                $content = $app['twig']->render('editacomentari.twig', [
                    'errors' => [
                        'unespected' => 'An error has ocurred'
                    ]
                ]);
                $response->setContent($content);
                return $response;
            }
        }
    }
}