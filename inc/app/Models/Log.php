<?php namespace App\Models;

use App\Events\LogWrite;
use App\Events\LogWriteException;
use App\Services\TemplateService;
use App\Services\Util;
use Event;
use Exception;
use Lang;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Log extends BaseModel
{
    public $guarded = [];

    const TYPE_EXCEPTION = 'EXCEPTION';
    const TYPE_MESSAGE = 'MESSAGE';
    const TYPE_CRITICAL = 'CRITICAL';
    const TYPE_DELETE = 'DELETE';

    public static function writeException(Exception $e, $skip_db_exceptions = false, $skip_http_exceptions = true)
    {
        Event::fire(new LogWriteException($e));

        // something wrong with the database, skip db logging
        if($skip_db_exceptions && $e instanceof \PDOException) {
            return false;
        }

        // do not log http exceptions, not found pages etc
        if($skip_http_exceptions && $e instanceof HttpException) {
            return false;
        }

        $log = new Log();
        $log->type = self::TYPE_EXCEPTION;
        $log->message = get_class($e); ;
        $log->data = $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString();
        $log->save();

    }

    public static function write($message, $data, $inform_admin = false, $type = self::TYPE_MESSAGE)
    {

        $log = new Log();
        $log->type = $type;
        $log->message = $message;
        $log->data = $data;
        $log->save();

        Event::fire(new LogWrite($log, $inform_admin));

        if($inform_admin && config('global.report-errors')) {
            if(Lang::has('app.'.$message)) {
                $message = trans('app.'.$message);
            }

            $TemplateService = new TemplateService();
            $TemplateService->loadSystemTemplate('generic');
            $TemplateService->setVars(['content' => $message]);

            Util::sendMail(config('global.admin-mail'), trans('app.important'), $TemplateService->render());
        }
    }

}
