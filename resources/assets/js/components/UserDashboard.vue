<template>
	<div class="container section profile" v-if="user">
		<div class="section profile-heading">
	    	<div class="columns">
				<div class="column is-6">
					<article class="media is-vertically-centered">
						<figure class="media-left">
					    	<p class="image is-128x128 avatar">
					      		<img :src="user.profile_image_url">
					    	</p>
					  	</figure>
		        		<span class="title is-bold">{{ user.full_name_or_username }}</span>
					</article>
				</div>
				<div class="column is-6">
					<nav class="level">
						<div class="level-item has-text-centered">
							<div>
							<p class="heading">{{ pluralize('Channel', totalUserChannels) }}</p>
							<p class="title">{{ totalUserChannels }}</p>
							</div>
						</div>
						<div class="level-item has-text-centered">
							<div>
							<p class="heading">{{ pluralize('Video', totalUserVideos) }}</p>
							<p class="title">{{ totalUserVideos }}</p>
							</div>
						</div>
						<div class="level-item has-text-centered">
							<div>
							<p class="heading">{{ pluralize('Video View', totalVideoViews) }}</p>
							<p class="title">{{ totalVideoViews }}</p>
							</div>
						</div>
					</nav>
				</div>
	    	</div>
	  	</div>
	  	<hr>

		<div v-if="columns">
			<div class="columns" v-for="(n, i) in columns">
				<dashboard-channel-list v-if="channels.length" v-for="channel in channelChuncks[i]" :channel="channel" :key="channel.id"></dashboard-channel-list>
			</div>
	  	</div>

	  	<div v-else>
	  		<div class="box">
	  			Sorry, no channels created yet.
	  		</div>
	  	</div>
	</div>
</template>

<script>
	import pluralize from 'pluralize';
	import eventHub from './../eventhub.js';

	export default {

		props: ['user'],

		data() {
			return {
				channels: [],
				error: null,
				columns: null,
				channelChuncks: [],
				totalUserChannels: 0,
				totalUserVideos: 0,
				totalVideoViews: 0
			};
		},

		methods: {
			pluralize,

			loadUserDashboardData() {
				axios.get(this.user.username + '/user-channels')
				     .then(({data}) => {
				     	this.channels = data.data.channels;

				     	this.totalUserChannels = this.channels.length;

				     	this.channels.forEach((currentValue) => {
							this.totalUserVideos += currentValue.total_video_count;
							this.totalVideoViews += currentValue.total_video_views;
						});

				     	this.divideVideosArrayInChuncks();
				     })
				     .catch(({response}) => {
				     	this.error = response.data;
				     });
			},

			divideVideosArrayInChuncks() {
				let j = 0;
				for(let i = 0; i < this.channels.length; i+= 3) {
					// we are incrementing by 3 because we are using is-4 in the grid
					this.channelChuncks[j++] = this.channels.slice(i, i+3);
				}

				this.columns = this.channelChuncks.length;
			},

			changeSubscriptionCount(status) {

				if (status === 'subscribed') {
					this.channel.total_subscriptions++;
				} else {
					this.channel.total_subscriptions--;
				}
			}
		},

		mounted() {
			this.loadUserDashboardData();
		}
	}
</script>