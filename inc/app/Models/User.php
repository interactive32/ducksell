<?php namespace App\Models;

use App\Events\PostCreateCustomer;
use App\Events\PreCreateCustomer;
use App\Events\ReferralUpdated;
use Auth;
use Event;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
	use SoftDeletes;
	use Authenticatable, CanResetPassword;

	const CUSTOMER_ROLE = 0;
	const ADMIN_ROLE = 1;
	const MANAGER_ROLE = 2;

	protected $dates = ['deleted_at'];
	protected $table = 'users';
	protected $fillable = ['name', 'email', 'password', 'details'];
	protected $hidden = ['password', 'remember_token'];

	public function metadata()
	{
		return $this
			->hasMany('App\Models\UserMetadata');
	}

	public function transactions()
	{
		return $this
			->hasMany('App\Models\Transaction')
			->with('products');
	}

	public function scopeCustomers($query)
	{
		return $query
			->where('role', self::CUSTOMER_ROLE);
	}

	public function scopeProfiles($query)
	{
		return $query
			->where('role', '<>', self::CUSTOMER_ROLE);
	}

	public function createCustomer($name, $email, $details, array $metadata = [])
	{
		Event::fire(new PreCreateCustomer($name, $email, $details, $metadata));

		$profile = User::withTrashed()->where('email', $email)->first();

		if($profile) {
			throw new \Exception('log_cannot_create_customer_profile_exists');
		}

		try {
			$user = $this->create([
				'name' => $name,
				'email' => $email,
				'role' => self::CUSTOMER_ROLE,
				'details' => $details,
				]);
			
			if(!empty($metadata)) {
				$UserMetadata = new UserMetadata();
				$UserMetadata->addMetadata($user->id, $metadata);
			}

			Event::fire(new PostCreateCustomer($user));

			Log::write('log_new_customer_created', $user->toJson());

		} catch (\Exception $e) {
			Log::writeException($e, false);
			return false;
		}

		return $user;
	}

	public function getOrCreateCustomer($email, $name = '', $details, array $metadata = [], $restore_trashed = true)
	{
		$customer = User::withTrashed()->customers()->where('email', $email)->first();

		if($customer && $customer->trashed()) {
			if($restore_trashed) {
				$customer->restore();
				Log::write('log_customer_restored', $customer, true);
				return $customer;
			} else {
				return false;
			}
		}

		if (!$customer) {
			try {
				$customer = $this->createCustomer($name, $email, $details, $metadata);
			} catch (\Exception $e) {
				Log::write($e->getMessage(), $email);
				return false;
			}
		}

		return $customer;
	}

	public static function updateReferral($user = null)
	{
		if(!$user) {
			$user = Auth::check() ? Auth::user() : null;
		}

		if (isset(session('first_visit')->referral) && session('first_visit')->referral) {
			$referral = session('first_visit')->referral;
		} else {
			$referral = '';
		}

		if (isset(session('first_visit')->landing_page) && session('first_visit')->landing_page) {
			$landing_page = session('first_visit')->landing_page;
		} else {
			$landing_page = '';
		}

		// first time only
		if(!$user->metadata->where('key', 'referral')->first()) {
			$UserMetadata = new UserMetadata();
			$UserMetadata->updateMetaValue($user->id, 'referral', $referral);
			$UserMetadata->updateMetaValue($user->id, 'landing_page', $landing_page);
		}

		Event::fire(new ReferralUpdated());
	}

}
