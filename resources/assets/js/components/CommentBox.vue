<template>
	<article class="media">
	  	<figure class="media-left">
	    	<p class="image is-64x64">
	      		<img :src="currentUser.user_profile_imageUrl">
	    	</p>
	  	</figure>

	  	<div class="media-content">
	    	<div class="field">
	      		<p class="control">
	        		<textarea class="textarea is-small" :class="{ ' is-danger': bodyError }" placeholder="Add a comment..." v-model="body" @keyup.enter="postCommentOnEnter" @keydown="bodyError = false"></textarea>
	      		</p>

        		<p class="help is-danger" v-if="bodyError">
    	            {{ bodyError }}
    	        </p>
	    	</div>

	    	<nav class="level">
	      		<div class="level-left">
			        <div class="level-item">
			          <button class="button is-small" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click.prevent="postComment">Post comment</button>
			        </div>
	      		</div>

	      		<div class="level-right">
	        		<div class="level-item">
	          			<label class="checkbox">
	            			<input type="checkbox" v-model="checked"> Press enter to submit
	          			</label>
	        		</div>
	      		</div>
	    	</nav>
	  	</div>
	</article>
</template>

<script>
	import eventHub from './../eventhub.js';

	export default {
		props: ['videoUid', 'currentUser', 'canCurrentUserComment', 'channelType'],

		data() {
			return {
				body: null,
				error: null,
				checked: true,
				comment: null,
				showIsLoading: false,

				// Form Validations
                bodyError: false
			};
		},

		methods: {
			postCommentOnEnter() {
				if (this.checked && this.body.trim()) {
					this.postComment();
				}
			},

			postComment() {
				if (this.$root.user.authenticated && this.canCurrentUserComment) {

					// won't send a backend request
					if (!this.body || !this.body.trim()) {
						this.bodyError = 'Cannot post a blank comment.';
						return;
					}

					this.showIsLoading = true;

					axios.post('/videos/' + this.videoUid + '/comments', {
						'body': this.body
					})
					.then(({data}) => {
						this.body = null;
						this.showIsLoading = false;
						eventHub.$emit('update-comment-list' , data.data);
					})
					.catch(({response}) => {
						this.showIsLoading = false;
						this.bodyError = response.data.body[0];
					})

				} else if (!this.canCurrentUserComment && this.channelType === 'paid') {
					let title = 'Notice!!';
					let message = 'You must buy this paid channel to comment on the video';

					eventHub.$emit('show-comment-message' , { 'title': title, 'message': message });
				} else if (!this.canCurrentUserComment)  {
					this.body = null;
					let title = 'Notice!!';
					let message = 'Sorry, can not comment on a private video';

					eventHub.$emit('show-comment-message' , { 'title': title, 'message': message });
				} else {
					this.body = null;
					let title = 'Notice!!';
					let message = 'Sign-in to comment on this video';

					eventHub.$emit('show-comment-message' , { 'title': title, 'message': message });
				}
			}
		}
	}
</script>