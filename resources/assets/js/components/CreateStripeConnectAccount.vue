<template>
	<div class="control">
		<strong>
			<p v-if="!channelOwner.stripe_id" class="help is-danger">
				Note: You have not created a Stripe Account yet. Please create a stripe account to create paid channels &rarr; <a class="button is-danger is-small" :href="stripeConnectAccountCreationLink">Connect</a>
			</p>

			<p v-else class="help is-success">
				Note: You have already created a Stripe Account. You can now create paid channels.
			</p>
		</strong>
	</div>
</template>

<script>

	export default {

		props: ['channelOwner'],

		computed: {
			stripeConnectAccountCreationLink() {
				return 'https://connect.stripe.com/oauth/authorize?response_type=code&state=' + window.Laravel.csrfToken + '&scope=read_write&client_id=' + this.$root.stripeKey;
			}
		}
	}
</script>