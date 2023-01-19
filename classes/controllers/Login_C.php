<?php
namespace classes\controllers;
use classes\models\UserMgr;
use classes\exceptions\AuthenticationError;

/**
 * Kontroler pro přihlášení
 */
class Login_C extends Main_C {
	/**
     * Hlavní metoda zpracování
	 * Pokud již je v session nastaven uživatel, nic nedělá a směřuje na domovskou stránkou
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {

        $userMgr = new UserMgr();

        if ($userMgr->getUser())
			$this->route('home');

        // Nastavení šablony a dat
		$this->view = 'login';
		$this->head['title'] = 'Přihlášení';
		if ($_POST)	{
			try	{
                $userMgr->login($_POST['email'], $_POST['password']);
                $this->addMessage('Byl jste úspěšně přihlášen.');
				$this->route('home');
			}
			catch (AuthenticationError $e) {
				$this->addMessage($e->getMessage());
			}
		}
    }
}