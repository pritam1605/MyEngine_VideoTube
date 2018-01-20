<template>
    <div class="column">
        <div class="column is-two-thirds is-offset-2">
            <div v-if="videos.length">
                <video-component v-for="video in videos"
                                 :video="video"
                                 :key="video.id">
                </video-component>
            </div>

            <div v-else>
                <div class="box">
                    <p class="is-medium">No videos to show yet.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import eventHub from './../eventhub.js';

    export default {

        data() {
            return {
                videos: [],
                error: null
            };
        },

        methods: {
            updateVideoList(videos) {
                this.videos = videos;
            },

            getVideos() {
                axios.get('/videos/user/' + this.$root.user.username + '/all-user-videos')
                     .then(({data}) => {
                        this.videos = data.data.videos;
                     })
                     .catch(({response}) => {
                        this.error = response.data;
                     });
            }
        },

        mounted() {
            this.getVideos();
            eventHub.$on('videolist-updated', this.updateVideoList);
        }
    }
</script>
