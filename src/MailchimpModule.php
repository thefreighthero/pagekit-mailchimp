<?php

namespace Bixie\Mailchimp;

use Pagekit\Application as App;
use Pagekit\Module\Module;

class MailchimpModule extends Module {

	/**
	 * {@inheritdoc}
	 */
	public function main (App $app) {
		$app['mailchimp_api'] = function ($app) {
			return new \Mailchimp($app->module('bixie/mailchimp')->config('api_key'), []);
		};
	}

}
