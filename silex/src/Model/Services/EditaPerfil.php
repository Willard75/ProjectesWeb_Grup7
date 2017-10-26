<?php

namespace SilexApp\Model\Services;

class EditaPerfil{
    public function editaPerfil($id_user, $name, $mail, $date, $passhash, $app){
        $app['db']->exec("UPDATE usuari SET username = '{$name}', email = '{$mail}', birthday = '{$date}', password = '{$passhash}' WHERE id = '{$id_user}'");
    }

}
