<?php namespace Config;

define("ROOT",dirname(__DIR__) . "/");
define("VIEWS_PATH", ROOT . "Views/");
define("FRONT_ROOT", "http://localhost/git/trabajofinal4/");
//define("FRONT_ROOT", "http://localhost/trabajofinal4-master/");
//define("FRONT_ROOT", "http://localhost/Proyectos/trabajofinal4/");
//define("FRONT_ROOT", "http://localhost/Proyectos/trabajofinal4/"); 
define("FRONT_CSS", FRONT_ROOT . 'Views/css/');


//DATABASE

/* BACK */

define('DB_HOST', 'localhost');
define('DB_NAME', 'MoviePass');
define('DB_USER', "root");
define('DB_PASS', null);
