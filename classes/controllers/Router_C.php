<?php
namespace classes\controllers;

/**
 * Hlavní router kterým se začíná v indexu
 */
class Router_C extends Main_C {
    /**
     * Instance kontrolleru
     * @var Main_C
     */
	protected Main_C $myController;

    /**
     * Převod textu z URL na název třídy
     * @param $text
     * @return array|string|string[]
     */
	private function conversion($text) : string {
		//zatím pomlčky nepoužívám, ale kdyby, tak je odstraním
        $sentence = str_replace('-', ' ', $text);
        //první písmeno chci velké
		$sentence = ucwords($sentence);
		//mezery nepoužívám, ale kdyby, tak se jich zbavím
        $sentence = str_replace(' ', '', $sentence);
		return $sentence;
	}

    /**
     * Naparsuji URL adresu podle lomítek do pole
     * @param $url
     * @return array
     */
	private function parseURL($url) : array {
	    // naparsování části URL adresy do asociativního pole (oddělí domenu od parametru)
        $parsedURL = parse_url($url);
		// odstranění počátečního lomítka
		$parsedURL["path"] = ltrim($parsedURL["path"], "/");
		// odstranění bílych znaků
		$parsedURL["path"] = trim($parsedURL["path"]);
		// rozbití dle lomítek do pole
		$splittedPath = explode("/", $parsedURL["path"]);
		return $splittedPath;
	}

    /**
     * Vytvoření příslušného kontroleru dle URL
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {
		// kontroler je 1. parametr URL
		$parsedURL = $this->parseURL($params[0]);
        //jestli v parametru ještě nic není, jdu domů
		if(empty($parsedURL[0])){
			$this->route('Home');
		}
        //z URL získám název třídy (kontrolerům dávám záponu _C) a udělám instanci
		$ControllsersClass = $this->conversion(array_shift($parsedURL)) . '_C';
		if (file_exists('classes/controllers/' . $ControllsersClass . '.php')){
			$ControllsersClass = 'classes\controllers\\' . $ControllsersClass;
			$this->myController = new $ControllsersClass;
		}
		else
            $this->route("error");

		// Volání hlavní metody kontroleru
		try{
			$this->myController->process($parsedURL);
		}catch(\UnexpectedValueException $e){
			//echo('Došlo k chybě: ' . $e->getMessage());
			$this->route("error");
		}
		// nastavení hlavičky a nadpisu
		$this->data['title'] = $this->myController->head['title'];
		if(isset($_SESSION['menu']))
			$this->data['menu'] = $_SESSION['menu'];
		
		//nastavení systémpvých zpráv
		$this->data['messages'] = $this->getMessages();

		// nastavení hlavní šablony
		$this->view = 'layout';
    }
}