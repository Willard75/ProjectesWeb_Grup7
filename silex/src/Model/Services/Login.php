<?php

namespace SilexApp\Model\Services;

class Login{

	public function valida_login($name, $passhash, $app){
        $a = $app['db']->fetchAssoc("SELECT * FROM usuari WHERE ((username = '{$name}' AND password = '{$passhash}') OR (email = '{$name}' AND password = '{$passhash}'))");
        return $a;
	}
}
