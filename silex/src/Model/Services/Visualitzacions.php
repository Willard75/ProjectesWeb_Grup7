<?php

namespace SilexApp\Model\Services;

class Visualitzacions{
    public function visualitzacions_img($id_imatge, $app){
        $cont = $app['db']->fetchAssoc("SELECT * FROM imatge WHERE id= '{$id_imatge}'");
        return $cont;
    }

    public function actualitza($id_imatge, $cont_act, $app){
        $cont = $app['db']->exec("UPDATE imatge SET visits = '{$cont_act}' WHERE id = '{$id_imatge}'");
        return $cont;
    }
    public function busca_comentaris($id_imatge, $app){
        $cont = $app['db']->fetchAll("SELECT * FROM comentaris WHERE (img_id = '{$id_imatge}')");
        return $cont;
    }
}
