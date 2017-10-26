<?php

namespace SilexApp\Model\Services;

class Like{

    public function add($id_user, $id_imatge, $app){
        $app['db']->insert('likes', [
            'user_id' =>$id_user,
            'img_id'=>$id_imatge,
        ]);
    }

    public function comprova_like($id_user, $id_imatge, $app){
        $a = $app['db']->fetchAssoc("SELECT * FROM likes WHERE (user_id = '{$id_user}' AND img_id='{$id_imatge}')");
        return $a;
    }

    public function likes_img($id_imatge, $app){
        $cont = $app['db']->fetchAssoc("SELECT likes FROM imatge WHERE id= '{$id_imatge}'");
        return $cont;
    }

    public function actualitza($cont_act, $id_imatge, $app){
        $cont = $app['db']->exec("UPDATE imatge SET likes = '{$cont_act}' WHERE id = '{$id_imatge}'");
        return $cont;
    }
    public function delete($id_imatge , $id_usuari, $app){
        $app['db']->exec("DELETE FROM likes WHERE (user_id = '{$id_usuari}' AND img_id = '{$id_imatge}')");
    }
    public function crea_notificacio($id_user, $id_imatge, $id_user_to, $app){
        $app['db']->insert('notificacions', [
            'user_id' =>$id_user,
            'user_id_to' => $id_user_to,
            'img_id'=>$id_imatge,
            'type'=>0,
            'readed'=>0,
        ]);
    }
    public function busca_likes($id_imatge, $app){
        $a = $app['db']->fetchAssoc("SELECT * FROM likes WHERE (img_id='{$id_imatge}')");
        return $a;
    }
    public function delete_likes($id_imatge, $app){
        $app['db']->exec("DELETE FROM likes WHERE (img_id = '{$id_imatge}')");
    }
}
