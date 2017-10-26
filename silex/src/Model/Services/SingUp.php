<?php

namespace SilexApp\Model\Services;

class SingUp{

	public function add($name, $mail, $date, $passhash, $app){
        $app['db']->insert('usuari', [
            'username' =>$name,
            'email'=>$mail,
            'birthday'=>$date,
            'password'=>$passhash
        ]);
	}
    public function add_amb_foto($name, $mail, $date, $passhash, $foto, $app){
        $app['db']->insert('usuari', [
            'username' =>$name,
            'email'=>$mail,
            'birthday'=>$date,
            'password'=>$passhash,
            'img_path' => $foto
        ]);
    }
    public function pilla_usuaris($app){
        $a = $app['db']->fetchAll("SELECT * FROM usuari");
        return $a;
    }
    public function modifica_usuari($app, $id){
        $app['db']->exec("UPDATE usuari SET active = 1 WHERE id = '{$id}'");
    }
}
