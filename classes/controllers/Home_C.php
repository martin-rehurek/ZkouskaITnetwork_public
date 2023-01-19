<?php
namespace classes\controllers;
use classes\models\UserMgr;
use classes\models\ProductMgr;

/**
 * Kontroler pro zpracování domovské stránky
 */
class Home_C extends Main_C {
    /**
     * Hlavní metoda zpracování připraví proměnné pro volání šablony
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {

        $userMgr = new UserMgr();
        $productMgr = new ProductMgr();

        $user = $userMgr->getUser();
        // Nastavení šablony a dat
        $this->view = $this->authenticateUser(get_class($this));
        $this->head['title'] = 'Domovská stránka';
        $this->data['email'] = $user['email'];
        $this->data['role_id'] = $user['role_id'];
        $this->data['menu'] = $user['menu'];
        $this->data['usersStats'] = $userMgr->getUsersStats($user['id']);
        if($user['role_id']<5)
            $this->data['productsStats'] = $productMgr->getProductsStats();
    }
}