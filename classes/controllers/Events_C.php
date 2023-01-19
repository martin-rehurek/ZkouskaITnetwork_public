<?php
namespace classes\controllers;
use classes\models\UserMgr;
use classes\models\ProductMgr;

/**
 * Kontroler pro práci s pojistnými událostmi
 */
class Events_C extends Main_C {
    /**
     * Hlavní metoda zpracování připraví proměnné pro volání šablony
     * Nejdřív se zvolí klient, pak (dle údaje z POSTu) přeroutuju na /user_id, abych uchoval informaci o zvoleném klientovi.
     * Pak se volí produkt a doplní se zbývající údaje pro zápis pojistné události
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {

        $userMgr = new UserMgr();
        $productMgr = new ProductMgr();
        
        //nastavení pohledu a dat
        $this->view = $this->view = $this->authenticateUser(get_class($this));;
        $this->head['title'] = 'Správa pojistných událostí';
        $clients = $userMgr->getClients();
        $clients[count($clients)] = array('id'=>0, 'fname'=>'Zvolte', 'lname'=>'Klienta', 'email'=>'');//nastavím výchozí informační položku
        $this->data['clients'] = $clients;

        if (!empty($params[0]) && $params[0] > 0){
            $this->data['user'] = array('user_id' => $params[0]);
            $this->data['usersProducts'] = $productMgr->getUsersProducts($params[0]);
            $this->data['usersEvents'] = $productMgr->getUsersEvents($params[0]);
        }else{
            $this->data['user'] = array('user_id' => 0); //výchozí hodnoty, když ještě není zvolený klient
            $this->data['usersProducts'] = array(array('product_id'=>0, 'name'=>'Nejprve', 'description'=>'zvolte', 'value'=>'uživatele', 'valid_to'=>' '));
            $this->data['usersEvents'] = array(array('name'=>'', 'description'=>'', 'subject'=>'', 'value'=>'', 'author'=>'', 'event'=>'', 'created'=>''));
        }

        if($_POST){
            if(isset($_POST['product_id']) && $_POST['product_id'] > 0){
                $productMgr->addEvent(array('user_id'=>$_POST['user_id'], 'product_id'=>$_POST['product_id'], 'author_id'=>$_SESSION['current_user']['id'], 'comment'=>$_POST['event']));
            }
            if(isset($_POST['user_id']) && $_POST['user_id'] > 0){
                $this->route('events/'.$_POST['user_id']);
            }
            $this->data['usersEvents'] = $productMgr->getUsersEvents($_POST['user_id']);
        }
    }
}