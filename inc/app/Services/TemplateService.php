<?php namespace App\Services;

use Wpb\StringBladeCompiler\Facades\StringView;

/**
 * Service
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class TemplateService {

    private $content = '';
    private $vars = [];

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setVars($vars)
    {
        $this->vars = $vars;
    }

    public function render()
    {
        try {
            $ret = StringView::make(['template'  => $this->content, 'cache_key' => 'stringview', 'updated_at' => 0], $this->vars);
            $ret = $ret->render();
            $ret = nl2br($ret);
        } catch (\Exception $e) {
            return false;
        }

        return $ret;
    }

    public function loadSystemTemplate($template_name)
    {
        if(!method_exists($this, '_'.$template_name)) {
            throw new \Exception(trans('app.template_error'));
        }

        $this->{'_'.$template_name}();
    }

    private function _generic()
    {
        $this->content = config('mail.template.generic');
        $this->vars = [
            'content' => trans('app.mail_test_content'),
        ];
    }

    private function _thankyou()
    {
        $this->content = config('mail.template.thankyou');
        $this->vars = [
            'direct_link' => 'http://example.com/123456789',
            'user_email' => 'test@example.com',
            'user_password' => '123456',
        ];
    }
}
