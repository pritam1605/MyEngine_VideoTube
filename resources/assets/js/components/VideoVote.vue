<template>
	<div v-if="up != null">
		<a class="vote" @click.prevent="vote('up')"><i class="fa fa-lg" :class="[ userVote === 'up' ? 'fa-thumbs-up' : 'fa-thumbs-o-up' ]"></i> {{ up }}</a>
		<a class="vote" @click.prevent="vote('down')"><i class="fa fa-lg" :class="[userVote === 'down' ? 'fa-thumbs-down' : 'fa-thumbs-o-down']"></i> {{ down }}</a>
	</div>
</template>

<script>
	import eventHub from './../eventhub.js';

	export default {
		props: ['videoUid', 'canBeAccessedByCurrentUser', 'channelType'],

		data() {
			return {
				up: null,
				down: null,
				userVote: null,
				error: null
			}
		},

		methods: {
			vote(type) {

				if (this.$root.user.authenticated && this.canBeAccessedByCurrentUser) {

					if (this.userVote == type) {
						this[type]--;
						this.userVote = null;
						this.sendVoteRequest(type);
						return;
					}

					if (this.userVote) {
						this[type == 'up' ? 'down' : 'up']--;
					}

					this[type]++;
					this.userVote = type;
					this.sendVoteRequest(type);
				} else if (!this.canBeAccessedByCurrentUser && this.channelType === 'paid') {
					let title = 'Notice!!';
					let message = 'Please buy the paid channel to cast your vote';

					eventHub.$emit('show-voting-message' , { 'title': title, 'message': message });
				} else if (!this.canBeAccessedByCurrentUser) {
					let title = 'Notice!!';
					let message = 'Sorry, you can not vote on a private video';

					eventHub.$emit('show-voting-message' , { 'title': title, 'message': message });
				} else {
					let title = 'Notice!!';
					let message = 'Sign-in to cast your vote';

					eventHub.$emit('show-voting-message' , { 'title': title, 'message': message });
				}
			},

			sendVoteRequest(type) {
				axios.post('/videos/' + this.videoUid + '/vote', {
						type: type
					 })
					 .then(({data}) => {
			     		this.updateVoteData(data.data);
			     	 })
					 .catch(({response}) => {
					 	this.error = response.data;
					 });
			},

			updateVoteData(data) {
				this.up = data.upVotes;
				this.down = data.downVotes;
				this.userVote = data.userVote;
			},

			getVoteData() {
				axios.get('/videos/' + this.videoUid + '/vote')
				     .then(({data}) => {
				     	this.updateVoteData(data.data);
				     })
				     .catch(({response}) => {
				     	this.error = response.data;
				     });
			}
		},

		mounted() {
			this.getVoteData();
		}
	}
</script>