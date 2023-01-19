<?php
namespace classes\controllers;
use classes\captcha\CaptchaYear;
use classes\models\UserMgr;
use classes\exceptions\AuthenticationError;

/**
 * Kontroler pro zpracování registrace
 */
class Register_C extends Main_C {
	/**
     * Hlavní metoda zpracování připraví proměnné pro volání šablony
	 * Pokud je něco v POSTu, zpracuje přihlášení
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {
        
		$captcha = new CaptchaYear();
        
		// Nastavení šablony a dat
		$this->view = 'register';
		$this->head['title'] = 'Registrace';
		$this->data['captcha'] = array('HtmlCode'=> $captcha->getHtmlCode());
		
		if ($_POST)	{
			try	{
				$userMgr = new userMgr();
				if($captcha->verify($_POST['captcha'])){	
					$userMgr->register($_POST['email'], $_POST['password'], $_POST['passwordAgain']);
				}
				else{
					$this->addMessage('Captcha nesouhlasí');
				}				
				$this->route('home');
			}
			catch (AuthenticationError $e)			{
				$this->addMessage($e->getMessage());
			}
		}
    }
}