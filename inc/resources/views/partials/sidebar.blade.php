<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="@if(getRouteName() == 'dashboard@dashboard'){{ 'active' }}@endif"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i><span>{{ trans('app.dashboard') }}</span></a></li>
            <li class="@if(getRouteName() == 'reports@visits'){{ 'active' }}@endif"><a href="{{ url('/reports') }}"><i class="fa fa-line-chart"></i><span>{{ trans('app.reports') }}</span></a></li>
            <li class="@if(getRouteName() == 'transaction@index'){{ 'active' }}@endif"><a href="{{ url('/transactions') }}"><i class="fa fa-shopping-cart"></i><span>{{ trans('app.transactions') }}</span></a></li>
            <li class="@if(getRouteName() == 'customer@index'){{ 'active' }}@endif"><a href="{{ url('/customers') }}"><i class="fa fa-users"></i><span>{{ trans('app.customers') }}</span></a></li>
            <li class="@if(getRouteName() == 'product@index'){{ 'active' }}@endif"><a href="{{ url('/products') }}"><i class="fa fa-cubes"></i><span>{{ trans('app.products') }}</span></a></li>
            <li class="@if(getRouteName() == 'files@index'){{ 'active' }}@endif"><a href="{{ url('/files') }}"><i class="fa fa-file"></i><span>{{ trans('app.files') }}</span></a></li>
            @if(isAdmin())<li class="@if(getRouteName() == 'site@index'){{ 'active' }}@endif"><a href="{{ url('/sites') }}"><i class="fa fa-sitemap"></i><span>{{ trans('app.sites') }}</span></a></li>@endif
            <li class="@if(getRouteName() == 'reports@conversions'){{ 'active' }}@endif"><a href="{{ url('/conversions') }}"><i class="fa fa-share-alt"></i><span>{{ trans('app.conversions') }}</span></a></li>
            @if(isAdmin())
                <li class="header"><span class="pull-left">{{ strtoupper(trans('app.plugins')) }}</span>@if(!env('LOAD_PLUGINS'))<span class="text-danger text-bold pull-right">{{ trans('app.disabled') }}</span>@endif</li>
                @foreach(Event::fire(new \App\Events\PluginMenu()) as $item)
                    {!! $item !!}
                @endforeach
                <li class="@if(getRouteName() == 'plugin@index'){{ 'active' }}@endif"><a href="{{ url('/plugins') }}"><i class="fa fa-cogs"></i><span>{{ trans('app.manage') }}</span></a></li>
                <li class="header">{{ strtoupper(trans('app.settings')) }}</li>
                <li class="@if(getRouteName() == 'option@index'){{ 'active' }}@endif"><a href="{{ url('/options') }}"><i class="fa fa-cogs"></i><span>{{ trans('app.options') }}</span></a></li>
                <li class="@if(getRouteName() == 'profiles@index'){{ 'active' }}@endif"><a href="{{ url('/profiles') }}"><i class="fa fa-key"></i><span>{{ trans('app.profiles') }}</span></a></li>
                <li class="@if(getRouteName() == 'reports@logs'){{ 'active' }}@endif"><a href="{{ url('/logs') }}"><i class="fa fa-book"></i><span>{{ trans('app.logs') }}</span></a></li>
            @endif
        </ul>
    </section>
</aside>