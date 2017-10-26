<?php

namespace SilexApp\Model\Services;

class Publica{

	public function add($id_user, $titol, $private, $path, $app){
        $app['db']->insert('imatge', [
            'user_id' =>$id_user,
            'title'=>$titol,
            'img_path'=>$path,
            'private'=>$private
        ]);
	}

    public function delete($id_imatge, $app){
        $app['db']->exec("DELETE FROM imatge WHERE id = '{$id_imatge}'");
    }

    public function modifica($id_imatge, $titol, $private, $app){
        $app['db']->exec("UPDATE imatge SET title = '{$titol}', private = '{$private}' WHERE id = '{$id_imatge}'");
    }
}
