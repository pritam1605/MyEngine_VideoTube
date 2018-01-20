<template>
	<div class="column is-4">
		<div class="card">
			<div class="card-image">
				<figure class="image is-user-channel-dashboard">
					<a :href="channelUrl">
						<img :src="channel.imageUrl" :alt="channelImageAlt">
					</a>
				</figure>
			</div>
			<div class="card-content">
				<div class="media">
					<div class="media-content">
						<div class="columns">
							<div class="column is-6">
								<strong><p><a class="is-5" :href="channelUrl">{{ channel.name }}</a></p></strong>
								<small><div class="timestamp">Updated ({{ channel.updated_at | humanReadableDate }})</div></small>
							</div>
							<div class="column is-6">
								<p class="is-small">
									<a v-if="!channel.is_owned_by_current_user">
										<subscribe-button v-if="channel.slug" :channelSlug="channel.slug"></subscribe-button>
		          					</a>
		          				</p>
							</div>
						</div>
					</div>
				</div>

				<div class="media-content center-text">
					<p class="panel-block-item">
						{{ pluralize('Video', channel.total_video_count, true) }} | {{ pluralize('View', channel.total_video_views, true) }}
					</p>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import pluralize from 'pluralize';

	export default {
		props: ['channel'],

		data() {
			return {
				error: null,
			};
		},

		filters: {
			humanReadableDate(timeStamp) {
		    	return moment(timeStamp).fromNow();
		    },
		},

		computed: {
			channelUrl() {
				return '/channels/' + this.channel.slug;
			},

			videoUrl() {
				return '/videos/' + this.video.uid;
			},

			channelImageAlt() {
				return this.channel.name + ' thumbnail';
			}
		},

		methods: {
			pluralize
		}
	}
</script>