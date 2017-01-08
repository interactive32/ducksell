@if (Session::has('flash_notification.message'))
    <div id="flash-notification-box">
        <div class="alert alert-{{ Session::get('flash_notification.level') }} alert-dismissible">
            <button data-dismiss="alert" class="close" type="button">&times;</button>
            <i class="icon fa fa-info"></i> {{ Session::get('flash_notification.message') }}
        </div>
    </div>
@endif