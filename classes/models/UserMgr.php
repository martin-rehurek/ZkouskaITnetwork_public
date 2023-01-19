<?php
namespace classes\models;
use classes\exceptions\AuthenticationError;
use classes\exceptions\InternallError;
use PDOException;

/**
 * Modul pro správu uživatelů
 */
class UserMgr {

    /**
     * Hashování hesla
     * @param string $password
     * @return string
     */
    public function getHash(string $password) : string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Založí do systému nového uživatele
     * @param string $email
     * @param string $password
     * @param string $passwordAgain
     * @return void
     * @throws AuthenticationError
     */
    public function register(string $email, string $password, string $passwordAgain) : void {
        if ($password != $passwordAgain)
            throw new AuthenticationError('Hesla nesouhlasí.');
        $user = array(
            'email' => $email,
            'password' => $this->getHash($password),
        );
        try { //print_r($user); exit();//debug
            Db::insert('dbc_users', $user);
        }
        catch (PDOException $e) {
            throw new AuthenticationError('Uživatel s tímto jménem je již zaregistrovaný.');//<br />'.$e->getMessage().'<br />');
        }
    }

    /**
     * Získání základních údajů o uživateli (id, email, role_id, password, status)
     * Použito hlavně pro výpis oslovení a zjištění role přihlášeného uživatele hned po přihlášení (na základě e-mailu)
     * @param string $email
     * @return array
     * @throws InternallError
     */
    public function getUsersInfoLogin(string $email) : array {
		//print_r($email);exit();//debug
        $user = Db::queryOne("
		SELECT id, email, role_id, password, status
        FROM dbc_users
		WHERE email = ?
        ", array($email));
		if(!$user)
            return array();
        return $user;
    }

    /**
     * Získání údajů o uživateli (id, fname, lname, email, phone, status, role_id, role, password)
     * Použito hlavně pro účely úpravy profilu
     * @param string $id
     * @return array
     * @throws InternallError
     */
    public function getUsersInfo(string $id) : array {
		$user = Db::queryOne("
		SELECT dbc_users.id as id, fname, lname, email, phone, status, role_id, dbc_roles.name as role, password
		FROM dbc_users 
        JOIN dbc_roles 
        ON dbc_users.role_id = dbc_roles.id
		WHERE dbc_users.id = ?
        ", array($id));
        if (!$user)
            throw new InternallError('Vnitřní chyba při získání odobních údajů.');
		return $user;
    }

    /**
     * Získání adresy uživatele (street, city, zip)
     * Použito hlavně pro účely úpravy profilu
     * @param int $id
     * @return array
     * @throws InternallError
     */
    public function getUsersAddress(int $id) : array {
		$address = Db::queryOne("
		SELECT street, city, zip 
		FROM dbc_addresses 
		WHERE user_id = ? 
		ORDER BY dbc_addresses.id DESC LIMIT 1;
        ", array($id));
        if (!$address)
            return array();
		return $address;
    }

    /**
     * Získání seznamu rolí (id, name)
     * Slouží pro správu uživatelů pro naplnění rozbalovacího seznamu
     * @return array
     * @throws InternallError
     */
    public function getRoles() : array {
        $roles = Db::queryAll("
		SELECT id, name 
		FROM dbc_roles 
        ", array());
        if (!$roles)
            throw new InternallError('Vnitřní chyba při získání seznamu rolí.');
        return $roles;
    }

    /**
     * Vrátí seznam povolených url z menu aktuálně přihlášeného uživatele
     * @return array
     * @throws InternallError
     */
    public function getAllowedUrls() : array {
        if (isset($_SESSION['current_user'])){
            foreach($_SESSION['current_user']['menu'] as $item){
                $allowedUrls[] = strtolower($item['url']);
            }
            return $allowedUrls;
        }
        return array();
    }


    /**
     * Přihlášení - ověření a nastavení session
     * @param string $email
     * @param string $password
     * @return void
     * @throws AuthenticationError
     * @throws InternallError
     */
    public function login(string $email, string $password) : void {
        $user = $this->getUsersInfoLogin($email); //print_r($user);exit();//debug
        if (!$user || !password_verify($password, $user['password']))
            throw new AuthenticationError('Neplatné jméno nebo heslo.');
        if ($user['status'] != 'active')
            throw new AuthenticationError('Účet není aktivní. Obraťte se na svého obchodního zástupce.');
        $_SESSION['current_user'] = $user;
        $_SESSION['current_user']['menu'] = $this->GetUsersMenu($_SESSION['current_user']['id']);
    }

	/**
     * Odhlásí uživatele a vyčistí session
     */
    public function logout() : void {
        unset($_SESSION['current_user']);
        unset($_SESSION['messages']);
    }

    /**
     * Vrátí aktuálně přihlášeného uživatele (tím pádem i test, zda je přihlášen)
     */
    public function getUser() : ?array {
        if (isset($_SESSION['current_user']))
            return $_SESSION['current_user'];
        return null;
    }

    /**
     * Zápis uživatelských informací do session
     * Hlavně pro účely editace profilu
     * @param int $id
     * @return void
     * @throws InternallError
     */
    public function SetProfileToSession(int $id) : void {
        unset($_SESSION['profile']);
        $_SESSION['profile']['info'] = $this->GetUsersInfo($id);
        $_SESSION['profile']['address'] = $this->GetUsersAddress($id);
    }

    /**
     * Získání menu daného uživatele podle jeho role
     * @param int $id
     * @return array
     * @throws InternallError
     */
    public function GetUsersMenu(int $id) : array {
        $menu = Db::queryAll('
            SELECT `label`, `url`
            FROM `dbc_menu`
            WHERE `role_id` >= (
                SELECT `role_id`
                FROM `dbc_users`
                WHERE `id` = ?
                LIMIT 1
            ) ORDER BY `id`', array($id));
        if (!$menu)
            throw new InternallError('Vnitřní chyba při získání menu.');
            return $menu;
    }

    /**
     * Získání seznamu klientů
     * @return array
     * @throws InternallError
     */
    public function getClients() : array {
		$clients = Db::queryAll('
		SELECT dbc_users.id as id, fname, lname, email, phone, status, role_id, dbc_roles.name as role
		FROM dbc_users
		    LEFT JOIN dbc_roles ON dbc_roles.id = role_id
        ', array());
        if (!$clients)
            throw new InternallError('Vnitřní chyba při získávání seznamu uživatelů.');
		return $clients;
    }

    /**
     * Úprava uživatelského profilu
     * @param int $id
     * @param array $params
     * @return void
     * @throws InternallError
     */
	public function updateProfile(int $id, array $params) : void {
        if(!empty(array_diff_assoc($params, $_SESSION['profile']['info']))) //provedu, jen pokud se data změnila
            if (!Db::update('dbc_users', 'id', $id, $params))
                throw new InternallError('Vnitřní chyba při úpravě dat. id: '.$id.' '.print_r($params));
	}

    /**
     * Založení nového uživatele
     * @param array $params
     * @return int
     * @throws InternallError
     */
	public function addClient(array $params) : int {
        if (!Db::insert('dbc_users', $params))
            throw new InternallError('Vnitřní chyba při vkládání nového klienta - možná už existuje?');
        $user = $this->getUsersInfoLogin($params['email']);
        return $user['id'];
	}

    /**
     * Úprava adresy uživatele
     * @param int $id
     * @param array $params
     * @return void
     * @throws InternallError
     */
	public function updateAddress(int $id, array $params) : void {
    	$params += ['user_id' => $id];
        if(!empty(array_diff_assoc($params, $_SESSION['profile']['address']))) //provedu, jen pokud se data změnila
            if (!Db::insert('dbc_addresses', $params))
                throw new InternallError('Vnitřní chyba při úpravě dat.');
	}

    /**
     * Změna hesla uživatele
     * @param int $id
     * @param array $params
     * @return void
     * @throws InternallError
     */
	public function updatePassword(int $id, array $params) : void {
        if (isset($params['password']) && isset($params['passwordAgain']) && $params['password'] === $params['passwordAgain'] && mb_strlen($params['password']) > 6 && $_SESSION['profile']['info']['password'] !== $this->getHash($params['password'])){
            if(!Db::update('dbc_users', 'id', $id, array('password' => $this->getHash($params['password']))))
                throw new InternallError('Vnitřní chyba při změně hesla.');
        }
    }

    /**
     * Uživatelský report
     * @param int $id
     * @return array
     * @throws InternallError
     */
    public function getUsersStats(int $id) : array {
		$usersStats = Db::queryOne("
		SELECT count(*) AS contracts, 
            (
                SELECT count(*) FROM dbc_user_event WHERE (user_id = ?)
            ) AS events 
        FROM dbc_user_product WHERE user_id = ?
        ", array($id, $id));
        if (!$usersStats)
            throw new InternallError('Vnitřní chyba při získání statistických údajů.');
		return $usersStats;
    }
}