<?php

namespace SilexApp\Model\Services;

class Comentari{

	public function add($id_user, $id_imatge, $coment, $app){
        $app['db']->insert('comentaris', [
            'user_id' =>$id_user,
            'img_id'=>$id_imatge,
            'content'=>$coment
        ]);
	}

	public function delete($id_comentari, $app){
        $app['db']->exec("DELETE FROM comentaris WHERE id = '{$id_comentari}'");
    }

    public function modifica($coment, $id_comentari, $app){
        $app['db']->exec("UPDATE comentaris SET content = '{$coment}' WHERE id = '{$id_comentari}'");
    }

    public function crea_notificacio2($id_user, $id_imatge, $id_user_to, $app){
        $app['db']->insert('notificacions', [
            'user_id' =>$id_user,
            'user_id_to' => $id_user_to,
            'img_id'=>$id_imatge,
            'type'=>1,
            'readed'=>0,
        ]);
    }
    public function actualitza($cont_act, $id_imatge, $app){
        $cont = $app['db']->exec("UPDATE imatge SET comentaris = '{$cont_act}' WHERE id = '{$id_imatge}'");
        return $cont;
    }
    public function imatge_del_comentari_id($id_comentari, $app){
        $a = $app['db']->fetchAll("SELECT * FROM comentaris WHERE (id = '{$id_comentari}')");
        return $a;
    }
    public function imatge_del_comentari($id, $app){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (id = '{$id}')");
        return $a;
    }
    public function comentaris_usuari($id_usuari, $app){
        $a = $app['db']->fetchAll("SELECT * FROM comentaris WHERE (user_id = '{$id_usuari}')");
        return $a;
    }
    public function showMore($id, $app){
        $a = $app['db']->fetchAll("SELECT * FROM comentaris WHERE ( img_id= '{$id}')");
        return $a;
    }
    public function Imatge_Last_Comentari($id, $app){
	  $a = $app['db']->fetchAll("SELECT * FROM comentaris WHERE ( img_id= '{$id}') ORDER BY id DESC LIMIT 1");
        return $a;
    }

    public function User_Logged_has_coment_for_this_image($id_user, $id_image, $app){
	    $a = $app['db']->fetchAssoc("SELECT * FROM comentaris WHERE ( img_id= '{$id_image}') AND ( user_id= '{$id_user}') ");
	    return $a;
    }




}
