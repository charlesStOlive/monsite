<?php namespace VijayWilson\Multicaptcha;

use System\Classes\PluginBase;
use Backend;
use Cms\Classes\CmsController;

class Plugin extends PluginBase
{
	public function pluginDetails()
	{		
		return [
				'name'=>'MultiCaptcha',
				'description'=>'Provides google reCaptcha v2.0 for authentication',
				'author'=>'Vijay Wilson'
			   ];
	}
	
	public function registerNavigation()
	{
		return[
				'multicaptcha'=>[
								'label'=>'MultiCaptcha',
								'url'=>Backend::url('vijaywilson/multicaptcha/multicaptcha/update/captcha'),
								'icon'=>'icon-shield',
								'iconSvg'=>'/plugins/vijaywilson/multicaptcha/assets/images/multi-captcha.svg'
								]
			  ];
	}
	
	public function registerComponents()
	{
		return[
					'\VijayWilson\Multicaptcha\Components\Captcha'=>'reCaptcha'
			  ];
	}
	/*
	 * Register the captchaMiddleware to handle the g-recaptcha-response before a request is routed
	 */
	public function boot()
	{
		CmsController::extend(function($controller)
		{
			$controller->middleware('VijayWilson\Multicaptcha\Middleware\captchaMiddleware');
		});
	}
}


?>