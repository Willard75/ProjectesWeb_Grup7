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

class LoginController
{
    public function postAction(Application $app, Request $request)
    {

        $response = new Response();
        if ($request->isMethod('POST')) {
            //validate
            $name = $request->get('username');
            $password = $request->get('password');
            $passhash = md5($password);
            $a = $app['login'] -> valida_login($name, $passhash, $app);
            if (!$a['username']) {
                print_r("Usuario incorrecto");
            }
            else {
                $app['session']->set('user', array('username' => $a['username'], 'id' => $a['id'], 'foto' => $a['img_path']));

                $url = '/';
                return new RedirectResponse($url);
            }
        }
        $content = $app['twig']->render('login.twig');
        $response->setContent($content);
        return $response;
    }

}