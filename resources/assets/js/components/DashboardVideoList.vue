<template>
	<div class="column is-4">
		<div class="card">
			<div class="card-image">
				<figure class="image is-16by9">
					<a :href="videoUrl">
						<img :src="video.thumbnail" :alt="videoImageAlt">
					</a>
				</figure>
			</div>
			<div class="card-content">
				<div class="media">
					<div class="media-content">
						<strong><p><a class="is-5" :href="videoUrl">{{ video.title }}</a></p></strong>
						<small>
							<p>
								<a class="is-6" :href="channelUrl">{{ video.channel.name }}</a>
								<span class="timestamp">({{ video.created_at | humanReadableDate }})</span>
							</p>
						</small>
					</div>
				</div>

				<div class="media-content center-text">
					<p class="panel-block-item">
						{{ pluralize('vote', video.votes, true) }} | {{ pluralize('comment', video.comments, true) }} | {{ pluralize('view', video.views, true) }}
					</p>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import pluralize from 'pluralize';

	export default {
		props: ['video'],

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
				return '/channels/' + this.video.channel.slug;
			},

			videoUrl() {
				return '/videos/' + this.video.uid;
			},

			videoImageAlt() {
				return this.video.title + ' thumbnail';
			}
		},

		methods: {
			pluralize
		}
	}
</script>