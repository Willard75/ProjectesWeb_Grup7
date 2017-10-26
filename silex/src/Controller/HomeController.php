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

class HomeController
{
    public function inicialitza(Application $app)
    {

        $response = new Response();
        //**************
        $imagesmes = $app['home']->busca_imatges_mesvistes($app);
        $longitudmes = sizeof($imagesmes);
        $id_fotosmes = array();
        $titols_imatgesmes = array();
        $id_usuarismes = array();
        $noms_usuarismes = array();
        $dates_imatgesmes = array();
        $img_pathsmes = array();
        $num_visualitzacionsmes = array();
        $num_likesmes = array();
        for ($x = 0; $x < $longitudmes; $x++) {
            $nom_usuari = $app['home']->busca_nom_usuari($imagesmes[$x]['user_id'], $app);
            array_push($noms_usuarismes, $nom_usuari[0]['username']);

            array_push($id_fotosmes, $imagesmes[$x]['id']);
            array_push($titols_imatgesmes, $imagesmes[$x]['title']);
            array_push($id_usuarismes, $imagesmes[$x]['user_id']);
            array_push($dates_imatgesmes, $imagesmes[$x]['created_at']);
            array_push($img_pathsmes, $imagesmes[$x]['img_path']);
            array_push($num_visualitzacionsmes, $imagesmes[$x]['visits']);
            array_push($num_likesmes, $imagesmes[$x]['likes']);
        }

        $images = $app['home']->busca_imatges($app);
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
            for($j = 0; $j < $longitud_com; $j++){
                $u = $app['home']->busca_nom_usuari($comentaris[$j]['user_id'], $app);
                array_push($usuari_com, $u[0]['username']);
                array_push($fotos, $u[0]['img_path']);
                array_push($id_autors_com, $comentaris[$j]['user_id']);
                array_push($com, $comentaris[$j]['content']);
                //id comentaris
                array_push($id_comentari, $comentaris[$j]['id']);
                if(isset($app['session']->get('user')['id'])) {
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
        //print_r($aa_id_autors);
        $content = $app['twig']->render('home.twig', [
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
            'id_fotos' => $id_fotos,
            'longitudmes' => $longitudmes,
            'id_fotosmes' => $id_fotosmes,
            'titols_imatgesmes' => $titols_imatgesmes,
            'id_usuarismes' => $id_usuarismes,
            'noms_usuarismes' => $noms_usuarismes,
            'dates_imatgesmes' => $dates_imatgesmes,
            'img_pathsmes' => $img_pathsmes,
            'num_visualitzacionsmes' => $num_visualitzacionsmes,
            'num_likesmes' => $num_likesmes
        ]);
        $response->setContent($content);
        return $response;
    }

    public function like_home(Application $app, $id)
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
        $url = '/';
        return new RedirectResponse($url);


    }
    public function comenta_home(Request $request, Application $app, $id)
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
                $url = '/';
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
        $url = '/';
        return new RedirectResponse($url);
    }
    public function mostraImatges(Request $request, Application $app){
	    $arrayImatges = $app['home'] -> busca_totes_imatges($app);
	    $numImatges = sizeof($arrayImatges);
	    $id_user = $app['session']->get('user')['id'];
	    $HiHaComentari = 'false';
	    for($i = 0; $i < $numImatges; $i++){
		    $idImatge = $arrayImatges[$i]['id'];

		    if($id_user!='null'){
			    $COM = $app['comenta']->User_Logged_has_coment_for_this_image($id_user, $idImatge, $app);
			    $HiHaComentari = $COM;
			    if($COM > 0){
				    $HiHaComentari = 'true';
			    }
		    }
		    $ComentariImatge = $app ['comenta']->Imatge_Last_Comentari($idImatge,$app);
		    $arrayImatges[$i]['comentari'] = $ComentariImatge;
		    $arrayImatges[$i]['userComented'] = $HiHaComentari;

		    }

		    $GoodArray = array('imgs' => $arrayImatges);
		    $GoodArray['session'] = $id_user;
		     


		    return json_encode($GoodArray);
	    }


}


//******
