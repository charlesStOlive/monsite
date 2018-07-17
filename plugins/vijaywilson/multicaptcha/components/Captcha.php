<?php
namespace VijayWilson\Multicaptcha\Components;

use Cms\Classes\ComponentBase;
use VijayWilson\Multicaptcha\Models\MultiCaptcha;
use Lang;

class Captcha extends ComponentBase
{
	public function componentDetails()
	{
		return[
				'name' 			=> 'Multi-Captcha',
				'description'	=> 'Implements more than one google recaptcha widget on a page'
			  ];
	}
	
	public function onRun()
	{
		$multicaptcha = MultiCaptcha::getCaptchaDetails();
		$this->page['g_recaptcha_error_style'] 	= $multicaptcha['css_classes'];
		$params 								= ['site-key'=>(isset($multicaptcha['site_key']))?$multicaptcha['site_key']:''];
		$this->addJs('assets/js/multi_captcha.js',http_build_query($params));
		$this->addJs('assets/js/flash.js');
		$this->addCss('assets/css/flash.css');
	}
	/*
	 * Validate g-recaptcha-response  
	 */
	public static function validateCaptcha($captcha_reponse)
	{
		$secret_key = MultiCaptcha::getCaptchaDetails()['secret_key'];
		$result = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$captcha_reponse);
		$result = json_decode($result,true);
		if($result['success'] == false)
		{
			return false;
		}
		return true;
	}
}

?>