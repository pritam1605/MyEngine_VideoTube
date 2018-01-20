Hello {{ $seller->getFirstNameOrUserName() }}!

You are receiving this email because your paid channel {{ $channel->name }} was subscribed by a new MyEngine user.
<br>
Receipt Details:
* Channel Title: {{ $channel->name }}
* Channel Price: ${{ number_format($channel->price, 2) }}
* Tax: ${{ number_format(0, 2) }}
* Discount: ${{ number_format(0, 2) }}
* MyEngine Commission: ${{ number_format($channel->calculateCommission(),2) }}
* Total Earning: ${{ number_format($channel->price - $channel->calculateCommission(), 2) }}


Thanks,<br>
{{ config('app.name') }}

<hr>
If you have any doubts regarding this sale, please get in touch with admin at admin@myengine.me