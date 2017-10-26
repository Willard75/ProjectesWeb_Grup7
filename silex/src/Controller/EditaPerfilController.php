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

class EditaPerfilController
{
    public function edita(Application $app, Request $request, $id)
    {
        $response = new Response();
        $id_user = $app['session']->get('user')['id'];
        if ($id_user != null) {
            if ($id_user == $id) {
                //pilla el que et fa falta
                $u = $app['perfil']->busca_user($app, $id);
                $a_nom = $u[0]['username'];
                $a_email = $u[0]['email'];
                $a_img = $u[0]['img_path'];
                $a_data = $u[0]['birthday'];

                if ($request->isMethod('POST')) {
                    //agafem el id de l'usuari logejat

                    $name = $request->get('username');
                    $mail = $request->get('mail');
                    $date = $request->get('date');
                    $password = $request->get('password');
                    $passhash = md5($password);
                    //mirar que no existeixi abans ni el nom d'usuari ni el email
                    $usuaris = $app['add_user'] -> pilla_usuaris($app);
                    $size_usuaris = sizeof($usuaris);
                    $exist_n = 0;
                    $exist_e = 0;
                    for ($v = 0; $v < $size_usuaris; $v++) {
                        if ($name == $usuaris[$v]['username']){
                            $exist_n = 1;
                        }
                        if ($mail == $usuaris[$v]['email']){
                            $exist_e = 1;
                        }
                    }
                    if ($exist_e == 0) {
                        if ($exist_n == 0){
                            try {
                                $app['edita_perf']->editaPerfil($id_user, $name, $mail, $date, $passhash, $app);
                                $app['session']->set('user', array('username' => $name, 'id' => $id_user));
                                $url = '/';
                                return new RedirectResponse($url);
                            } catch (Exeption $e) {
                                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                                //printa error
                                $response = new Response();
                                $content = $app['twig']->render('error.twig', [
                                    'error' => "Ha ocurrido un error"
                                ]);
                                $response->setContent($content);
                                return $response;
                            }
                        }
                        else {
                            print_r("el nom d'usuari ja existeix");
                        }
                    }
                    else {
                        print_r("aquest email ja esta en us");
                    }
                }
                $content = $app['twig']->render('editaperfil.twig', [
                    'a_nom' => $a_nom,
                    'a_email' => $a_email,
                    'a_img' => $a_img,
                    'a_data' => $a_data
                ]);
                $response->setContent($content);
                return $response;
            }
            else {
                //printa error
                $response = new Response();
                $content = $app['twig']->render('error.twig', [
                    'error' => "Tu no eres este usuario"
                ]);
                $response->setContent($content);
                return $response;
            }
        }
        else {
            //printa error
            $response = new Response();
            $content = $app['twig']->render('error.twig', [
                'error' => "Tu no eres este usuario"
            ]);
            $response->setContent($content);
            return $response;
        }
    }
}