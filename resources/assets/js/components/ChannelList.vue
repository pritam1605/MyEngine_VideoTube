<template>
    <div class="column">
        <div class="column is-two-thirds is-offset-2">
            <div v-if="channels.length">
                <channel-component v-for="channel in channels"
                        :channel="channel"
                        :key="channel.id">
                </channel-component>
            </div>

            <div v-else>
                <div class="box">
                    <p class="is-medium">No channels to show yet.</p>
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
                channels: [],
                error: null
            };
        },

        methods: {
            updateChannelList(channels) {
                this.channels = channels;
            },

            getChannels() {
                axios.get('/channels/user/' + this.$root.user.username + '/all-user-channels')
                     .then(({data}) => {
                        this.channels = data.data.channels;
                     })
                     .catch(({response}) => {
                        this.error = response.data;
                     });
            }
        },

        mounted() {
            this.getChannels();
            eventHub.$on('channellist-updated', this.updateChannelList);
        }
    }
</script>