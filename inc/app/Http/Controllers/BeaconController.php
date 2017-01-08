<?php namespace App\Http\Controllers;

use App\Events\Beacon;
use App\Models\Analytic;
use App\Models\Log;
use App\Models\Site;
use App\Services\Notifications;
use App\Services\Util;
use Cache;
use Carbon\Carbon;
use Event;
use Request;
use Response;
use Session;
use View;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class BeaconController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function javascript()
    {
        $url = url('beacon');

        if(config('global.ssl-tracking')) {
            $url = '//'.Util::stripProtocol($url);
        }

        $contents = View::make('beacon.javascript')->with('url', $url);
        $response = Response::make($contents);
        $response->header('Content-Type', 'application/javascript');

        return $response;
    }

    public function beacon()
    {
        Event::fire(new Beacon());

        if ($this->isHuman()) {

            $geo_data = $this->getGeoLocationData();

            $site_id = Site::find(Request::input('site_id')) ? Request::input('site_id') : 0; // see beacon.js javascript
            $referral = urldecode(Request::input('r')); // see beacon.js javascript
            $created_at = Carbon::now();
            $created_at_timestamp = time();
            $ip_address = Request::getClientIp();
            $landing_page = Request::server('HTTP_REFERER', '');
            $custom_referral = strtolower(preg_replace('/[^a-zA-Z0-9]/s', '', Request::input('cr'))); // not used
            $browser_type = $this->getBrowserType();
            $browser_details = Request::server('HTTP_USER_AGENT', '');

            // store only last and first record to session
            $visit_data = new \stdClass();

            $visit_data->site_id = $site_id;
            $visit_data->created_at = $created_at;
            $visit_data->created_at_timestamp = $created_at_timestamp;
            $visit_data->ip_address = $ip_address;
            $visit_data->landing_page = $landing_page;
            $visit_data->referral = $referral;
            $visit_data->custom_referral = $custom_referral;
            $visit_data->browser_type = $browser_type;
            $visit_data->browser_details = $browser_details;
            $visit_data->geo_continent = $geo_data->continent;
            $visit_data->geo_country_code = $geo_data->country_code;
            $visit_data->geo_country_name = $geo_data->country_name;
            $visit_data->geo_city = $geo_data->city;
            $visit_data->geo_region = $geo_data->region;
            $visit_data->geo_latitude = $geo_data->latitude;
            $visit_data->geo_longitude = $geo_data->longitude;

            // record first visit reference to user's session
            if (!Session::get('first_visit')) {
                $first_visit = true;
                Session::put('first_visit', $visit_data);
            } else {
                $first_visit = false;
            }

            // up count visits
            $visit_data->visit_count = Session::get('last_visit') ? ++Session::get('last_visit')->visit_count : 1;

            Session::put('last_visit', $visit_data);

            // populate analytics
            $now = Carbon::createFromDate();
            $this_hour = Analytic
                ::where('site_id', $site_id)
                ->where('hour', $now->hour)
                ->where('day', $now->day)
                ->where('month', $now->month)
                ->where('year', $now->year)
                ->first();
            if($this_hour) {
                $this_hour->increment('pageviews');
                if($first_visit){
                    $this_hour->increment('new_users');
                }
            } else {
                Analytic::create([
                    'site_id' => $site_id,
                    'pageviews' => 1,
                    'new_users' => $first_visit ? 1 : 0,
                    'hour' => $now->hour,
                    'day' => $now->day,
                    'month' => $now->month,
                    'year' => $now->year,
                ]);
            }

            Notifications::checkNoTrackingHasBeenRecorded();
            Notifications::checkUrlHasBeenChanged();

        }

        // show 1x1 transparent png
        $response = Response::make(base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII='
        ), 200);

        $response->header('Content-Type', 'Content-Type: image/png');

        return $response;
    }

    protected function getBrowserType()
    {
        $user_agent = Request::server('HTTP_USER_AGENT', '');
        $browser_type = 'desktop';

        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$user_agent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($user_agent,0,4))) {

            $browser_type = 'mobile';
        }

        return $browser_type;
    }


    protected function getGeoLocationData()
    {
        $ip = Request::getClientIp();

        $cache_key = 'geo.'.$ip;

        // try from cache first
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        $data = $this->callGeoApi($ip);

        // store to cache
        Cache::put($cache_key, $data, Carbon::now()->addMinutes(120));

        return $data;
    }


    protected function callGeoApi($ip, $timeout = 5)
    {
        // default data set
        $ret = new \stdClass();

        $ret->ip  = $ip;
        $ret->continent = '';
        $ret->country_code = '';
        $ret->country_name = '';
        $ret->city = '';
        $ret->region = '';
        $ret->latitude = '';
        $ret->longitude = '';


        try {
            $ctx = stream_context_create(['http'=> ['timeout' => $timeout]]);
            $response = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip, false, $ctx));
        } catch (\Exception $e) {
            Log::writeException($e);
            $response = false;
        }

        if($response && isset($response['geoplugin_countryCode'])) {

            $ret->continent = $response['geoplugin_continentCode'];
            $ret->country_code = $response['geoplugin_countryCode'];
            $ret->country_name = $response['geoplugin_countryName'];
            $ret->city = $response['geoplugin_city'];
            $ret->region = $response['geoplugin_region'];
            $ret->latitude = $response['geoplugin_latitude'];
            $ret->longitude = $response['geoplugin_longitude'];
        }

        return $ret;
    }


    protected function isHuman()
    {

        $is_human = true;

        $robots = array(
            'googlebot'     => 'Googlebot',
            'msnbot'        => 'MSNBot',
            'baiduspider'   => 'Baiduspider',
            'bingbot'       => 'Bing',
            'slurp'         => 'Inktomi Slurp',
            'yahoo'         => 'Yahoo',
            'askjeeves'     => 'AskJeeves',
            'fastcrawler'   => 'FastCrawler',
            'infoseek'      => 'InfoSeek Robot 1.0',
            'lycos'         => 'Lycos',
            'yandex'        => 'YandexBot',
            'newrelic'      => 'NewRelicPinger',
        );

        foreach ($robots as $key => $value) {
            if (strpos(Request::server('HTTP_USER_AGENT', ''), $value) !== false) {
                $is_human = false;
                break;
            }
        }

        return $is_human;
    }

}
