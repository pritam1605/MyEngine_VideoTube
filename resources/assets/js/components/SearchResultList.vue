<template>
	<div class="column">
	    <div class="column is-two-thirds is-offset-2">

	    	<div v-if="channels.length || videos.length">
	    		<strong><p class="is-medium">Search result for {{ queryString }}</p></strong>
	    	</div>

	      	<div v-if="channels.length">
				<nav class="panel">
					<p class="panel-heading is-medium">
				    	{{ pluralize('Channel', channels.length, true) }}
				  	</p>
				</nav>

				<channel-component v-for="channel in channels"
                        :channel="channel"
                        :key="channel.id">
        		</channel-component>
	        </div>

	        <br>

	        <div v-if="videos.length">
				<nav class="panel">
					<p class="panel-heading is-medium">
				    	{{ pluralize('Video', videos.length, true) }}
				  	</p>
				</nav>

				<video-component v-for="video in videos"
						:video="video"
						:page="queryString"
                        :key="video.id">
            	</video-component>
	        </div>

            <div v-if="!channels.length && !videos.length" class="box">
                <p class="is-medium">Sorry, no result found for {{ queryString }}</p>
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
				videos: [],
				channels: [],
				queryString: null,
				error: null
			};
		},

		methods: {
			pluralize,

			getParameterByName(name, url) {
			    if (!url) url = window.location.href;
			    name = name.replace(/[\[\]]/g, "\\$&");
			    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			        results = regex.exec(url);
			    if (!results) return null;
			    if (!results[2]) return '';
			    return decodeURIComponent(results[2].replace(/\+/g, " "));
			},

			updateVideoList(videos) {
                this.videos = videos;
            },

            updateChannelList(channels) {
                this.channels = channels;
            },

            getSearchResult() {
            	this.queryString = this.getParameterByName('q');

				axios.get('/search-list?q=' + this.queryString)
            	     .then(({data}) => {
            	     	this.videos = data.data.videos;
            	     	this.channels = data.data.channels;
            	     })
            		.catch(({response}) => {
            			this.error = response.data;
            		});
            }
		},


		mounted() {
			this.getSearchResult();
			eventHub.$on('videolist-updated', this.updateVideoList);
			eventHub.$on('channellist-updated', this.updateChannelList);
		}
	}
</script>