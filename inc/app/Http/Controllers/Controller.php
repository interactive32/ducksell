<?php namespace App\Http\Controllers;

use App\Events\ContentBodyBottom;
use App\Events\ContentBodyTop;
use App\Events\ContentHead;
use App\Events\ControllerBoot;
use App\Services\Util;
use Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Input;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use View;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	public function __construct() {

        View::share('search_term', Input::get('search_term'));

        $this->addPluginContent();

        Event::fire(new ControllerBoot($this));

    }

    protected function addPluginContent()
    {
        $plugins_head = $plugins_body_top = $plugins_body_bottom = '';

        $head = Event::fire(new ContentHead());

        if($head) {
            foreach($head as $head_content) {
                $plugins_head .= $head_content;
            }
        }

        $body_top = Event::fire(new ContentBodyTop());

        if($body_top) {
            foreach($body_top as $body_top_content) {
                $plugins_body_top .= $body_top_content;
            }
        }

        $body_bottom = Event::fire(new ContentBodyBottom());

        if($body_bottom) {
            foreach($body_bottom as $body_bottom_content) {
                $plugins_body_bottom .= $body_bottom_content;
            }
        }

        View::share('plugins_head', $plugins_head);
        View::share('plugins_body_top', $plugins_body_top);
        View::share('plugins_body_bottom', $plugins_body_bottom);
    }

    protected function isExport()
    {
        return Input::get('export', false) !== false;
    }

    protected function export(Builder $builder)
    {
        $file_name = mt_rand(1000, 9999);
        $file_name_csv = $file_name.'.csv';

        $data = $builder->get();

        Excel::create($file_name, function($Excel) use ($data) {

            foreach ($data as &$obj) {
                $obj = (array)$obj;
            }

            $Excel->sheet('Sheet1', function($sheet) use ($data) {

                $sheet->fromArray($data);

            });

        })->store('csv', config('global.tmp'));

        return Response::download(config('global.tmp').$file_name_csv, $file_name_csv);
    }
}
