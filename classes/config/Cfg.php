<?php
namespace classes\config;

/**
 * Vyctovy typ pro ulozeni parametru pripojeni
 * Diky vyctovemu typu se mi tendo soubor sam nacte autoloaderem
 */
	enum Cfg : string{
		case H = "proj";
		case U = "root";
		case P = "";
        case D = "db_of_clients";
	}
