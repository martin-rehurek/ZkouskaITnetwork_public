<?php
namespace classes\controllers;
use classes\models\UserMgr;

/**
 * Kontroler pro práci s klienty
 */
class Clients_C extends Main_C {
    /**
     * Hlavní metoda zpracování připraví proměnné pro volání šablony
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {

        $userMgr = new UserMgr();
        
        //nastavení šablony a dat
		$this->view = $this->view = $this->authenticateUser(get_class($this));;
        $this->head['title'] = 'Seznam klientů';
		$this->data['clients'] = $userMgr->getClients();
    }
}