<div class="g-recaptcha" data-sitekey="<?= Config::get('recaptcha::siteKey'); ?>"></div>

<?= Marwelln\Recaptcha\Script::instance()->script(); ?>