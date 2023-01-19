<?php
namespace classes\captcha;

interface Captcha {
    public function getHtmlCode() : string;
    public function verify(string $code) : bool;
}