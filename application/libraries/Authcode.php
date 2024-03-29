<?php
/**
 * CodeIgniter Authcode Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		wintion@gmail.com
 */
class Authcode {
	var $CI;
	var $fontPath;//字体路径
	var $image;
	var $charLen 		= 4; //生成几位验证码
	var $arrChr			= array();//验证码字符
	var $width 			= 75; //图片宽
	var $height 		= 27; //图片高

	var $bgcolor 		= "#ffffff"; //背景色
	var $showNoisePix 	= true; //生成杂点
	var $noiseNumPix 	= 20; //生成杂点数量
	var $showNoiseLine 	= true; //生成杂线
	var $noiseNumLine 	= 0; //生成杂线数量
	var $showBorder 	= true; //边框，当杂点、线一起作用的时候，边框容易受干扰
	var $borderColor 	= "#E5E5E5";

	function Authcode() {
		$this->CI = & get_instance();
		$this->fontPath = realpath(dirname(__FILE__) . '/fonts/');	//字体文件
		$this->arrChr 		= array_merge(range(1, 9) , range('A', 'H'), range('J', 'N'), range('P', 'Z'));//数字字母验证码
		//$this->arrChr 		= range('A', 'Z');//纯字母验证码
		//$this->arrChr = range(0, 9);//纯数字验证码
	}

	/**
	 * 显示验证码
	 *
	 */
	function show() {
		$codename='authcode';
		$this->image = imageCreate($this->width, $this->height);
		$this->back = $this->getColor($this->bgcolor);

		imageFilledRectangle($this->image, 0, 0, $this->width, $this->height, $this->back);

		$size = $this->width / $this->charLen - 4;
		if ($size > $this->height) {
			$size = $this->height;
		}
		$left = ($this->width - $this->charLen * ($size + $size / 10)) / $size + 5;
		$code='';
		for($i = 0; $i < $this->charLen; $i ++) {
			$randKey = rand(0, count($this->arrChr) - 1);
			$randText = $this->arrChr[$randKey];
			$code .= $randText;
			$textColor = imageColorAllocate($this->image, rand(0, 100), rand(0, 100), rand(0, 100));
			$font = $this->fontPath . '/' . rand(1, 5) . ".ttf";
			$randsize = rand($size - $size / 10, $size + $size / 10);
			$location = $left + ($i * $size + $size / 10);
			@imagettftext($this->image, $randsize, rand(- 18, 18), $location, rand($size - $size / 10, $size + $size / 10) + 5, $textColor, $font, $randText);
		}

		if ($this->showNoisePix == true) {
			$this->setNoisePix();
		}
		if ($this->showNoiseLine == true) {
			$this->setNoiseLine();
		}
		if ($this->showBorder == true) {
			$this->borderColor = $this->getColor($this->borderColor);
			imageRectangle($this->image, 0, 0, $this->width - 1, $this->height - 1, $this->borderColor);
		}
		$this->CI->session->set_userdata($codename, $code);
		header("Content-type: image/jpeg");
		imagejpeg($this->image);
		imagedestroy($this->image);
	}


	/**
	 * 显示验证码的JS调用
	 *
	 */
	function showScript() {
		//显示验证码
		echo "var img_src = '/imgauthcode/show/';";
        echo "document.writeln('<img id=\"img_authcode\" src=\"' + img_src + Math.random() + '\" style=\"cursor:hand;\" onclick=\"this.src=img_src + Math.random();\" alt=\"点击更换图片\">');";
	}

	function getColor($color) {
		$color = preg_replace("/^#/", "", $color);
		$r = $color[0] . $color[1];
		$r = hexdec($r);
		$b = $color[2] . $color[3];
		$b = hexdec($b);
		$g = $color[4] . $color[5];
		$g = hexdec($g);
		$color = imagecolorallocate($this->image, $r, $b, $g);
		return $color;
	}

	function setNoisePix() {
		for($i = 0; $i < $this->noiseNumPix; $i ++) {
			$randColor = imageColorAllocate($this->image, rand(0, 255), rand(0, 255), rand(0, 255));
			imageSetPixel($this->image, rand(0, $this->width), rand(0, $this->height), $randColor);
		}
	}

	function setNoiseLine() {
		for($i = 0; $i < $this->noiseNumLine; $i ++) {
			$randColor = imageColorAllocate($this->image, rand(0, 255), rand(0, 255), rand(0, 255));
			imageline($this->image, rand(1, $this->width), rand(1, $this->height), rand(1, $this->width), rand(1, $this->height), $randColor);
		}
	}
}
