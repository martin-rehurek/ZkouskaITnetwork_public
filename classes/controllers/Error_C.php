<?php
namespace classes\controllers;

/**
 * Kontroler pro zpracování chybové stránky
 */
class Error_C extends Main_C {
    /**
     * Nastaveni HTML hlavičky a šablony na error stránku
     * @param array $params
     * @return void
     */
    public function process(array $params) : void {
		  
      header("HTTP/1.0 404 Not Found");
      $this->head['title'] = 'Chyba 404';
		  $this->view = 'error';
    }
}