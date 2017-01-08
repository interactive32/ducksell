<?php namespace App\Services;

use App\Events\AddNotifications;
use App\Models\Option;
use App\Models\Product;
use Event;

/**
 * Service
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Notifications {


	public static function getNotifications()
	{

		$notifications = [];

		if(!config('global.script-license')) {

			$notifications[] = [
				'name' => trans('app.enter_license'),
				'text' => trans('app.enter_license_text'),
				'link' => url('options?section=license'),
			];

		}

		if(!config('mail.driver')) {

			$notifications[] = [
				'name' => trans('app.mail_not_configured'),
				'text' => trans('app.mail_not_configured_text'),
				'link' => url('options?section=mail'),
			];

		}

		if(!config('global.tracking-started')) {

			$notifications[] = [
				'name' => trans('app.not_receiving_data'),
				'text' => trans('app.not_receiving_data_text'),
				'link' => url('sites'),
			];

		}

		if(Util::stripProtocol(url(), true) != Util::stripProtocol(config('global.base-url'), true)) {
			$notifications[] = [
				'name' => trans('app.url_changed'),
				'text' => trans('app.url_changed_text'),
				'link' => url('sites'),
			];

		}

		if(Product::all()->isEmpty()) {
			$notifications[] = [
				'name' => trans('app.no_products'),
				'text' => trans('app.no_products_text'),
				'link' => url('products'),
			];

		}

		$event_notifications = Event::fire(new AddNotifications());

		$notifications = array_merge($notifications, $event_notifications);

		return $notifications;
	}

	public static function checkNoTrackingHasBeenRecorded()
	{
		if(!config('global.tracking-started')) {
			Option::updateOrCreate(['key' => 'global.tracking-started'], ['value' => 1]);
		}
	}

	public static function checkUrlHasBeenChanged()
	{
		if(Util::stripProtocol(url(), true) != Util::stripProtocol(config('global.base-url'), true)) {
			Option::updateOrCreate(['key' => 'global.base-url'], ['value' => url()]);
		}
	}

}
