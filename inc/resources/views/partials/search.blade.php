<div class="pull-right global-search">
    {!! Form::open(['url' => Request::path(), 'method' => 'GET', 'class' => '']) !!}
    <div class="input-group">
        <input type="text" placeholder="{{ trans('app.search') }}" class="form-control" name="search_term" value="{{ $search_term }}">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                @if($search_term)
                    <a href="{{ Request::path() }}" class="btn btn-default" type="submit"><i class="fa fa-remove"></i></a>
                @endif
            </span>
    </div>
    {!! Form::close() !!}
</div>
