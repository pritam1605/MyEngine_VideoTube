<template>
	<div class="column">
		<div class="columns">
			<div class="column is-8">

				<div v-if="video.processed && video.can_be_accessed_by_current_user">
					<div class="image">
						<!-- video player component -->
						<video-player :videoUid="video.uid" :videoUrl="video.video_url" :thumbnailUrl="video.thumbnail" :playlist=otherVideos></video-player>
					</div>
				</div>

				<div v-if="!video.processed" class="video-placeholder">
					<div class="video-placeholder-header">
						<p>Video is sill processing, please wait or come back later.</p>
					</div>
				</div>

				<div v-else-if="!video.can_be_accessed_by_current_user && video.type === 'paid'" class="video-placeholder">
					<div class="video-placeholder-header">
						<p>Sorry, this video belongs to a paid channel. Please buy the channel to access this video.</p>
					</div>
				</div>

				<div v-else-if="!video.can_be_accessed_by_current_user" class="video-placeholder">
					<div class="video-placeholder-header">
						<p>Sorry, this is a private video.</p>
					</div>
				</div>
				<br>

				<div class="box video-meta">
					<div class="video-title">{{ video.title }}</div>
					<br>

					<!-- Channel Data -->
	                <article class="media">
						<figure class="media-left">
							<p class="image is-64x64">
								<img :src="video.channel.imageUrl" :alt="imageAlt">
							</p>
						</figure>
					  	<div class="media-content">
					  		<div class="columns">
	            				<div class="column is-three-quarters">
							    	<div class="content">
										<p><strong><a :href="channelUrl">{{ video.channel.name }}</a></strong></p>

										<!-- Subscription component -->
										<subscribe-button v-if="video.channel.slug" :channelSlug="video.channel.slug"></subscribe-button>
							    	</div>
							    </div>
							    <div class="column is-one-quarter">
			    	                <div class="content">
		                                <p class="title is-6" v-if="video.views != undefined">{{ pluralize('view', video.views, true) }}</p>
		                            </div>

		                            <!-- Vote component -->
									<div v-if="video.uid && video.allows_vote">
										<video-vote :videoUid="video.uid" :canBeAccessedByCurrentUser="video.can_be_accessed_by_current_user" :channelType="video.channel.type"></video-vote>
									</div>
			    	            </div>
							</div>

							<!-- Notice for voting without signin -->
							<show-notice v-if="showMessage"
										 :message="message"
							             :messageTitle="messageTitle">
							</show-notice>
					  	</div>
					</article>
				</div>

				<div class="box video-description">
					<p><strong>Uploaded on {{ video.created_at | humanReadableDate }}</strong></p>
					<div v-if="video.description">
						<p v-if="!showMore">{{ videoDescription }}</p>
						<transition name="fade">
							<p v-if="showMore">{{ video.description }}</p>
						</transition>
					</div>
					<p v-else>No description available</p>
					<hr>
					<p class="has-text-centered has-text-muted video-description-more" @click="showMoreContent">Show More</p>
				</div>

				<!-- Comments component -->
				<div v-if="video.uid && video.allows_comment">
					<video-comments :videoUid="video.uid" :canCurrentUserComment="video.can_be_accessed_by_current_user" :channelType="video.channel.type"></video-comments>
				</div>
			</div>

			<!-- Playlist -->
			<div class="column is-4">
				<div class="box related-list">
					<p class="autoplay">
						<span class="autoplay-title">Other videos</span>
					</p>

					<playlist v-for="video in otherVideos" :video="video" :key="video.id"></playlist>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import pluralize from 'pluralize';
	import eventHub from './../eventhub.js';

	export default {

		data() {
			return {
				video: {
					channel: ''
				},

				otherVideos: [],

				error: null,
				showMessage: false,
				messageTitle: '',
				message: '',

				showMore: false,
			};
		},

		filters: {
			humanReadableDate(timeStamp) {
		    	return moment(timeStamp).format('ll');
		    },
		},

		computed: {
			videoUrl() {
				return '/videos/' + this.video.uid;
			},

			imageAlt() {
				return this.video.channel.name + ' image';
			},

			channelUrl() {
				return '/channels/'+ this.video.channel.slug;
			},

			videoDescription() {
				let description = this.video.description

				if (description.length > 65) {
					return description.substring(0, 65) + '....';
				}

				return description;
			}
		},

		methods: {
			pluralize,

			showVotingMessage(data) {
				this.showMessage = true;
				this.message = data.message;
				this.messageTitle = data.title;
			},

			getVideoData() {
				let uid = window.location.pathname.split('/')[2];

				axios.get('/videos/' + uid + '/show')
				     .then(({data}) => {
				     	this.video = data.data.video;
				     	this.otherVideos = data.data.other_videos;
				     })
				     .catch(({response}) => {
				     	this.error = response.data;
				     });
			},

			closeNotice() {
				this.showMessage = false;
			},

			showMoreContent() {
				if (this.videoDescription !== this.video.description) {
					this.showMore = !this.showMore;
				}
			}
		},

		mounted() {
			this.getVideoData();
			eventHub.$on('show-voting-message', this.showVotingMessage);
			eventHub.$on('close-notice-box', this.closeNotice);
		}
	}
</script>