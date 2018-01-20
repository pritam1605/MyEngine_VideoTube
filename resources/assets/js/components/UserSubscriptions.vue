<template>
    <div class="column">

        <div class="column is-two-thirds is-offset-2">
            <p class="title is-4 has-text-centered">My Subscribed Channels</p>
            <hr class="below-title">

            <div v-if="channels.length">
                <channel-component v-for="channel in channels"
                        :channel="channel"
                        :key="channel.id">
                </channel-component>
            </div>

            <div v-else>
                <div class="box">
                    <p class="is-medium">You have not subscribed any channels yet.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        data() {
            return {
                channels: [],
                error: null
            };
        },

        methods: {

            getChannelsPurchasedByUser() {
                axios.get('/' + this.$root.user.username + '/get-my-subscribed-channel-list')
                     .then(({data}) => {
                        this.channels = data.data.channels;
                     })
                     .catch(({response}) => {
                        this.error = response.data;
                     });
            }
        },

        mounted() {
            this.getChannelsPurchasedByUser();
        }
    }
</script>