<?php namespace App\Services;

use App\Events\EmailPrepare;
use App\Events\EmailSent;
use App\Models\Log;
use Auth;
use Event;
use Input;
use Mail;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Service
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Util
{
	
	public static function sendMail($to, $subject, $body, $cc = false, $bcc = false)
	{
		Event::fire(new EmailPrepare($to, $subject, $body, $cc, $bcc));

		try {
			// don't use laravel mail and blade templates, use swift mailer directly
			$message = new Swift_Message;
			$message->setTo($to);
			$message->setFrom(config('mail.from.address'), config('mail.from.name'));
			$message->setSubject($subject);
			$message->setBody($body, 'text/html');

			if($cc) {
				$message->addCc($cc);
			}

			if($bcc) {
				$message->addBcc($bcc);
			}

			$mailer = new Swift_Mailer(Mail::getSwiftMailer()->getTransport());
			$mailer->send($message);
		} catch (\Exception $e) {
			Log::writeException($e);
			return false;
		}

		Event::fire(new EmailSent($message));

		return true;

	}

	public static function stripProtocol($url, $strip_www = false)
	{
		$url = preg_replace("#^[^:/.]*[:/]+#i", "", $url);

		if ($strip_www && substr($url, 0, 4) == 'www.') {
			$url = substr($url, 4);
		}

		return $url;
	}

	public static function getRouteIdentifier()
	{
		$action = $controller = null;

		if (app('request')->route()) {

			$action = app('request')->route()->getAction();
			$controller = str_replace("Controller", "", class_basename($action['controller']));

			list($controller, $action) = explode('@', $controller);

			$controller = strtolower($controller);
			$action = strtolower($action);
		}

		return $controller . '@' . $action;
	}

	public static function generateLicenseNumber()
	{
		return uniqid();
	}

	public static function generateTransactionHash($hash = null)
	{
		if(!$hash) {
			$hash = mt_rand(10000, 99999);
		}

		return uniqid().md5($hash);
	}

	public static function generatePassword($length = 8)
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

		$pass = [];

		$alphaLength = strlen($alphabet) - 1;

		for ($i = 0; $i < $length; $i++) {
			$n = mt_rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}

		return implode($pass);
	}

	public static function getCurrentUserInfo()
	{
		return (string) Auth::check() ? Auth::user()->email : false;
	}

	public static function deleteOldTmpFiles($seconds_old = 3600)
	{
		foreach (glob(config('global.tmp') . '*') as $file) {
			if (is_file($file) && filemtime($file) < time() - $seconds_old) {
				@unlink($file);
			}
		}
	}

	public static function getAvailableLanguages()
	{
		$languages = [];

		foreach (glob(base_path().'/resources/lang/*') as $val){
			$languages[basename($val)] = basename($val);
		}

		return $languages;
	}

	public static function generateFileName($file_name)
	{
		if(config('global.preserve-filenames')) {
			$file_name_disk = $file_name;

			// up-count on existing file
			$i = 1;
			while (file_exists(config('global.file_path').$file_name_disk)) {
				$file_name_disk = pathinfo($file_name, PATHINFO_FILENAME).'('.$i.')'.(pathinfo($file_name_disk, PATHINFO_EXTENSION) ? '.'.pathinfo($file_name_disk, PATHINFO_EXTENSION) : '' );
				$i++;
			}

			return $file_name_disk;
		}
		
		return uniqid();
	}

	public static function uploadFileFromInput(UploadedFile $uploaded_file)
	{
		$file_name = $uploaded_file->getClientOriginalName();
		$file_name_internal = Util::generateFileName($file_name);
		$uploaded_file->move(config('global.file_path'), $file_name_internal);

		return $file_name_internal;
	}

	public static function getFileFromServer($file_name)
	{
		if(!file_exists(config('global.file_tmp') . $file_name)) {
			return false;
		}

		// existing file on the server
		$file_name_internal = Util::generateFileName($file_name);
		rename(config('global.file_tmp') . $file_name, config('global.file_path') . $file_name_internal);

		return $file_name_internal;
	}

}
