<template>
	<div class="control" v-if="subscriptionCount != null">
		<button class="button is-danger is-small" v-if="canCurrentUserSubscribe" @click="handle">{{ subscriptionButtonTitle }}</button>
	  	<a v-if="!channelPage" class="tag subscription-count">{{ pluralize('subscriber', subscriptionCount, true) }}</a>
	</div>
</template>

<script>
	import pluralize from 'pluralize';
	import eventHub from './../eventhub.js';

	export default {
		props: ['channelSlug', 'channelPage'],

		data() {
			return {
				error: null,
				subscriptionCount: null,
				canCurrentUserSubscribe: false,
				userSubscribed: false
			};
		},

		computed: {
			subscriptionButtonTitle() {
				return this.userSubscribed ? 'Unsubscribe' : 'Subscribe';
			}
		},

		methods: {
			pluralize,

			loadSubscribeButtonData() {
				axios.get('/channels/' + this.channelSlug + '/subscription')
				     .then(({data}) => {
				     	this.subscriptionCount = data.data.total_subscriptions;
						this.canCurrentUserSubscribe = data.data.can_current_user_subscribe;
						this.userSubscribed = data.data.is_current_user_subscribed;
				     })
				     .catch(({response}) => {
				     	this.error = response.data;
				     });
			},

			handle() {
				if (this.userSubscribed) {
					this.unsubscribe();
				} else {
					this.subscribe();
				}
			},

			subscribe() {
				this.userSubscribed = true;
				this.subscriptionCount++;
				eventHub.$emit('subscription-status-changed', 'subscribed');

				axios.post('/subscriptions/' + this.channelSlug)
					 .catch(({response}) => {
					 	this.error = response.data;
					 });

			},

			unsubscribe() {
				this.userSubscribed = false;
				this.subscriptionCount--;
				eventHub.$emit('subscription-status-changed', 'unsubscribed');

				axios.delete('/subscriptions/' + this.channelSlug)
				     .catch(({response}) => {
				     	this.error = response.data;
				     });

			}
		},

		mounted() {
			this.loadSubscribeButtonData();
		}
	}
</script>