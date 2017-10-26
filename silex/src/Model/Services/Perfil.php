<?php

namespace SilexApp\Model\Services;

class Perfil{

    public function busca_user($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM usuari WHERE (id = $id)");
        return $a;
    }

    public function busca_publicacions($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id)");
        return $a;
    }

    public function busca_comentaris($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM comentaris WHERE (user_id = $id)");
        return $a;
    }
    public function busca_imatges_ocreacio_you($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id) ORDER BY id DESC");
        return $a;
    }
    public function busca_imatges_ocreacio($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id AND private = 0) ORDER BY id DESC");
        return $a;
    }
    public function busca_imatges_olikes_you($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id) ORDER BY likes DESC");
        return $a;
    }
    public function busca_imatges_olikes($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id AND private = 0) ORDER BY likes DESC");
        return $a;
    }
    public function busca_imatges_ocomentaris_you($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id) ORDER BY comentaris DESC");
        return $a;
    }
    public function busca_imatges_ocomentaris($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id AND private = 0) ORDER BY comentaris DESC");
        return $a;
    }
    public function busca_imatges_ovisualitzacions_you($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id) ORDER BY visits DESC");
        return $a;
    }
    public function busca_imatges_ovisualitzacions($app, $id){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (user_id = $id AND private = 0) ORDER BY visits DESC");
        return $a;
    }
}
