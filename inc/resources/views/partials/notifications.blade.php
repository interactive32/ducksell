<?php
$notifications = \App\Services\Notifications::getNotifications();
$notification_count = count($notifications);
?>

<li class="dropdown messages-menu">
    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        <i class="fa fa-bell-o"></i>
        @if($notification_count)
            <span class="label label-danger">{{ $notification_count }}</span>
        @endif
    </a>
    <ul class="dropdown-menu">
        <li class="header">{{ trans('app.you_have_n_notifications', ['n' => $notification_count]) }}</li>
        <li>
            <div class="slimScrollDiv notifications">
                <ul class="menu">
                    @foreach($notifications as $notification)
                    <li>
                        <a href="{{ $notification['link'] }}" title="{{ $notification['text'] }}">
                            <div class="pull-left">
                                <i class="fa fa-warning fa-2x text-yellow"></i>
                            </div>
                            <h4>{{ $notification['name'] }}</h4>
                            <p>{!! $notification['text']  !!}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </li>
    </ul>
</li>
