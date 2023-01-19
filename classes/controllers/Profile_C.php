<?php
namespace classes\controllers;
use classes\models\UserMgr;
use classes\models\ProductMgr;
use classes\exceptions\InternallError;
use classes\exceptions\UpdateError;

/**
 * Kontroler pro zpracovani domovske stranky
 */
class Profile_C extends Main_C {
    /**
     * Hlavní metoda zpracování připraví proměnné pro volání šablony
	 * Pokud je parametr prázný, edituje se přihlášený uživatel, jinak dle parametru
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {

        $userMgr = new UserMgr();
		$productMgr = new ProductMgr();

        $user = $userMgr->getUser();

        // Nastavení šablony a dat
        $this->view = $this->authenticateUser(get_class($this));;
		$this->head['title'] = 'Úprava profilu';
        $this->data['roles'] = $userMgr->getRoles();
		$this->data['authUserRole'] = $user['role_id'];

        if (empty($params[0])) { 
			$this->route('profile/'.$user['id']);	
        }
		else {
			try {
				$userMgr->SetProfileToSession($params[0]);
				$this->data['user'] = $_SESSION['profile'];
				$this->data['products'] = $productMgr->getProducts();
				$this->data['usersProducts'] = $productMgr->getUsersProducts($_SESSION['profile']['info']['id']);
			}
			catch (InternallError $e) {
				$this->addMessage($e->getMessage());
			}
		}

		if ($_POST) {
			try	{				
				$userMgr->updateProfile($_POST['id'], array('fname' => $_POST['fname'], 'lname' =>  $_POST['lname'], 'email' => $_POST['email'], 'phone' => $_POST['phone']));
				$userMgr->updateAddress($_POST['id'], array('street' => $_POST['street'], 'city' => $_POST['city'], 'zip' => $_POST['zip']));
				$userMgr->updatePassword($_POST['id'], array('password' => $_POST['password'], 'passwordAgain' => $_POST['passwordAgain']));
				$this->addMessage('Údaje aktualizovány');
				$this->route('profile/'.$_POST['id']);
			}
			catch (UpdateError $error) {
				$this->addMessage($error->getMessage());
			}
		}
		unset($_SESSION['profile']);
    }
}