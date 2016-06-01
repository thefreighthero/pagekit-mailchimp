<?php

namespace Bixie\Mailchimp\Controller;

use Pagekit\Application as App;

class MailchimpApiController {

	/**
	 * @Access("system: access settings")
	 * @Route("/list", methods="GET")
	 * @Request({"filter": "array"}, csrf=true)
	 */
	public function listAction ($filter = []) {

		$res = [];

		try {
			$api = App::get('mailchimp_api');
			$res = $api->lists->getList();

		} catch (\Exception $e) {
			App::abort(400, $e->getMessage());
		}

		return $res;
	}

	/**
	 * @Access("system: access settings")
	 * @Route("/merge_vars", methods="GET")
	 * @Request({"list_id": "string"}, csrf=true)
	 */
	public function mergeVarsAction ($list_id) {

		$res = [];

		try {
			$api = App::get('mailchimp_api');
			$res = $api->lists->mergeVars([$list_id]);

		} catch (\Exception $e) {
			App::abort(400, $e->getMessage());
		}

		return $res;
	}

	/**
	 * @Route("/subscribe", methods="POST")
	 * @Request({"list_id": "string", "email": "string", "merge_vars": "array"}, csrf=true)
	 * @param $list_id
	 * @param $email
	 * @param $merge_vars
	 * @return array
	 */
	public function subscribeAction ($list_id, $email, $merge_vars) {

		try {
			$api = App::get('mailchimp_api');
			$res = $api->lists->subscribe($list_id, ['email' => $email], $merge_vars);

			if (!empty($res['euid'])) {
				$result = 'success';
				$message = __('Email address added. A confirmation mail will be sent to to this address.');
			}


		} catch (\Mailchimp_Error $e) {

			$result = 'warning';
			$message = __($e->getMessage());
			
		} catch (\Exception $e) {

			App::abort(400, $e->getMessage());

		}

		return compact('result', 'message');

	}

}
