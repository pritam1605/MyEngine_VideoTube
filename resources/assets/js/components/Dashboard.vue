<template>

	<div class="container section">
		<div class="columns" v-if="columns" v-for="(n, i) in columns">
			<dashboard-video-list v-if="videos.length" v-for="video in videoChuncks[i]" :video="video" :key="video.id"></dashboard-video-list>
	  	</div>

	  	<div v-else>
	  		<div class="box">
	  			Sorry, no videos to watch yet.
	  		</div>
	  	</div>
	</div>
</template>

<script>
	export default {

		data() {
			return {
				error: null,
				videos: [],
				videoChuncks: [],
				columns: null,
			};
		},

		methods: {

			loadDashboardVideos() {
				axios.get('/homepage-videos')
				     .then(({data}) => {
				     	this.videos = data.data.videos;
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
			}
		},

		mounted() {
			this.loadDashboardVideos();
		}
	}
</script>