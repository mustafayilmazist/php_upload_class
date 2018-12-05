<?php 
//  Array
// (
// [resim] => Array
// (
// [name] => site taslak.fonksiyon.png
// [type] => image/png
// [tmp_name] => Z:\xampp\tmp\phpAAC1.tmp
// [error] => 0
// [size] => 8564
// )
// )

class Upload
{
	/**
	 * [$dosya -> $_FILES["dosya"]]
	 */
	private $dosya;
	/**
	 * [$yol -> dosyanın yüklenecek olduğu klasör ismi]
	 */
	private $yol;
	/**
	 * [$dosyaAdi yüklenen dosyanın adı]
	 */
	private $dosyaAdi;

	/**
	 * [__construct -> $_FILES["dosya"]] adını $dosya özelliğine atar.
	 */
	function __construct($file){
		$this->dosya=$file;
	}
	/**
	 * [sadeceAd -> dosya ad ve uzantısından uzantı harici bütün isimleri alır.]
	 * @return [type] [sadece ad]
	 */
	public function sadeceAd(){
		$adUzanti = explode(".", $this->dosya["name"]);
		/**
		 * site taslak.fonksiyon.png
		 * [0]site taslak
		 * [1]fonksiyon
		 * [2]png
		 */
		$sadeceAd="";
		for ($i=0; $i<count($adUzanti)-1; $i++) { 
			$sadeceAd .= $adUzanti[$i];
		}
		return $sadeceAd;
	}
	/**
	 * [sadeceUzanti -> dosya ad ve uzantısından sadece uzanti değerini alır.]
	 * @return [type] [description]
	 */
	public function sadeceUzanti(){
		$adUzanti = explode(".", $this->dosya["name"]);
		/**
		 * site taslak.fonksiyon.png
		 * [0]site taslak
		 * [1]fonksiyon
		 * [2]png
		 */
		$sadeceUzanti= $adUzanti[count($adUzanti)-1];
		return $sadeceUzanti;
	}
	/**
	 * [yeniAdOlustur -> dosya adında büyük A-Z küçük a-z ve rakam dışında bulunan tüm karakterleri temizleyip boşlukları - yapar. ve dosyanın adına benzersiz bir ıd değeri atar.]
	 * @param  [type] $text [metodun aldığı parametre]
	 * @return [type]       [$yeni temizlenmiş olan değer]
	 */
	function yeniAdOlustur($text)
	{
		$tr = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
		$ing = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', '', '');
		$text = strtolower(str_replace($tr, $ing, $text));
		$text = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $text);
		$text = trim(preg_replace('/\s+/', ' ', $text));
		$text = str_replace(' ', '-', $text);
		$text .= "_". $this->uniqIdOlustur();
		return $text;
	}
	/**
	 * [uniqIdOlustur -> 13 karakterli benzersiz bir id değeri oluşturur.]
	 * @return [type] [uniqid]
	 */
	function uniqIdOlustur(){
		return uniqid();
	}
	/**
	 * [dosyaAdi yüklenen dosyanın adını verir.]
	 * @return [type] [yeni yüklenen dosyanın en son adı]
	 */
	public function dosyaAdi()
	{
		return $this->dosyaAdi;
	}
	/**
	 * [yukle ->eğer yol boş ise bulunduğu klasöre eğer yol boş değilse belirtilen klasöre yeni ad verilen dosyayı yükler ve sonucunu true yada false ile döndürür.]
	 * @param  string $yol [dosyanın yükleneceği klasör]
	 * @return [type]      [true/false]
	 */
	public function yukle($yol=""){
		$ad = $this->sadeceAd();
		$uzanti = $this->sadeceUzanti();
		$yAd =  $this->yeniAdOlustur($ad);
		$dosyaAdi = $yAd.".".$uzanti;
		
		if ($yol=="") {
			$yuklenecekDosya = $dosyaAdi;
		}else{
			$yuklenecekDosya = $yol ."/". $dosyaAdi;
		}

		$yuklenenDosya = move_uploaded_file($this->dosya["tmp_name"], $yuklenecekDosya);
		if ($yuklenenDosya) {
			$this->dosyaAdi=$dosyaAdi;
			return true;
		}else{
			return false;
		}
	}
}
