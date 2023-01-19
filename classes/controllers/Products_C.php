<?php
namespace classes\controllers;
use classes\models\UserMgr;
use classes\models\ProductMgr;
use classes\exceptions\InternallError;
use classes\exceptions\UpdateError;

/**
 * Kontroler pro pr8ci s produkty
 */
class Products_C extends Main_C {
    /**
     * Hlavní metoda zpracování připraví proměnné pro volání šablony
	 * Pacuje s parametry:
	 * /add - přiřadí produk klientovi
	 * /new - založí nový produkt
	 * /edit - úprava produktu
	 * /del - odstranění produktu
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {

		$userMgr = new UserMgr();
		$productMgr = new ProductMgr();

        $user=$userMgr->getUser();

		// Nastavení šablony
        $this->view = $this->authenticateUser(get_class($this));;
		$this->head['title'] = 'Správa produktů';
		if($user['role_id']<=3)
			$this->data['products'] = $productMgr->getProducts();
		
		if (!empty($params[0]) && $params[0] == 'del') { 
            try {
				$productMgr->deleteProduct($params[1]);
				$this->route('Products');
			}
			catch (InternallError $error) {
				$this->addMessage($error->getMessage());
			}
        }
		else if (!empty($params[0]) && $params[0] == 'edit'){
			if(!empty($params[1]) && $params[1] > 0){
				try {
					$_SESSION['product'] = $productMgr->getProduct($params[1]);
					$this->route('Products');
				}
				catch (InternallError $error) {
					$this->addMessage($error->getMessage());
				}
			}else{
				try	{				
					$productMgr->updateProduct($_POST['id'], $_POST['name'], $_POST['description']);
				}
				catch (UpdateError $error) {
					$this->addMessage($error->getMessage());
				}
				$this->route('Products');
			}
		}
		else if (!empty($params[0]) && $params[0] == 'new'){
			if(isset($_POST['name']) && $_POST['name'] != ""){
				$productMgr->addProduct($_POST['name'], $_POST['description']);
				$this->addMessage('Údaje byly úspěšně aktualizovány.');
				$this->route('Products');
			}
		}
		else if (!empty($params[0]) && $params[0] == 'add'){
			if(isset($_POST['user_id']) && $_POST['user_id'] != ""){				
				$productMgr->addProductToUser(array('user_id' => $_POST['user_id'], 'product_id' => $_POST['product_id'], 'subject' => $_POST['subject'], 'value' => $_POST['value'], 'valid_from' => $_POST['valid_from'], 'valid_to' => $_POST['valid_to']));
				$this->route('profile/'.$_POST['user_id']);
			}
		}
		unset($_SESSION['product']);
    }
}