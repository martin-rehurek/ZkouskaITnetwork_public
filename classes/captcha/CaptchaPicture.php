<?php
namespace classes\captcha;

class CaptchaPicture implements Captcha {

    public function getHtmlCode() : void
    {
        echo('Přepiš text z obrázku: ');
        echo('<img src="../classes/captcha/picture.php?' . time() . '">');
        echo('<input type="text" name="captcha" />');
    }

    public function verify() : bool {
        return (isset($_SESSION['captcha']) &&
            ($_POST['verify'] == $_SESSION['captcha']));
    }

	public function generatePicture() : void	{
		$widht = 100;
		$height = 20;
		$picture = imagecreate($width, $height);
		$white = imagecolorallocate($picture, 255, 255, 255);
		$blue = imagecolorallocate($picture, 0, 0, 255);
		imagefilledrectangle($picture, 0, 0, $width, $height, $white);

		$symbols = 'abcdefghijklmnopqrstuvwxyz';
		$lenght = mb_strlen($lenght);
		$text = '';

		for ($i = 0; $i < 4; $i++) {
			$symbol = $symbols[rand(0, $symbols - 1)];
			$text .= $symbol;
			imagestring($picture, 10, 10 + $i * 20, 4, $symbol, $blue);
		}

		$_SESSION['captcha'] = $text;
		imagejpeg($picture);
	}

}