@component('mail::message')
<strong>Hello {{ $seller->getFirstNameOrUserName() }}!</strong>

You are receiving this email because your paid channel <strong>{{ $channel->name }}</strong> was subscribed by a new MyEngine user.
<br>
<br>
<strong>Receipt Details:</strong>
* <strong>Channel Title:</strong> {{ $channel->name }}
* <strong>Channel Price:</strong> ${{ number_format($channel->price, 2) }}
* <strong>Tax:</strong> ${{ number_format(0, 2) }}
* <strong>Discount:</strong> ${{ number_format(0, 2) }}
* <strong>MyEngine Commission:</strong> ${{ number_format($channel->calculateCommission(),2) }}
* <strong>Total Earning:</strong> ${{ number_format($channel->price - $channel->calculateCommission(), 2) }}

Thanks,<br>
{{ config('app.name') }}

<hr>
If you have any doubts regarding this sale, please get in touch with admin at admin@myengine.me

@endcomponent