<?php
namespace classes\models;
use classes\exceptions\AuthenticationError;
use classes\exceptions\InternallError;
use PDOException;

/**
 * Správa produktů
 */
class ProductMgr {

  /**
   * Získání informací o produktu dle ID
   * @param $id
   * @return array
   * @throws InternallError
   */
  public function getProduct(string $id) : array {
    $product = Db::queryOne("
    SELECT id, name, description, status, created
    FROM dbc_products
    WHERE id = ?;
        ", array($id));
        if (!$product)
            throw new InternallError('Vnitřní chyba při získání produktu.');
    return $product;
  }

  /**
   * Získání seznamu aktivních produktů
   * @return array
   * @throws InternallError
   */
  public function getProducts() : array {
    $products = Db::queryAll("
    SELECT id, name, description, status, created
    FROM dbc_products
    WHERE status = 'active'
    ORDER BY created DESC;
        ", array());
    if (!$products)
        return array();
    return $products;
  }

  /**
   * Získání seznamu produktů konkrétního uživatele
   * @param $id
   * @return array
   */
  public function getUsersProducts($id) : array {
    $products = Db::queryAll("
      SELECT product_id, name, description, subject, value, valid_from, valid_to 
      FROM dbc_user_product 
      JOIN dbc_products on dbc_user_product.product_id = dbc_products.id 
      WHERE user_id = ?;
        ", array($id));
    if (!$products)
        return array();
    return $products;
  }

  /**
   * Založení nového produktu
   * @param $name - jméno produktu
   * @param $desc - popis produktu
   * @return void
   * @throws InternallError
   */
  public function addProduct($name, $desc) : void {
    if (!Db::insert('dbc_products', array('name' => $name, 'description' => $desc)))
          throw new InternallError('Vnitřní chyba při vkládání dat: '.print_r(array('name' => $name, 'description' => $desc)));
  }

  /**
   * Přiřazení produktu konkrétnímu uživateli
   * @param array $params - asociatvní pole s prvky 'user_id', 'product_id', 'product_id', 'subject', 'value','valid_from', 'valid_to'
   * @return void
   * @throws InternallError
   */
  public function addProductToUser(array $params) : void {
    if (!Db::insert('dbc_user_product', $params))
          throw new InternallError('Vnitřní chyba při sjednávání kontraktu: '.print_r($params));
  }

  /**
   * Výpověď smlouvy uživatele
   * Nastaví konec platnosti smlouvy na aktuální den
   * @param $id
   * @return void
   * @throws InternallError
   */
  public function deleteUsersProduct($id) : void {
  if (!Db::update('dbc_user_product', $id, array('valid_to' => date('Y-m-d'))))
          throw new InternallError('Vnitřní chyba při úpravě dat. id: '.$id.' '.print_r(array('name' => $fname, 'description' => $desc)));
  }

  /**
   * Editace produktu
   * @param $id
   * @param $name
   * @param $desc
   * @return void
   */
  public function updateProduct($id, $name, $desc) : void {
    Db::update('dbc_products', 'id', $id, array('name' => $name, 'description' => $desc));
    //budu muset ošetřit, kdyby někdo dal editaci a přitom nic nezměnil
    //if (!Db::update('dbc_products', 'id', $id, array('name' => $name, 'description' => $desc)))
          //throw new InternallError('Vnitřní chyba při úpravě dat. id: '.$id.' '.print_r(array('name' => $name, 'description' => $desc)));
  }

  /**
   * Smazání produktu
   * Nastaví status na 'retired'
   * @param $id
   * @return void
   */
  public function deleteProduct($id) : void {
    if (!Db::update('dbc_products', 'id', $id, array('status' => 'retired')))
      throw new InternallError('Vnitřní chyba při úpravě dat. id: '.$id);
  }

  /**
   * Zadání pojistné události
   * @param array $params - asociatvní pole s prvky 'user_id', 'product_id', 'author_id', 'comment'
   * @return void
   * @throws InternallError
   */
  public function addEvent(array $params) : void {
    if (!Db::insert('dbc_user_event', $params))
      throw new InternallError('Vnitřní chyba při vkládání dat: '.print_r($params));
  }

  /**
   * Získání seznamu pojistných událostí zvoleného klienta
   * @param $id
   * @return array
   */
  public function getUsersEvents($id) : array {
		$products = Db::queryAll("
    SELECT dbc_products.name as name, dbc_user_product.subject as subject, dbc_user_product.value as value, dbc_users.fname as author, dbc_user_event.comment as event,  dbc_user_event.created as created FROM `dbc_user_event`
      JOIN dbc_users ON dbc_user_event.author_id = dbc_users.id
      JOIN dbc_products ON dbc_user_event.product_id = dbc_products.id
      JOIN dbc_user_product ON dbc_user_product.product_id = dbc_products.id
    WHERE dbc_user_event.user_id = ?;
        ", array($id));
		if (!$products)
        return array();
		return $products;
  }

  /**
   * Systémový report
   * @return array
   */
  public function getProductsStats() : array {
    $productsStats = Db::queryOne("
    SELECT count(*) AS contracts, 
            (
              SELECT count(*) FROM dbc_user_event
            ) AS events,
            (
              SELECT count(*) FROM dbc_products WHERE status = 'active'
            ) AS products 
        FROM dbc_user_product
        ", array());
        if (!$productsStats)
            throw new InternallError('Vnitřní chyba při získání statistických údajů.');
    return $productsStats;
  }
}
