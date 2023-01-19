<?php
namespace classes\controllers;
use classes\models\UserMgr;

/**
 * Kontroler pro nastavování uživatelských rolí
 */
class Roles_C extends Main_C {
    /**
     * Hlavní metoda zpracování připraví proměnné pro volání šablony
     * Podle toho, co je v POSTu vyplněné se provádí patřičná editace profilu
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {

        $userMgr = new UserMgr();

        $user = $userMgr->getUser();

        // Nastavení šablony a dat
		$this->view = $this->authenticateUser(get_class($this));;
        $this->head['title'] = 'Správa uživatelských rolí';
		$this->data['clients'] = $userMgr->getClients();
		$this->data['roles'] = $userMgr->getRoles();
		$this->data['statuses'] = array('new', 'active', 'inactive', 'locked');
        $this->data['forAdminOnly'] = $user['role_id'] == 1 ? '' : 'disabled="disabled" tabindex="-1"';
        
		if($_POST && isset($_POST['id']) && $_POST['id']){
			$user = $userMgr->getUsersInfo($_POST['id'], 'id');
			$userMgr->SetProfileToSession($_POST['id']);
			//aktuální uživatel nemůže nastavit stejnou nebo vyšší roli
            if(isset($_POST['role_id']) && $_POST['role_id'] > $user['role_id'] && $_POST['id'] != $user['id'])
				$userMgr->updateProfile($_POST['id'], array('role_id' => $_POST['role_id']));
			//aktuální uživatel nemůže měnit svůj vlastní status
            if(isset($_POST['status']) && $_POST['id'] != $user['id'])
				$userMgr->updateProfile($_POST['id'], array('status' => $_POST['status']));
            $this->route('roles');
		}
        unset($_SESSION['profile']);
    }
}