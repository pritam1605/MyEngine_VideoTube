<template>
	<video 	id="video"
	       	class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9"
	       	controls preload="auto"
	       	data-setup='{"fluid": true, "preload": "auto"}'
			:poster="thumbnailUrl"
			oncontextmenu="return false;"
	       >

		<source type="video/mp4" :src="videoUrl"></source>

	</video>
</template>

<script>
	export default {
		props: {
			videoUid: null,
			videoUrl: null,
			thumbnailUrl: null,
			// playlist: null
		},

		data() {
			return {
				player:null,
				duration: null
			};
		},

		methods: {
			hasHitViewQuota() {
				if (!this.duration) {
					return null;
				}

				// We will register a view only after at least 30% of the video is watched
				return Math.round(this.player.currentTime()) === Math.round(this.duration * 0.3);
			},

			logView() {
				axios.post('/videos/' + this.videoUid + '/views');
			},

			loadVideoPlayer() {
				this.player = videojs('video');

				this.player.on('loadedmetadata', () => {
					this.duration = Math.round(this.player.duration());
				});

				setInterval(() => {
					if (this.hasHitViewQuota()) {
						this.logView();
					}
				}, 1000);

				/*//Video Playlist
				var i;
				let list = [];
				list.push({
						sources: [{
							src: this.videoUrl,
							type: 'video/mp4',
						}],
						poster: this.thumbnailUrl
				});

				for (i = 0; i < this.playlist.length; i++) {

					let playlistItem = {
						sources: [{
							src: this.playlist[i].video_url,
							type: 'video/mp4',
						}],
						poster: this.playlist[i].thumbnail
					}

					list.push(playlistItem);

				}

				// Play through the playlist automatically.

				this.player = videojs('video');
				this.player.playlist(list);
				this.player.playlist.autoadvance(5);

				Note: this code goes before the this.player.on()
				*/
			}
		},

		mounted() {
			this.loadVideoPlayer();
		}

	}
</script>