<?php


return [

    'name' => 'bixie/mailchimp-subscribe',

    'label' => 'Mailchimp Subscribe',

	'events' => [

		'view.scripts' => function ($event, $scripts) use ($app) {
			$scripts->register('widget-admin-mailchimp-subscribe', 'bixie/mailchimp:app/bundle/widget-subscribe.js', ['~widgets', 'uikit-nestable']);

		}
		
	],

	'render' => function ($widget) use ($app) {

		$id = uniqid('mailchimp');

		$template = $widget->get('view', 'subscribe');
		$list_id = $widget->get('list_id', '');
		
		$text = $app['content']->applyPlugins($widget->get('text'), [
			'widget' => $widget, 'markdown' => $widget->get('markdown')
		]);

		$mergefields_string = json_encode($widget->get('merge_vars', []), JSON_NUMERIC_CHECK);

		return $app['view']('bixie/mailchimp/widgets/'.$template.'.php', compact('widget', 'id', 'list_id', 'text', 'mergefields_string'));

    }

];
