<?php
namespace VijayWilson\Multicaptcha\Middleware;

use Closure;
use Redirect;
use October\Rain\Exception\AjaxException;
use VijayWilson\Multicaptcha\Components\Captcha;
use VijayWilson\Multicaptcha\Models\MultiCaptcha as MultiCaptchaModel;
use Lang;
use Flash;

class captchaMiddleware
{
	public function handle($request, Closure $next)
	{
		if($request->exists('g-recaptcha-response'))
		{
			$multicaptcha = MultiCaptchaModel::find('captcha');

			if($request->input('g-recaptcha-response') == '')
			{
				$exception = [
								'input_request'	=> 'g-recaptcha-response',
								'message'		=> $multicaptcha->error_message_select_captcha
							 ];
				if($request->ajax())
				{
					throw new ajaxException(json_encode($exception));
				}
				Flash::error(json_encode($exception));
				return Redirect::back();
			}
			else
			{
				$exception = [
								'input_request'	=> 'g-recaptcha-response',
								'message'		=> $multicaptcha->error_message_invalid_captcha
							 ];
				$validation_result = Captcha::validateCaptcha($request->input('g-recaptcha-response'));
				if($validation_result == false)
				{
					if($request->ajax())
					{
						throw new ajaxException(json_encode($exception));
					}
					Flash::error(json_encode($exception));
					return Redirect::back();
				}
			}
		}
		return $next($request);
	}
}

?>