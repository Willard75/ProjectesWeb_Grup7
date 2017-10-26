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

class LogoutController
{
    public function logout(Application $app, Request $request)
    {

                $app['session']->remove('user');
                $app['session']->remove('id');

                $url = '/';
                return new RedirectResponse($url);
    }

}