@if (count($errors) > 0)
    <div class="alert alert-warning alert-dismissible">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-warning"></i> {{ trans('app.alert') }}</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif