@extends('app')

@section('content')

<section>
    <div class="row">
        <div class="col-lg-3">
            <div class="box box-success box-solid">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.env_setup') }}</h3>
                </div>
                <div class="box-body">
                    <strong>{{ trans('app.application_version') }}</strong>
                    <p class="text-muted">{{ APP_VERSION }}</p>
                    <strong>{{ trans('app.database_version') }}</strong>
                    <p class="text-muted">{{ $options->where('key', 'global.schema')->first()->value }}</p>
                    <strong>{{ trans('app.database_name') }}</strong>
                    <p class="text-muted">{{ env('DB_DATABASE', '') }}</p>
                    <strong>{{ trans('app.database_host') }}</strong>
                    <p class="text-muted">{{  env('DB_HOST', '') }}</p>
                    <strong>{{ trans('app.timezone') }}</strong>
                    <p class="text-muted">{{ env('APP_TIMEZONE', '').' ('.\Carbon\Carbon::create()->format('H:i').')' }}</p>
                    <strong>{{ trans('app.debugging') }}</strong>
                    <p class="text-muted">{{ (env('APP_DEBUG', false) ? trans('app.enabled') : trans('app.disabled')) }}</p>
                    <hr>
                    <p>{{ trans('app.env_location') }}</p>
                    <p>{{base_path().'/.env' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="box box-success box-solid">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.options') }}</h3>
                </div>
                <div class="box-body">
                    <div class="box no-shadow no-border">
                        <div class="box-body">
                            {!! Form::open() !!}
                            <div class="nav-tabs-custom no-shadow">
                                <ul class="nav nav-tabs">
                                    <li class="@if(!isset($_GET['section'])){{ 'active' }}@endif"><a data-toggle="tab" href="#tab-1">{{ trans('app.general') }}</a></li>
                                    <li class="@if(isset($_GET['section']) && $_GET['section'] == 'mail'){{ 'active' }}@endif"><a data-toggle="tab" href="#tab-2">{{ trans('app.mail_setup') }}</a></li>
                                    <li class="@if(isset($_GET['section']) && $_GET['section'] == 'templates'){{ 'active' }}@endif"><a data-toggle="tab" href="#tab-3">{{ trans('app.mail_templates') }}</a></li>
                                    <li class="@if(isset($_GET['section']) && $_GET['section'] == 'invoice'){{ 'active' }}@endif"><a data-toggle="tab" href="#tab-4">{{ trans('app.invoice') }}</a></li>
                                </ul>
                            </div>
                            @include('partials.form_errors')
                            <div class="tab-content">
                                <div class="tab-pane @if(!isset($_GET['section'])){{ 'active' }}@endif" id="tab-1">
                                    <div class="form-group">
                                        {!! Form::label('global_locale', trans('app.language_selection')) !!}
                                        {!! Form::select('global_locale', \App\Services\Util::getAvailableLanguages(), $options->where('key', 'global.locale')->first() ? $options->where('key', 'global.locale')->first()->value : 'en', ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_admin-mail', trans('app.admin_mail')) !!}
                                        {!! Form::text('global_admin-mail', $options->where('key', 'global.admin-mail')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_report-errors', trans('app.report_errors')) !!}
                                        {!! Form::select('global_report-errors', ['1' => trans('app.yes'), '0' => trans('app.no')], $options->where('key', 'global.report-errors')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('session_lifetime', trans('app.session_lifetime')) !!}
                                        {!! Form::text('session_lifetime', $options->where('key', 'session.lifetime')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_allow-direct-links', trans('app.allow_direct_links')) !!}
                                        {!! Form::select('global_allow-direct-links', [1 => trans('app.yes'), 0 => trans('app.no_direct_links')], $options->where('key', 'global.allow-direct-links')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_allow-download-pending', trans('app.allow_download_pending')) !!}
                                        {!! Form::select('global_allow-download-pending', ['1' => trans('app.yes'), '0' => trans('app.no')], $options->where('key', 'global.allow-download-pending')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_license-expiration', trans('app.expires_days')) !!}
                                        {!! Form::text('global_license-expiration', $options->where('key', 'global.license-expiration')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_allow-certificate', trans('app.allow_license_certificate_button')) !!}
                                        {!! Form::select('global_allow-certificate', ['1' => trans('app.yes'), '0' => trans('app.no')], $options->where('key', 'global.allow-certificate')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_ssl-tracing', trans('app.ssl_tracking')) !!}
                                        @if(strpos(url(), 'https://') === false)
                                            <span class="pull-right">
                                                <a target="_blank" href="https://{{ \App\Services\Util::stripProtocol(url()) }}"><small>{{ trans('app.not_on_ssl') }}</small></a>
                                            </span>
                                        @endif
                                        {!! Form::select('global_ssl-tracking', ['1' => trans('app.yes'), '0' => trans('app.no')], $options->where('key', 'global.ssl-tracking')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_min-password', trans('app.min_password')) !!}
                                        {!! Form::text('global_min-password', $options->where('key', 'global.min-password')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_recaptcha-sitekey', trans('app.recaptcha_site_key')) !!}
                                        {!! Form::text('global_recaptcha-sitekey', $options->where('key', 'global.recaptcha-sitekey')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_recaptcha-secretkey', trans('app.recaptcha_secret_key')) !!}
                                        {!! Form::text('global_recaptcha-secretkey', $options->where('key', 'global.recaptcha-secretkey')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_preserve-filenames', trans('app.preserve_filenames')) !!}
                                        {!! Form::select('global_preserve-filenames', ['1' => trans('app.yes'), '0' => trans('app.no')], $options->where('key', 'global.preserve-filenames')->first() ? $options->where('key', 'global.preserve-filenames')->first()->value : 0, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="tab-pane @if(isset($_GET['section']) && $_GET['section'] == 'mail'){{ 'active' }}@endif" id="tab-2">
                                    <div class="form-group">
                                        {!! Form::label('mail_driver', trans('app.mail_driver')) !!}
                                        {!! Form::select('mail_driver', ['' => trans('app.none')] + config('global.email-drivers'), $options->where('key', 'mail.driver')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('mail_from_address', trans('app.mail_from_address')) !!}
                                        {!! Form::text('mail_from_address', $options->where('key', 'mail.from.address')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('mail_from_name', trans('app.mail_from_name')) !!}
                                        {!! Form::text('mail_from_name', $options->where('key', 'mail.from.name')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('mail_host', trans('app.mail_host')) !!}
                                        {!! Form::text('mail_host', $options->where('key', 'mail.host')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('mail_port', trans('app.mail_port')) !!}
                                        {!! Form::text('mail_port', $options->where('key', 'mail.port')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('mail_encryption', trans('app.mail_encryption')) !!}
                                        {!! Form::select('mail_encryption', config('global.email-encryption'), $options->where('key', 'mail.encryption')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('mail_username', trans('app.mail_username')) !!}
                                        {!! Form::text('mail_username', $options->where('key', 'mail.username')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <!-- autocomplete bug  -->
                                    <input style="display:none"><input type="password" style="display:none">
                                    <div class="form-group">
                                        {!! Form::label('mail_password', trans('app.mail_password')) !!}
                                        {!! Form::text('mail_password', $options->where('key', 'mail.password')->first()->value, ['class' => 'form-control']) !!}
                                    </div>

                                </div>
                                <div class="tab-pane @if(isset($_GET['section']) && $_GET['section'] == 'templates'){{ 'active' }}@endif" id="tab-3">
                                    <div class="form-group">
                                        {!! Form::label('mail_template_thankyou', trans('app.mail_template_thankyou')) !!}
                                        <span class="pull-right">
                                                <a target="_blank" href="{{ url('options/templatetest/thankyou') }}"><small>{{ trans('app.mail_test') }}</small></a>
                                        </span>
                                        {!! Form::textarea('mail_template_thankyou', $options->where('key', 'mail.template.thankyou')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('mail_template_generic', trans('app.mail_template_generic')) !!}
                                        <span class="pull-right">
                                                <a target="_blank" href="{{ url('options/templatetest/generic') }}"><small>{{ trans('app.mail_test') }}</small></a>
                                        </span>
                                        {!! Form::textarea('mail_template_generic', $options->where('key', 'mail.template.generic')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="tab-pane @if(isset($_GET['section']) && $_GET['section'] == 'invoice'){{ 'active' }}@endif" id="tab-4">
                                    <div class="form-group">
                                        {!! Form::label('global_allow-invoice', trans('app.allow_invoice_button')) !!}
                                        {!! Form::select('global_allow-invoice', ['1' => trans('app.yes'), '0' => trans('app.no')], $options->where('key', 'global.allow-invoice')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_company-name', trans('app.company_name')) !!}
                                        {!! Form::text('global_company-name', $options->where('key', 'global.company-name')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_company-details', trans('app.company_details')) !!}
                                        {!! Form::textarea('global_company-details', $options->where('key', 'global.company-details')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_invoice-footer', trans('app.invoice_footer')) !!}
                                        {!! Form::textarea('global_invoice-footer', $options->where('key', 'global.invoice-footer')->first()->value, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_default-manual-currency', trans('app.default_manual_currency')) !!}
                                        {!! Form::text('global_default-manual-currency', $options->where('key', 'global.default-manual-currency')->first()->value, ['class' => 'form-control', 'required']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('global_invoice-offset', trans('app.invoice_offset')) !!}
                                        {!! Form::text('global_invoice-offset', $options->where('key', 'global.invoice-offset')->first() ? $options->where('key', 'global.invoice-offset')->first()->value : 0, ['class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            {!! Form::submit(trans('app.submit'), array('class' => 'btn btn-primary pull-right')) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="box box-success box-solid">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.mail_setup') }}</h3>
                </div>
                @if(config('mail.driver'))
                <div class="box-body">
                    <p>{{ trans('app.mail_driver') .': '. config('mail.driver') }}</p>
                    <p>{{ trans('app.mail_host') .': '. config('mail.host') }}</p>
                    <p>{{ trans('app.mail_port') .': '. config('mail.port') }}</p>
                    <p>{{ trans('app.mail_from_address') .': '. config('mail.from.address') }}</p>
                    <p>{{ trans('app.mail_from_name') .': '. config('mail.from.name') }}</p>
                    <p>{{ trans('app.mail_encryption') .': '. config('mail.encryption') }}</p>
                    <p>{{ trans('app.mail_username') .': '. config('mail.username') }}</p>
                    <p>{{ trans('app.mail_password') .': '. config('mail.password') }}</p>
                </div>
                <div class="box-footer text-right">
                    <a href="{{ url('options/mailtest') }}" class="btn btn-success">{{ trans('app.mail_test') }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
