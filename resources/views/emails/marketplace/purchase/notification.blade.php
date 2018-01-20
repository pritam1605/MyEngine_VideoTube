@component('mail::message')
<strong>Hello {{ $buyer->getFirstNameOrUserName() }}!</strong>

You have successfully subscribed and purchased the paid channel <strong>{{ $channel->name }}</strong>.
<br>
<br>
<strong>Receipt Details:</strong>
* <strong>Channel Title:</strong> {{ $channel->name }}
* <strong>Channel Price:</strong> ${{ number_format($channel->price, 2) }}
* <strong>Tax:</strong> ${{ number_format(0, 2) }}
* <strong>Discount:</strong> ${{ number_format(0, 2) }}
* <strong>Total:</strong> ${{ number_format($channel->price, 2) }}

Thanks,<br>
{{ config('app.name') }}

<hr>
If you did not make this purchase, please get in touch with admin at admin@myengine.me

@endcomponent