<?php

return [

	'name' => 'bixie/mailchimp',

	'type' => 'extension',

	'main' => 'Bixie\\Mailchimp\\MailchimpModule',

	'autoload' => [

		'Bixie\\Mailchimp\\' => 'src'

	],

	'routes' => [

		'/api/mailchimp' => [
			'name' => '@mailchimp/api',
			'controller' => [
				'Bixie\\Mailchimp\\Controller\\MailchimpApiController'
			]
		]

	],

	'widgets' => [

		'widgets/mailchimp-subscribe.php'

	],

	'resources' => [

		'bixie/mailchimp:' => ''

	],

	'config' => [
		'api_key' => ''
	],

	'settings' => 'settings-mailchimp',

	'events' => [
		'view.scripts' => function ($event, $scripts) use ($app) {
			$scripts->register('mailchimp-settings', 'bixie/mailchimp:app/bundle/settings.js', '~extensions');
		}
	]

];
