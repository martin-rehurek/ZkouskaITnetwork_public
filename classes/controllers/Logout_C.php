<?php
namespace classes\controllers;
use classes\models\UserMgr;

/**
 * Kontroler pro odhlášení
 */
class Logout_C extends Main_C {
    /**
     * Hlavní metoda zpracování
	 * Odstraní info o uživateli v session a směřuje na login
     * @param array $params
     * @return void
     */	
    public function process(array $params) : void {

        $userMgr = new UserMgr();

        $userMgr->logout();
        $this->route('login');
    }
}