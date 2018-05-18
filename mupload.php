<?php 
/*
Mustafa Yılmaz
*/
class MUpload
{
	// $_FILES["name"]
	private $file; 
	// yüklenen dosya adı
	public $yuklenen;
	// yükleme esnasında yakalanacak hatalar
	public $hata;
	// dosya yüklemesinde yeni ada eklenecek olan ön ek
	public $dosya_on_ek="";

	public function __construct($param)
	{
		$this->file=$param;
	}
	// sadece dosya adını alan metod
	// resim.png ise
	// return resim
	private function ad(){
		$ad_uzanti = explode(".",$this->file["name"]);
		$sadece_ad = "";
		for ($i=0; $i<count($ad_uzanti)-1; $i++) { 
			$sadece_ad = $sadece_ad ."". $ad_uzanti[$i] ."_";
		}
		return $sadece_ad;
	}
	// sadece dosya uzantısını alan metod
	// resim.png ise
	// return png	
	private function uzanti()
	{
		$ad_uzanti = explode(".",$this->file["name"]);
		$sadece_uzanti = $ad_uzanti[count($ad_uzanti)-1];
		return $sadece_uzanti;
	}
	// rastgele benzersiz kimlik üreten metod
	private function rastgeleDeger(){
		$zaman = date("Ymd");
		$kimlik = uniqid();
		$sonuc = $kimlik."".$zaman;
		return $sonuc;
	}
	
	// $param da bulunan türkçe karakterleri ve A-Z,a-z,0-9,-ve _ dışındaki karakterleri temizleyip küçük harflere döndüren metod
	private function temizle($param)
	{
		$eski = ["ç","Ç","ı","İ","ğ","Ğ","ş","Ş","ü","Ü","ö","Ö"];
		$yeni = ["c","C","i","I","g","G","s","S","u","U","o","O"];
		$param = str_replace($eski, $yeni, $param);
		$param = preg_replace("@[^A-Za-z0-9\-_]+@i","",$param);
		$param = strtolower($param);
		return $param;
	}
	// dosya yüklemesini yapan metod
	// eğer yol yok ise oluşturur.
	public function yukle($yol="")
	{
		$ad = $this->ad();
		$uzanti = $this->uzanti();
		$yeni_ad = $this->temizle($ad);
		if ($this->dosya_on_ek=="") {
			$son_ad= $yeni_ad ."". $this->rastgeleDeger() .".". $uzanti;
		}else{
			$son_ad= $this->dosya_on_ek."_".$yeni_ad ."". $this->rastgeleDeger() .".". $uzanti;
		}
		if ( $yol!="") {

			function mkdir_r($dirName, $izin=0644,$boo="false"){
				$dirs = explode('/', $dirName);
				$dir='';
				$hata =true;
				foreach ($dirs as $part) {
					$dir.=$part.'/';
					if (!is_dir($dir) && strlen($dir)>0)
						if(!mkdir($dir, $izin,$boo)){
							$hata = false;
						}
				}
				return $hata;
			}

			if(!mkdir_r($yol,0644,true)){
				$this->hata="Dizin Oluşturulamadı.";
				return false;
				exit();
			}else{
				$son = $yol ."/". $son_ad;
			}
		}else{
			$son = $son_ad;
		}

		$yukle = move_uploaded_file($this->file["tmp_name"], $son);
		if ( $yukle ) {
			$this->yuklenen = $son_ad;
			return true;
		}else{
			return false;
		}

	}
}