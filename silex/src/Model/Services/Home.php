<?php

namespace SilexApp\Model\Services;

class Home{

	public function busca_imatges($app){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (private = 0) ORDER BY id DESC LIMIT 5");
        return $a;
	}
    public function busca_imatges_mesvistes($app){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (private = 0) ORDER BY visits DESC LIMIT 5");
        return $a;
    }
    public function busca_nom_usuari($id_user, $app){
        $b = $app['db']->fetchAll("SELECT * FROM usuari WHERE (id = '{$id_user}')");
        return $b;
    }
    public function busca_comentaris($id_img, $app){
        $c = $app['db']->fetchAll("SELECT * FROM comentaris WHERE (img_id = '{$id_img}')");
        return $c;
    }
    public function busca_comentari($id, $app){
        $c = $app['db']->fetchAll("SELECT * FROM comentaris WHERE (id = '{$id}')");
        return $c;
    }
    public function busca_imatge($id, $app){
        $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (id = '{$id}')");
        return $a;
    }
    public function busca_totes_imatges($app){
      $a = $app['db']->fetchAll("SELECT * FROM imatge WHERE (private = 0) ORDER BY id DESC ");
      return $a;
    }
}
