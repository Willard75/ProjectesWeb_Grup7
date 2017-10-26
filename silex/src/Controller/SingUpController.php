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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPMailer;


class SingUpController
{

    public function postAction(Application $app, Request $request)
    {
        $response = new Response();
        if ($request->isMethod('POST')) {
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
                    try{
                        $original_filename = $request->files->get('photo');

                        if ($original_filename == ""){
                            $app['add_user'] -> add($name, $mail, $date, $passhash, $app);
                            print_r("no te foto");
                        }
                        else {
                            $path = 'assets/images/';
                            $filename = $original_filename->getClientOriginalName();
                            /** @var UploadedFile $original_filename */
                            print_r("te foto");
                            try {
                                $original_filename->move($path, $filename);
                                $app['add_user'] -> add_amb_foto($name, $mail, $date, $passhash, $path.$filename, $app);
                            } catch (\Exception $e) {
                                var_dump($e->getMessage());
                            }
                        }
                        //$app['add_user'] -> add($name, $mail, $date, $passhash, $app);



                        $a = $app['login'] -> valida_login($name, $passhash, $app);
                        $app['session']->set('user', array('username' => $a['username'], 'id' => $a['id'], 'foto' => $a['img_path']));
                        //enviar email--------------------------------
                        $hash = md5($a['id']);
                        $mail = new PHPMailer();

                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'grup7projectesweb@gmail.com';
                        $mail->Password = '123456qQ';
                        $mail->SMTPSecure = 'tls';

                        $mail->From = 'grup7projectesweb@gmail.com';
                        $mail->FromName = 'PWgram';
                        $mail->addAddress($a['email'], $a['username']);

                        //$mail->addReplyTo('raj.amalw@gmail.com', 'Raj Amal W');

                        $mail->WordWrap = 50;
                        $mail->isHTML(true);

                        $mail->Subject = 'Valida tu cuenta';
                        $mail->Body    = 'www.grup7.com/validamail/'.$hash;

                        if(!$mail->send()) {
                            echo 'Message could not be sent.';
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                            exit;
                        }

                        echo 'Message has been sent';

                        // -------------------------------------------
                        $url = '/';
                        return new RedirectResponse($url);
                    } catch (Exeption $e) {
                        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                        $content = $app['twig']->render('singup.twig', [
                            'errors' => [
                                'unespected' => 'An error has ocurred'
                            ]
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
        $content = $app['twig']->render('singup.twig');
        $response->setContent($content);
        return $response;
    }

}