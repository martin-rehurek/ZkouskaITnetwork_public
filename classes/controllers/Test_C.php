<?php
namespace classes\controllers;
use classes\models\UserMgr;
/**
 * Kontroler pro zpracování stránky na testování
 */
class Test_C extends Main_C {
    /**
     * Hlavní metoda zpracování pro testové účely
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {
        $userMgr = new UserMgr();
        $user = $userMgr->getUser();

        $this->view = $this->authenticateUser(get_class($this));;
        $this->head['title'] = 'Test';
        $this->data['user'] = $user;
    }
}