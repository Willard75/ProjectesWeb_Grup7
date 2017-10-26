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

class ValidaMailController
{
    public function validamail(Application $app, Request $request, $hash)
    {
        //busca usuaris
        $usuaris = $app['add_user'] -> pilla_usuaris($app);
        $size_usuaris = sizeof($usuaris);
        for ($x = 0; $x < $size_usuaris; $x++) {
            print_r(md5($usuaris[$x]['id']));
            print_r("-");
            print_r($hash);
            print_r("\n");
            if (md5($usuaris[$x]['id']) == $hash){
                $app['add_user'] -> modifica_usuari($app, $usuaris[$x]['id']);
                //logeja al usuari
                $app['session']->set('user', array('username' => $usuaris[$x]['username'], 'id' => $usuaris[$x]['id'], 'foto' => $usuaris[$x]['img_path']));
            }
        }
        //retorna a la home
        $url = '/';
        return new RedirectResponse($url);
    }

}