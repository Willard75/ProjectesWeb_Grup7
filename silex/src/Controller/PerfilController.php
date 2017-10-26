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

class PerfilController
{
    public function inicialitza(Application $app, Request $request, $id)
    {
        $user = $app['perfil']->busca_user($app, $id);

        if ($user == null){
            //printa error
            $response = new Response();
            $content = $app['twig']->render('error.twig', [
                'error' => "El usuari no existeix"
            ]);
            $response->setContent($content);
            return $response;
        }
        else {
            $publicacions = $app['perfil']->busca_publicacions($app, $id);
            $publi = sizeof($publicacions);
            $comentaris = $app['perfil']->busca_comentaris($app, $id);
            $coment = sizeof($comentaris);

            //*****************************************
            $id_user = $app['session']->get('user')['id'];
            $ordre = 1;
            //------------------------------------------------------------
            $response = new Response();
            if ($request->isMethod('POST')) {
                $ordre = $request->get('taskOption');
            }
            //print_r($ordre);
            //------------------------------------------------------------
            if ($id_user != null) {
                if ($id_user == $id) {
                    if ($ordre == 1) {
                        $images = $app['perfil']->busca_imatges_ocreacio_you($app, $id);
                    }
                    if ($ordre == 2) {
                        $images = $app['perfil']->busca_imatges_olikes_you($app, $id);
                    }
                    if ($ordre == 3) {
                        $images = $app['perfil']->busca_imatges_ocomentaris_you($app, $id);
                    }
                    if ($ordre == 4) {
                        $images = $app['perfil']->busca_imatges_ovisualitzacions_you($app, $id);
                    }
                } else {
                    if ($ordre == 1) {
                        $images = $app['perfil']->busca_imatges_ocreacio($app, $id);
                    }
                    if ($ordre == 2) {
                        $images = $app['perfil']->busca_imatges_olikes($app, $id);
                    }
                    if ($ordre == 3) {
                        $images = $app['perfil']->busca_imatges_ocomentaris($app, $id);
                    }
                    if ($ordre == 4) {
                        $images = $app['perfil']->busca_imatges_ovisualitzacions($app, $id);
                    }
                }
            } else {
                if ($ordre == 1) {
                    $images = $app['perfil']->busca_imatges_ocreacio($app, $id);
                }
                if ($ordre == 2) {
                    $images = $app['perfil']->busca_imatges_olikes($app, $id);
                }
                if ($ordre == 3) {
                    $images = $app['perfil']->busca_imatges_ocomentaris($app, $id);
                }
                if ($ordre == 4) {
                    $images = $app['perfil']->busca_imatges_ovisualitzacions($app, $id);
                }
            }

            $longitud = sizeof($images);
            $noms_autors = array();
            $id_autors = array();
            $aa_id_autors = array();
            $dates_imatges = array();
            $titols_imatges = array();
            $img_paths = array();
            $num_visualitzacions = array();
            $num_likes = array();
            $aa_usuari = array();
            $aa_com = array();
            $aa_ha_comentat = array();
            $aa_fotos_perfil = array();
            $fotos_perfil_img = array();
            $ha_comentat = 0;
            $aa_longituds = array();
            $id_fotos = array();
            $aa_id_comentaris = array();
            for ($x = 0; $x < $longitud; $x++) {

                $nom_usuari = $app['home']->busca_nom_usuari($images[$x]['user_id'], $app);
                array_push($noms_autors, $nom_usuari[0]['username']);

                array_push($fotos_perfil_img, $nom_usuari[0]['img_path']);

                array_push($id_autors, $images[$x]['user_id']);

                array_push($dates_imatges, $images[$x]['created_at']);

                array_push($titols_imatges, $images[$x]['title']);

                array_push($img_paths, $images[$x]['img_path']);

                array_push($num_visualitzacions, $images[$x]['visits']);

                array_push($num_likes, $images[$x]['likes']);

                //array d'id d'imatges seleccionades previament
                array_push($id_fotos, $images[$x]['id']);

                $comentaris = $app['home']->busca_comentaris($images[$x]['id'], $app);
                $longitud_com = sizeof($comentaris);
                array_push($aa_longituds, $longitud_com);
                $com = array();
                $usuari_com = array();
                $id_autors_com = array();
                $fotos = array();
                $id_comentari = array();
                for ($j = 0; $j < $longitud_com; $j++) {
                    $u = $app['home']->busca_nom_usuari($comentaris[$j]['user_id'], $app);
                    array_push($usuari_com, $u[0]['username']);
                    array_push($fotos, $u[0]['img_path']);
                    array_push($id_autors_com, $comentaris[$j]['user_id']);
                    array_push($com, $comentaris[$j]['content']);
                    //id comentaris
                    array_push($id_comentari, $comentaris[$j]['id']);
                    if ($app['session']->get('user')['id']) {
                        if ($comentaris[$j]['user_id'] == $app['session']->get('user')['id']) {
                            $ha_comentat = 1;
                        }
                    }
                }
                array_push($aa_usuari, $usuari_com);
                array_push($aa_id_autors, $id_autors_com);
                array_push($aa_com, $com);
                array_push($aa_ha_comentat, $ha_comentat);
                array_push($aa_fotos_perfil, $fotos);
                //aa id comentaris
                array_push($aa_id_comentaris, $id_comentari);

                unset($com);
                unset($usuari_com);
                unset($id_autors_com);
                unset($fotos);
                unset($id_comentari);
                $ha_comentat = 0;
            }
            //*****************************************

            $content = $app['twig']->render('perfil.twig', [
                'user' => $user[0]['username'],
                'foto_perfil' => $user[0]['img_path'],
                'publi' => $publi,
                'coment' => $coment,
                'id' => $id,
                'longitud' => $longitud,
                'noms_usuaris' => $noms_autors,
                'id_usuaris' => $id_autors,
                'dates_imatges' => $dates_imatges,
                'titols_imatges' => $titols_imatges,
                'img_paths' => $img_paths,
                'num_visualitzacions' => $num_visualitzacions,
                'num_likes' => $num_likes,
                'aa_longituds' => $aa_longituds,
                'aa_usuari' => $aa_usuari,
                'aa_com' => $aa_com,
                'ha_comentat' => $aa_ha_comentat,
                'aa_id_usuaris' => $aa_id_autors,
                'aa_fotos_perfil' => $aa_fotos_perfil,
                'fotos_perfil_img' => $fotos_perfil_img,
                'aa_id_comentaris' => $aa_id_comentaris,
                'id_fotos' => $id_fotos
            ]);
            $response->setStatusCode($response::HTTP_OK);
            $response->headers->set('Content-type', 'text/html');
            $response->setContent($content);
            return $response;
        }
    }

    public function like_perfil(Application $app, $id, $id_user_perf)
    {
        $id_user = $app['session']->get('user')['id'];
        $images = $app['home']->busca_imatge($id, $app);
        $id_imatge = $images[0]['id'];
        $a = $app ['like'] -> comprova_like($id_user, $id_imatge, $app);
        if (!$a['id']) {
            //insert a la taula nou like
            $app ['like']->add($id_user, $id_imatge, $app);
            $cont = $app ['like']->likes_img($id_imatge, $app);
            $cont_act = $cont['likes'] + 1;
            $app['like']->actualitza($cont_act, $id_imatge, $app);
            $app['like']->crea_notificacio($id_user, $id_imatge, $images[0]['user_id'], $app);
        }
        else {
            $cont = $app ['like']->likes_img($id_imatge, $app);
            $cont_act = $cont['likes'] - 1;
            $app ['like']->actualitza($cont_act, $id_imatge, $app);
            $app ['like']->delete($id_imatge, $id_user, $app);
        }
        $url = '/perfil/'.$id_user_perf;
        return new RedirectResponse($url);


    }
    public function comenta_perfil(Request $request, Application $app, $id, $id_user_perf)
    {
        $response = new Response();
        if ($request->isMethod('POST')) {
            $id_user = $app['session']->get('user')['id'];
            $coment = $request->get('comenta');
            $images = $app['home']->busca_imatge($id, $app);
            $id_imatge = $images[0]['id'];
            try{
                $app['comenta'] -> add($id_user, $id_imatge, $coment, $app);
                $cont_act = ($images[0]['comentaris'] + 1);
                $app['comenta']->actualitza($cont_act, $id_imatge, $app);
                $app['comenta']->crea_notificacio2($id_user, $id_imatge, $images[0]['user_id'], $app);
                $url = '/perfil/'.$id_user_perf;
                return new RedirectResponse($url);
            } catch (Exeption $e) {
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                $content = $app['twig']->render('home.twig', [
                    'errors' => [
                        'unespected' => 'An error has ocurred'
                    ]
                ]);
                $response->setContent($content);
                return $response;
            }
        }
        $url = '/perfil/'.$id_user_perf;
        return new RedirectResponse($url);
    }

}