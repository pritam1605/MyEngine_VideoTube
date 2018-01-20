Hello {{ $buyer->getFirstNameOrUserName() }}!

You have successfully subscribed and purchased the paid channel {{ $channel->name }}.
<br>
Receipt Details:
* Channel Title: {{ $channel->name }}
* Channel Price: ${{ number_format($channel->price, 2) }}
* Tax: ${{ number_format(0, 2) }}
* Discount: ${{ number_format(0, 2) }}
* Total: ${{ number_format($channel->price, 2) }}



Thanks,
{{ config('app.name') }}

If you did not make this purchase, please get in touch with admin at admin@myengine.me