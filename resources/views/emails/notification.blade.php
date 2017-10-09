@component('mail::message')
# Task Notification

"{{$task->description}}"

Thanks,<br>
{{ config('app.name') }}
@endcomponent