<template>
	<div class="box">
		<article class="media">
		  	<figure class="media-left">
		    	<p class="image is-64x64">
		      		<img :src="comment.user.profile_image_url">
		    	</p>
		  	</figure>
			<div class="media-content">
				<div class="content">
					<p>
						<strong><a :href="userDashboard(comment.user.username)">{{ comment.user.full_name_or_username }}</a></strong>
						<br>
						{{ comment.body }}
						<br>
						<small>{{ comment.created_at_human }}</small>
					</p>
				</div>

				<nav class="level is-mobile">
	        		<div class="level-left">
	          			<a class="level-item" @click="showReplyBox = !showReplyBox">
	            			<span class="icon is-small"><i class="fa fa-comment-o"></i></span>
	          			</a>

  						<a v-if="$root.user.id === comment.user_id" class="level-item" @click="deleteComment(comment.id)">
  			  				<span class="icon is-small"><i class="fa fa-trash-o"></i></span>
  						</a>
	          		</div>
	          	</nav>

				<transition name="fade">
					<article class="media" v-if="showReplyBox">
					  	<div class="media-content">
					    	<div class="field">
					      		<p class="control">
					        		<textarea class="textarea is-small" :class="{ ' is-danger': replyBodyError }" placeholder="Add a comment..." v-model="replyBody" @keydown="replyBodyError = false"></textarea>
					      		</p>

				        		<p class="help is-danger" v-if="replyBodyError">
				    	            {{ replyBodyError }}
				    	        </p>
					    	</div>

					    	<nav class="level">
					      		<div class="level-left">
							        <div class="level-item">
							          <button class="button is-small" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click.prevent="postReply(comment.id)">Reply</button>
							        </div>
					      		</div>
					    	</nav>
					  	</div>
					</article>
				</transition>

				<!-- Notice for reply without signin -->
				<br>
				<show-notice v-if="showMessage"
							 :message="message"
				             :messageTitle="messageTitle">
				</show-notice>

				<!-- Replies -->
    			<article class="media" v-if="comment.replies" v-for="reply in comment.replies.data">
      				<figure class="media-left">
        				<p class="image is-48x48">
          					<img :src="reply.user.profile_image_url">
        				</p>
      				</figure>
      				<div class="media-content">
        				<div class="content">
          					<p>
            					<strong><a :href="userDashboard(reply.user.username)">{{ reply.user.full_name_or_username }}</a></strong>
	            				<br>
	            				{{ reply.body }}
	            				<br>
	            				<small>{{ reply.created_at_human }}</small>
          					</p>
        				</div>

        				<nav class="level is-mobile">
			        		<div class="level-left">
		  						<a v-if="$root.user.id === reply.user_id" class="level-item" @click="deleteComment(reply.id)">
		  			  				<span class="icon is-small"><i class="fa fa-trash-o"></i></span>
		  						</a>
			          		</div>
			          	</nav>
      				</div>
    			</article>
  			</div>
		</article>
	</div>
</template>

<script>
	import eventHub from './../eventhub.js';

	export default {
		props: ['videoUid', 'comment', 'canCurrentUserReply', 'channelType'],

		data() {
			return {
				error: null,
				message: '',
				replyBody: null,
				messageTitle: '',
				showMessage: false,
				showReplyBox: false,
				showIsLoading: false,

				// form validation
				replyBodyError: false
			};
		},

		methods: {
			userDashboard(username) {
				return '/' + username;
			},

			postReply(commentId) {
				if (this.$root.user.authenticated && this.canCurrentUserReply) {

					// won't send a backend request
					if (!this.replyBody || !this.replyBody.trim()) {
						this.replyBodyError = 'Cannot post a blank comment.';
						return;
					}

					this.showIsLoading = true;

					axios.post('/videos/' + this.videoUid + '/comments', {
						'body': this.replyBody,
						'parent_id': commentId
					})
					.then(({data}) => {
						this.replyBody = null;
						this.showReplyBox = false;
						this.showIsLoading = false;
						this.comment.replies.data.unshift(data.data);
					})
					.catch(({response}) => {
						this.showIsLoading = false;
						this.replyBodyError = response.data.body[0];
					})

				} else if (!this.canCurrentUserReply && this.channelType === 'paid') {
					this.replyBody = null;
					let title = 'Notice!!';
					let message = 'You must buy this paid channel to comment on the video';

					this.showCommentMessage(title, message)
				} else if (!this.canCurrentUserReply) {
					this.replyBody = null;
					let title = 'Notice!!';
					let message = 'Sorry, can not reply to a comment on a private video';

					this.showCommentMessage(title, message)
				} else {
					this.replyBody = null;
					let title = 'Notice!!';
					let message = 'Sign-in to comment on this video';

					this.showCommentMessage(title, message)
				}
			},

			deleteComment(commentId) {
				if (!confirm('Are you sure?')) {
					return;
				}

				axios.delete('/videos/' + this.videoUid + '/comments/' + commentId)
				     .then(({data}) => {
				     	eventHub.$emit('update-comment-list-after-deletion', data.comments.data)
				     })
				     .catch(({response}) => {
				     	this.error = response.data;
				     });

			},

			showCommentMessage(title, message) {
				this.showMessage = true;
				this.message = message;
				this.messageTitle = title;
			},

			closeNotice() {
				this.showMessage = false;
			}
		},

		mounted() {
			eventHub.$on('close-notice-box', this.closeNotice);
		}
	}
</script>