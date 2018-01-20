<template>
		<article class="media related-card is-mobile">
			<div class="media-left">
				<figure class="image">
					<a :href="videoUrl"><img class="image is-140x75" :src="video.thumbnail" :alt="imageAlt"></a>
				</figure>
			</div>
			<div class="media-content">
				<div class="content">
					<p>
						<span class="video-title"><strong><a :href="videoUrl">{{ video.title }}</a></strong></span>
						<span class="video-account"><a :href="channelUrl">{{ video.channel.name }}</a></span>
						<span class="video-views">{{ pluralize('view', video.views, true) }}</span>
						<span class="video-views">{{ video.created_at | humanReadableDate }}</span>
					</p>
				</div>
			</div>
		</article>
</template>

<script>
	import pluralize from 'pluralize';

	export default {
		props: ['video'],

		computed: {
			videoUrl() {
				return '/videos/' + this.video.uid;
			},

			imageAlt() {
				return this.video.title + ' thumbnail';
			},

			channelUrl() {
				return '/channels/' + this.video.channel.slug;
			}
		},

		filters: {
			humanReadableDate(timeStamp) {
		    	return moment(timeStamp).fromNow();
		    },
		},

		methods: {
			pluralize
		}
	}
</script>