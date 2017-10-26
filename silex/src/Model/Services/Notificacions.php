<?php

namespace SilexApp\Model\Services;

class Notificacions{

	public function busca_notificacions($id_user, $app){
        $a = $app['db']->fetchAll("SELECT * FROM notificacions WHERE (user_id_to = '{$id_user}' AND readed = 0)");
        return $a;
	}
    public function busca_nom_usuari($id_user, $app){
        $b = $app['db']->fetchAll("SELECT username FROM usuari WHERE (id = '{$id_user}')");
        return $b;
    }
    public function busca_nom_img($img_id, $app){
        $b = $app['db']->fetchAll("SELECT title FROM imatge WHERE (id = '{$img_id}')");
        return $b;
    }
    public function notificacio_readed($id_notificacio, $app){
        $app['db']->exec("UPDATE notificacions SET readed = 1 WHERE id = '{$id_notificacio}'");
    }
    public function busca_notificacions_imatge($id_img, $app){
        $a = $app['db']->fetchAll("SELECT * FROM notificacions WHERE (img_id = '{$id_img}')");
        return $a;
    }
    public function delete_notificacions($id_img, $app){
        $app['db']->exec("DELETE FROM notificacions WHERE (img_id = '{$id_img}')");
    }
}
