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

class ImageViewerController
{
    public function inicialitza(Application $app, $img_id)
    {
        $id_user = $app['session']->get('user')['id'];
        $id_imatge = $img_id;
        $cont = $app ['visualitzacions']->visualitzacions_img($id_imatge, $app);
        if ($cont == null){
            //printa error
            $response = new Response();
            $content = $app['twig']->render('error.twig', [
                'error' => "La imatge no existeix"
            ]);
            $response->setContent($content);
            return $response;
        }
        else {
            if ($cont['private'] == 1) {
                if ($id_user != $cont['user_id']) {
                    $url = '/';
                    return new RedirectResponse($url);
                }
            }
            $cont_act = $cont['visits'] + 1;
            $app ['visualitzacions']->actualitza($id_imatge, $cont_act, $app);
            $cont = $app ['visualitzacions']->visualitzacions_img($id_imatge, $app);
            //buscar tot lo necesari per fer el twig de cada imatge
            $id_user_imatge = $cont['user_id'];
            $nom_usuari = $app['home']->busca_nom_usuari($cont['user_id'], $app);
            $temps = strtotime($cont['created_at']);
            $now = strtotime(date("Y-m-d H:i:s"));
            $datediff = $now - $temps;
            $dies_q_fa = intval($datediff / (60 * 60 * 24));
            $titular = $cont['title'];
            $likes = $cont['likes'];
            $views = $cont['visits'];
            $img_path = $cont['img_path'];
            $comentari = $app ['visualitzacions']->busca_comentaris($id_imatge, $app);
            $long_comentari = sizeof($comentari);
            $a_contingut = array();
            $a_user_com = array();
            $a_user_img = array();
            $a_id_user_com = array();
            $a_id_com = array();
            $ha_comentat = 0;
            $num_coment = 3;
            for ($q = 0; $q < $long_comentari; $q++) {
                array_push($a_contingut, $comentari[$q]['content']);
                array_push($a_id_com, $comentari[$q]['id']);
                $user_com = $app['home']->busca_nom_usuari($comentari[$q]['user_id'], $app);
                if ($comentari[$q]['user_id'] == $id_user) {
                    $ha_comentat = 1;
                }
                array_push($a_user_com, $user_com[0]['username']);
                array_push($a_user_img, $user_com[0]['img_path']);
                array_push($a_id_user_com, $user_com[0]['id']);

            }
            $content = $app['twig']->render('imageviewer.twig', [
                'nom_usuari' => $nom_usuari[0]['username'],
                'imatge_usuari' => $nom_usuari[0]['img_path'],
                'dies' => $dies_q_fa,
                'titular' => $titular,
                'likes' => $likes,
                'views' => $views,
                'img_path' => $img_path,
                'long_comentari' => $long_comentari,
                'a_contingut' => $a_contingut,
                'a_user_com' => $a_user_com,
                'a_user_img' => $a_user_img,
                'ha_comentat' => $ha_comentat,
                'a_id_user_com' => $a_id_user_com,
                'a_id_com' => $a_id_com,
                'id' => $id_imatge,
                'id_user_imatge' => $id_user_imatge,
                'num_coment' => $num_coment
            ]);
            $response = new Response();
            $response->setStatusCode($response::HTTP_OK);
            $response->headers->set('Content-type', 'text/html');
            $response->setContent($content);
            return $response;
        }
    }

    public function like_imageviewer(Application $app, $id)
    {
        $id_user = $app['session']->get('user')['id'];
        $images = $app['home']->busca_imatge($id, $app);
        $id_imatge = $images[0]['id'];
        $a = $app ['like'] -> comprova_like($id_user, $id_imatge, $app);
        //no sumis nova visualitzacio
        $cont = $app ['visualitzacions']->visualitzacions_img($id, $app);
        $cont_act = $cont['visits'] - 1;
        $app ['visualitzacions']->actualitza($id, $cont_act, $app);

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
        $url = '/imageviewer/'.$id;
        return new RedirectResponse($url);


    }

    public function comenta_imageviewer(Request $request, Application $app, $id)
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

                //no sumis nova visualitzacio
                $cont = $app ['visualitzacions']->visualitzacions_img($id, $app);
                $cont_act = $cont['visits'] - 1;
                $app ['visualitzacions']->actualitza($id, $cont_act, $app);

                $url = '/imageviewer/'.$id;
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
        $url = '/imageviewer/'.$id;
        return new RedirectResponse($url);
    }


    public function mescomentaris(Request $request, Application $app)
    {
        $idImage = $_POST['id'];
        $numComentaris = $_POST['numComents'] + 1;
        //echo $idImage;
        $nextComents = array();
        //$AllComents = $app['home']->busca_comentaris($idImage,$app);
        //echo $numComentaris;
        $allComents = $app['comenta']->showMore($idImage,$app);
        $QuantsTenim = sizeof($allComents);
        //print_r($QuantsTenim);
        for($i = $numComentaris; $i <$QuantsTenim; $i++){
                $g = $app['home']->busca_nom_usuari($allComents[$i]['user_id'],$app);
                //array_push($allComents[$i], 'nom' => $g[0][username]);
                $allComents[$i]['nom'] = $g[0]['username'];
                $allComents[$i]['img_u'] = $g[0]['img_path'];
                $allComents[$i]['id_user_log'] = $app['session']->get('user')['id'];
                array_push($nextComents,$allComents[$i]);
        }
        return json_encode(array('count' => $nextComents));
    }

}
