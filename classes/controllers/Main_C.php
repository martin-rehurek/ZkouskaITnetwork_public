<?php
namespace classes\controllers;
use classes\models\UserMgr;
/**
 * Výchozí kontroler
 */
abstract class Main_C {
    /**
     * @var array - z indexu tohoto pole čtu v šablonách
     */
    protected array $data = array();
    /**
     * @var string - název šablony
     */
    protected string $view = "";
    /**
     * @var array|string[] - hlavička HTML stránky a nadpis
     */
	protected array $head = array('title' => '');
    /**
     * Requiruje podled dle hodnoty uložene v $this->view
     * @return void
     */
    public function getView() : void {
        if ($this->view) {
            extract($this->data);
			require("views/" . $this->view . ".phtml");
        }
    }
    /**
     * Přesměruje na URL dle parametru
     * @param string $url
     * @return never
     */
	public function route(string $url) : never	{
        header("Location: /$url");
		header("Connection: close");
        exit;
	}

    /**
     * Přidá zprávu pro uživatele
     * @param string $message Hláška k zobrazení
     * @return void
     */
    public function addMessage(string $message) : void {
        if (isset($_SESSION['messages']))
            $_SESSION['messages'][] = $message;
        else
            $_SESSION['messages'] = array($message);
    }

    /**
     * Vrátí zprávy pro uživatele
     * @return array Všechny uložené hlášky k zobrazení
     */
    public function getMessages() : array {
        if (isset($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
            return $messages;
        }
        else
            return array();
    }

    /**
     * Ověří, zda je přihlášený uživatel (vyplněná session), případně přesměruje na login
     * Ověří, zda je právě otevírané menu v seznamu povolených a pokud ano, vrátí jeno návev, který rovnou použiji pro nastavení pohledu
     * @return string
     */
    public function authenticateUser($whereAmI) : string {
        //extrahuji název objektu = url = pohled
        $whereAmI = strtolower($whereAmI);
        $whereAmI = str_replace("classes\controllers\\", "", $whereAmI);
        $whereAmI = str_replace("_c", "", $whereAmI);

        $userMgr = new UserMgr();
        //echo $whereAmI; print_r($userMgr->getAllowedUrls());exit(); //debug
        if (in_array($whereAmI, $userMgr->getAllowedUrls())){
            return $whereAmI;
        }
        else{
            $this->addMessage('Je nutné se přihlásit.');
            $this->route('logout');
        }
    }

    /**
     * Hlavni metoda controlleru
     * @param array $params - pole parametru pro vyuziti kontrolerem
     * @return void
     */
    abstract function process(array $params) : void;

}