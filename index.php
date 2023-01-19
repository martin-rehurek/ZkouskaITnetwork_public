<?php

use classes\models\Db;
use classes\config\Cfg;
use classes\controllers\Router_C;

session_start();

// interni kodovani pro funkce pro praci s retezci
mb_internal_encoding("UTF-8");

/*
//Vypis dostupnych db driveru: //debug
foreach(PDO::getAvailableDrivers() as $driver) {
  echo $driver.'<br />';
}
*/

//fce pro autoloader trid
function autoloader(string $class) : bool{	//echo "<br />Parametr autoloaderu: $class "; //debug
	$class = str_replace('\\', '/', $class);
        return require_once ("$class.php");
}

// autoloader trid
spl_autoload_register("autoloader");

// pripojeni k db
Db::connectMySQL(Cfg::H, Cfg::U, Cfg::P, Cfg::D); //pozdeji asi nahradim konkretnim uzivatelem

// vytvoreni routeru pro zpracovani parametru z URL
$router = new Router_C();

//zpracovani parametru z URL
$router->process(array($_SERVER['REQUEST_URI']));

// generovani sablony
$router->getView();
