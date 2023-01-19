<?php
namespace classes\models;
use PDO;
//use PDOStatement;
use classes\config\Cfg;

/**
 * Hlavni třída (modul) pro obsluhu DB
 */
class Db{
    //stav připojení
	private static $conn;

    //nastavení PDO
	private static $setting = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
	);

    /**
     * Metoda pro připojení na testovou db
     * @param Cfg $host
     * @param Cfg $user
     * @param Cfg $password
     * @param Cfg $database
     * @return void
     */
	public static function connectMySQL(Cfg $host, Cfg $user, Cfg $password, Cfg $database) : void	{
		if (!isset(self::$conn))	{
			self::$conn = @new PDO( //nezapomentout, že zavináč skrývá errory
				"mysql:host=$host->value;dbname=$database->value",
				$user->value,
				$password->value,
				self::$setting
			);
		}
	}

    /**
     * Spustí dotaz a vrátí z něj první řádek
     * @param string $query SQL dotaz s parametry nahrazenými otazníky
     * @param array $params Parametry pro doplnění do připraveného SQL dotazu
     * @return array|bool Asociativní pole s informacemi z prvního řádku výsledku nebo FALSE v případě prázdného výsledku
     */
    public static function queryOne(string $query, array $params = array()) : array|bool {
        $result = self::$conn->prepare($query);
        $result->execute($params);
        return $result->fetch();
    }

    /**
     * Spustí dotaz a vrátí všechny jeho řádky
     * @param string $query SQL dotaz s parametry nahrazenými otazníky
     * @param array $params Parametry pro doplnění do připraveného SQL dotazu
     * @return array|bool Pole asociativních pole s informacemi o všech řádcích výsledku nebo FALSE v případě prázdného výsledku
     */
    public static function queryAll(string $query, array $params = array()) : array|bool {
        $result = self::$conn->prepare($query);
        $result->execute($params);
        return $result->fetchAll();
    }

    /**
     * Spustí dotaz a vrátí z něj první sloupec prvního řádku
     * @param string $query SQL dotaz s parametry nahrazenými otazníky
     * @param array $params Parametry pro doplnění do připraveného SQL dotazu
     * @return string|null Hodnota v prvním sloupci prvního řádku výsledku
     */
    public static function queryItem(string $query, array $params = array()) : string {
        $result = self::queryOne($query, $params);
        return $result[0];
    }

    /**
     * Spustí dotaz a vrátí počet ovlivněných řádků
     * @param string $query SQL dotaz s parametry nahrazenými otazníky
     * @param array $params Parametry pro doplnění do připraveného SQL dotazu
     * @return int Počet ovlivněných řádků
     */
    public static function query(string $query, array $params = array()) : int {
		$result = self::$conn->prepare($query);
        $result->execute($params);
        return $result->rowCount();
    }

    /**
     * Vloží do tabulky nový řádek jako data z asociativního pole
     * @param string $table Název databázové tabulky
     * @param array $params Asociativní pole parametrů pro vložení
     * @return bool TRUE v případě úspěšného provedení dotazu
     */
    public static function insert(string $table, array $params = array()) : bool {
        //echo "INSERT INTO `$table` (`".implode('`, `', array_keys($params))."`) VALUES (".str_repeat('?,', sizeOf($params)-1)."?)<br />"; print_r(array_values($params)); exit(); //debug
        return self::query("INSERT INTO `$table` (`".
            implode('`, `', array_keys($params)).
            "`) VALUES (".str_repeat('?,', sizeOf($params)-1)."?)",
            array_values($params));
    }

    public static function update(string $table, string $col, int $key, array $params = array()) : bool {
        $params_flipped = array_flip($params);
		$query = "UPDATE `$table` SET";
		foreach($params_flipped as $par){
			$query .= " `$par` = ?,";
		}
		$query = mb_substr($query, 0, -1); //zruším poslední čárku
		$query .= " WHERE `$table`.`$col` = ?";
		$params += ['key' => $key];
		//echo $query; print_r(array_values($params)); exit(); //debug
		return self::query($query,array_values($params));
    }
}