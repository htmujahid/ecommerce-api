<x-mail::message>
# Order

A new order has been placed. You can get further details about the order by logging into admin panel.

<x-mail::button :url="config('app.url')">
Click Here
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
