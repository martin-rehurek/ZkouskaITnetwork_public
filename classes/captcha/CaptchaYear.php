<?php
namespace classes\captcha;

class CaptchaYear implements Captcha {

    public function getHtmlCode() : string {
        return '
            <label for="InputCaptcha">Aktuální rok:</label>
            <input type="text" name="captcha" class="form-control" id="InputCaptcha" aria-describedby="captchaHelp" placeholder="aktuální rok">
            <small id="captchaHelp" class="form-text text-muted">Zadejte aktuální rok (captcha)</small>
            ';
    }

    public function verify(string $code) : bool {
        return ($code == date("Y"));
    }
}