<template>
	<div class="container section profile" v-if="channel">
		<div class="section profile-heading">
	    	<div class="columns">
				<div class="column is-6">
					<article class="media is-vertically-centered">
						<figure class="media-left">
					    	<p class="image is-128x128 avatar">
					      		<img :src="channel.imageUrl">
					    	</p>
					  	</figure>
					  	<div class="media-content">
					    	<div class="content">
					      		<p>
					        		<span class="title is-bold">{{ channel.name }}</span>
									<small><div class="timestamp">Last updated <strong>{{ humanReadableDate(channel.updated_at) }}</strong></div></small>
									<div v-if="channel.description">
										<p v-if="!showMore">{{ channelDescription }} <a v-if="isDescriptionLong" @click.prevent="showMore = true">read more</a></p>
										<p v-if="showMore">{{ channel.description }} <a @click.prevent="showMore = false">read less</a></p>
									</div>

									<div v-else>
										<p class="is-medium closer">No description available.</p>
									</div>
					      		</p>
					    	</div>
							<div class="channel-dashboard-buttons">
								<div class="columns">
									<div class="column is-4" v-if="!channel.is_owned_by_current_user">
										<subscribe-button v-if="channel.slug" :channelSlug="channel.slug" channelPage="true"></subscribe-button>
									</div>

									<div class="column is-4">
										<buy-button v-if="$root.user.id && channel.type === 'paid'" :channel="channel" :channelOwner="channelOwner"></buy-button>
									</div>
								</div>
							</div>
						</div>
					</article>
				</div>
				<div class="column is-6">
					<nav class="level">
						<div class="level-item has-text-centered">
							<div>
							<p class="heading">{{ pluralize('subscriber', channel.total_subscriptions) }}</p>
							<p class="title">{{ channel.total_subscriptions }}</p>
							</div>
						</div>
						<div class="level-item has-text-centered">
							<div>
							<p class="heading">{{ pluralize('video', videos.length) }}</p>
							<p class="title">{{ videos.length }}</p>
							</div>
						</div>
						<div class="level-item has-text-centered">
							<div>
							<p class="heading">{{ pluralize('video view', channel.total_video_views) }}</p>
							<p class="title">{{ channel.total_video_views }}</p>
							</div>
						</div>
					</nav>
				</div>
	    	</div>
	  	</div>
	  	<hr>

		<div v-if="columns">
			<div class="columns" v-for="(n, i) in columns">
				<dashboard-video-list v-if="videos.length" v-for="video in videoChuncks[i]" :video="video" :key="video.id"></dashboard-video-list>
			</div>
		</div>

	  	<div v-else>
	  		<div class="box">
				<p>Sorry, no videos to watch yet.</p>
			</div>
	  	</div>
	</div>
</template>

<script>
	import pluralize from 'pluralize';
	import eventHub from './../eventhub.js';

	export default {

		props: ['channelSlug'],

		data() {
			return {
				videos: [],
				error: null,
				columns: null,
				channel: null,
				videoChuncks: [],
				showMore: false,
				isDescriptionLong: false,
				channelOwner: null
			};
		},

		computed: {
			channelDescription() {
				if (this.channel.description) {
					let description = this.channel.description;

					if (description.length > 65) {
						this.isDescriptionLong = true;
						return description.substring(0,65) + '....';
					}

					this.isDescriptionLong = false;
					return description;
				}
			},
		},

		methods: {
			pluralize,

			humanReadableDate(timeStamp) {
				return moment(timeStamp).fromNow();
		    },

			loadChannelDashboardData() {
				axios.get('/channels/' + this.channelSlug + '/channel-data')
				     .then(({data}) => {
				     	this.videos = data.data.videos;
				     	this.channel = data.data.channel;

				     	this.divideVideosArrayInChuncks();
				     })
				     .catch(({response}) => {
				     	this.error = response.data;
				     });
			},

			divideVideosArrayInChuncks() {
				let j = 0;
				for(let i = 0; i < this.videos.length; i+= 3) {
					// we are incrementing by 3 because we are using is-4 in the grid
					this.videoChuncks[j++] = this.videos.slice(i, i+3);
				}

				this.columns = this.videoChuncks.length;
			},

			changeSubscriptionCount(status) {

				if (status === 'subscribed') {
					this.channel.total_subscriptions++;
				} else {
					this.channel.total_subscriptions--;
				}
			},

			showMoreContent() {
				if (this.channelDescription !== this.channel.description) {
					this.showMore = !this.showMore;
				}
			},

            getChannelOwnerStripeKey() {
				if (this.$root.user.authenticated) {
					axios.get('/' + this.channelSlug+ '/get-user-info-from-channel')
						 .then(({data}) => {
							this.channelOwner = data.data.channelOwner;
					     })
					     .catch(({response}) => {
							this.error = response.data;
					     });
				}

			},
		},

		mounted() {
			this.loadChannelDashboardData();
			this.getChannelOwnerStripeKey();
			eventHub.$on('subscription-status-changed', this.changeSubscriptionCount)

		}
	}
</script>