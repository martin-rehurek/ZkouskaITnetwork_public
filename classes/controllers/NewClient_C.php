<?php
namespace classes\controllers;
use classes\models\UserMgr;
use classes\exceptions\UpdateError;

/**
 * Kontroler pro založení nového uživatele
 */
class NewClient_C extends Main_C {
    /**
     * Hlavní metoda zpracování připraví proměnné pro volání šablony
	 * Pokud je něco v POSTu, zpracuje data z formuláře a přesměruje na jeho profil
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {

		$userMgr = new UserMgr();
		
		// Nastavení šablony a dat
        $this->view = $this->view = $this->authenticateUser(get_class($this));;
		$this->head['title'] = 'Nový klient';

		if ($_POST) {
			try	{
				$id = $userMgr->addClient(array('fname' => $_POST['fname'], 'lname' =>  $_POST['lname'], 'email' => $_POST['email'], 'phone' => $_POST['phone']));
				$userMgr->updatePassword($id, array('password' => $_POST['password'], 'passwordAgain' => $_POST['passwordAgain']));
				$userMgr->updateAddress($id, array('street' => $_POST['street'], 'city' => $_POST['city'], 'zip' => $_POST['zip']));
				$this->route('profile/'.$id);
			}
			catch (UpdateError $e) {
				$this->addMessage($e->getMessage());
			}
		}
    }
}