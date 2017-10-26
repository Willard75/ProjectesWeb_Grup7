<?php
use Silex\Application;
$app = new Application();


//class calculator
$app ['calc'] = function(){
    return new \SilexApp\Model\Services\Calculator();
};


//aqui comença lo bo
$app ['add_user'] = function(){
    return new \SilexApp\Model\Services\SingUp();
};

$app ['login'] = function(){
    return new \SilexApp\Model\Services\Login();
};

$app ['publica'] = function(){
    return new \SilexApp\Model\Services\Publica();
};

$app ['comenta'] = function(){
    return new \SilexApp\Model\Services\Comentari();
};

$app ['edita_perf'] = function(){
    return new \SilexApp\Model\Services\EditaPerfil();
};

$app ['like'] = function(){
    return new \SilexApp\Model\Services\Like();
};

$app ['visualitzacions'] = function(){
    return new \SilexApp\Model\Services\Visualitzacions();
};

$app ['notificacions'] = function(){
    return new \SilexApp\Model\Services\Notificacions();
};

$app ['home'] = function(){
    return new \SilexApp\Model\Services\Home();
};

$app ['perfil'] = function(){
    return new \SilexApp\Model\Services\Perfil();
};

return $app;