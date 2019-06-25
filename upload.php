<?php

class Upload
{
	private $dosya;
	private $yuklenen_dosya_adi;
	public function __construct($file)
	{
		$this->dosya=$file;
	}
	function getDosya(){
		echo "<pre>";
		print_r($this->dosya);
		echo "</pre>";
	}

	public function sadeceAd()
	{
		$adveUzanti = explode(".", $this->dosya["name"]);
		$sadeceAd = "";
		for ($i=0; $i <count($adveUzanti)-1; $i++) { 
			$sadeceAd .= "-". $adveUzanti[$i];
		}
		$sadeceAd = ltrim($sadeceAd,"-");
		return $sadeceAd;
	}

	public function sadeceUzanti()
	{
		$adveUzanti = explode(".", $this->dosya["name"]);
		$sadeceUzanti = $adveUzanti[count($adveUzanti)-1];
		return $sadeceUzanti;
	}

	public function yeniAdOlustur($text)
	{
		$tr=["Ç","Ş","Ğ","Ü","İ","Ö","ç","ş","ğ","ü","ı","ö","+","#"];
		$ing = ["C","S","G","U","I","O","c","s","g","u","i","o","",""];
		$text = strtolower(str_replace($tr, $ing, $text));
		$text = preg_replace("@[\.+]@", "", $text);
		$text = preg_replace("@[^A-Za-z0-9\-_\+]@", " ", $text);
		$text = trim(preg_replace('/\s+/', " ", $text));
		$text = str_replace(" ", "-", $text);
		$text .= "_".uniqid();
		return $text;
	}
	public function yukle($yol="")
	{
		$ad = $this->sadeceAd();
		$uzanti = $this->sadeceUzanti();
		$yeniAdOlustur = $this->yeniAdOlustur($ad);
		$yeniDosyaAdi = $yeniAdOlustur.".".$uzanti;
		if ($yol=="") {
			$yuklenecekDosya = $yeniDosyaAdi;
		}else{

			if (file_exists($yol)) {
				$yuklenecekDosya = $yol."/".$yeniDosyaAdi;
			}else{
				mkdir($yol,"0777");
				$yuklenecekDosya = $yol."/".$yeniDosyaAdi;
			}
		}
		$yuklenenDosya = move_uploaded_file($this->dosya["tmp_name"], $yuklenecekDosya);
		if ( $yuklenenDosya ) {
			$this->yuklenen_dosya_adi = $yeniDosyaAdi;
			return True;
		}else{
			return False;
		}
	}
	public function dosyaAdi()
	{
		return $this->yuklenen_dosya_adi;
	}

}
?>
