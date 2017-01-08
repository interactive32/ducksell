<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Analytic extends BaseModel
{

    public $timestamps = false;
    public $guarded = [];
    public $site_id = null;
    private $sum_pageviews_col = 'sum_pageviews';
    private $sum_new_users_col = 'sum_new_users';


    public function scopeForSite($query)
    {
        // only active sites, skip soft deleted
        $query->join('sites', 'sites.id', '=', 'analytics.site_id');
        $query->whereNull('sites.deleted_at');

        if($this->site_id) {
            return $query->where('sites.id', $this->site_id);
        } else {
            return $query;
        }
    }

    public function setSiteId($id)
    {
        $this->site_id = $id;
    }

    private function addToCollection(Collection $collection, $y, $row)
    {
        $data['y'] = $y;
        $data['pageviews'] = isset($row->{$this->sum_pageviews_col}) ? $row->{$this->sum_pageviews_col} : 0;
        $data['new_users'] = isset($row->{$this->sum_new_users_col}) ? $row->{$this->sum_new_users_col} : 0;

        $collection->push($data);
    }

    public function getVisitsToday()
    {
        $start = Carbon::create()->startOfDay();

        $rows = $this
            ->selectRaw("analytics.hour, analytics.year, analytics.month, analytics.day, sum(analytics.pageviews) as {$this->sum_pageviews_col}, sum(analytics.new_users) as {$this->sum_new_users_col}")
            ->where('year', '=', $start->year)
            ->where('month', '=', $start->month)
            ->where('day', '=', $start->day)
            ->forSite()
            ->groupBy('hour', 'day', 'month', 'year')
            ->get();

        $ret = collect();

        for($x = 0; $x < 24; $x++){

            $d = Carbon::create()->startOfDay()->addHours($x);

            $row = $rows
                ->where('day', $d->day, false)
                ->where('hour', $d->hour, false)
                ->where('year', $d->year, false)
                ->first();

            $this->addToCollection($ret, $d->toDateString() .' '. $d->hour .':00', $row);
         }

        return $ret;
    }

    public function getVisitsByDays(Carbon $time_from, $days)
    {
        $current = Carbon::create();

        if($time_from->year != $current->year) {
            $rows = $this
                ->selectRaw("analytics.year, analytics.month, analytics.day, sum(analytics.pageviews) as {$this->sum_pageviews_col}, sum(analytics.new_users) as {$this->sum_new_users_col}")
                ->whereRaw('((year = ? and month >= ?) or (year = ?))', [$time_from->year, $time_from->month, $current->year])
                ->forSite()
                ->groupBy('day', 'month', 'year')
                ->get();
        } else {
            $rows = $this
                ->selectRaw("analytics.year, analytics.month, analytics.day, sum(analytics.pageviews) as {$this->sum_pageviews_col}, sum(analytics.new_users) as {$this->sum_new_users_col}")
                ->where('year', '=', $time_from->year)
                ->where('month', '>=', $time_from->month)
                ->forSite()
                ->groupBy('day', 'month', 'year')
                ->get();
        }

        $ret = collect();

        $diff_days = $time_from->diffInDays();

        for($x = 0; $x < $days; $x++) {

            $d = Carbon::create()->subDays($diff_days - $x);

            $row = $rows
                ->where('day', $d->day, false)
                ->where('month', $d->month, false)
                ->where('year', $d->year, false)
                ->first();

            $this->addToCollection($ret, $d->toDateString(), $row);
        }

        return $ret;
    }

    public function getVisitsByMonths(Carbon $time_from)
    {
        $rows = $this
            ->selectRaw("analytics.year, analytics.month, sum(analytics.pageviews) as {$this->sum_pageviews_col}, sum(analytics.new_users) as {$this->sum_new_users_col}")
            ->where('year', '=', $time_from->year)
            ->forSite()
            ->groupBy('month', 'year')
            ->get();

        $ret = collect();

        for($x = 0; $x < 12; $x++){

            $d = Carbon::create()->startOfYear()->addMonths($x);

            $row = $rows
                ->where('month', $d->month, false)
                ->where('year', $d->year, false)
                ->first();

            $this->addToCollection($ret, $d->toDateString(), $row);
        }

        return $ret;
    }

    public function getSales(Carbon $time_from, Carbon $time_to = null)
    {
        return Transaction::getSalesAmountByCurrency($time_from, $time_to);
    }

}
