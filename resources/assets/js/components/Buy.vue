<template>
	<div v-if="$root.user.authenticated">
 		<a v-if="!channel.has_current_user_bought_this_channel && !channel.is_owned_by_current_user" class="button is-info is-small" type="submit" @click.prevent="buy">Buy for ${{ channel.price }} </a>
 		<a v-else-if="channel.is_owned_by_current_user" class="tag is-info subscription-count">Selling for ${{ channel.price }}</a>
 		<a v-else class="tag is-primary subscription-count">Bought for ${{ channel.price }}</a>
 	</div>
</template>

<script>
    export default {
        props: ['channel', 'channelOwner'],

        data() {
            return {
                stripeToken: null ,
                stripeEmail: null,
            };
        },

        methods: {
            buy() {
                this.stripe.open({
                    name: this.channel.name,
                    description: this.channel.description,
                    zipCode: true,
                    amount: (this.channel.price * 100),
                })
            },

			cofigureStripeCheckout() {
				if (this.$root.user.authenticated) {
					this.stripe = StripeCheckout.configure({
					    key: this.channelOwner.stripe_key,
					    image: "https://s3.amazonaws.com/production.images.myengine.me/myengine.png",
					    locale: "auto",
					    token: (token) => {
					        this.stripeToken = token.id;
					        this.stripeEmail = token.email;

					        axios.post('/' + this.channel.slug + '/purchase', this.$data)
					             .then(({data}) => {
					             	this.channel.has_current_user_bought_this_channel = true;
									alert('Complete! Thanks for your payment');
					             })
					             .catch(({response}) => {
									this.channel.has_current_user_bought_this_channel = false;
									alert("Payment didn't go through. Something went wrong!");
					            });
					    }
					});
				}
			}
        },

        mounted() {
        	this.cofigureStripeCheckout();
        }
    }
</script>
