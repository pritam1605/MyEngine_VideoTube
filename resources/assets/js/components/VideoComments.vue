<template>
	<div class="box">
		<comment-box v-if="currentUser" :videoUid="videoUid" :currentUser="currentUser" :canCurrentUserComment="canCurrentUserComment" :channelType="channelType"></comment-box>
		<hr>

		<!-- Notice for comment without signin -->
		<show-notice v-if="showMessage"
					 :message="message"
		             :messageTitle="messageTitle">
		</show-notice>

		<nav class="panel" v-if="comments.length">
			<p class="panel-heading is-medium">
		    	{{ pluralize('Comment', comments.length, true) }}
		  	</p>
		</nav>

		<comment v-for="comment in comments" :comment="comment" :videoUid="videoUid" :key="comment.id" :canCurrentUserReply="canCurrentUserComment" :channelType="channelType"></comment>
	</div>
</template>

<script>
	import pluralize from 'pluralize';
	import eventHub from './../eventhub.js';

	export default {
		props: ['videoUid', 'canCurrentUserComment', 'channelType'],

		data() {
			return {
				error: null,
				message: '',
				comments: [],
				messageTitle: '',
				currentUser: null,
				showMessage: false,
			};
		},

		methods: {
			pluralize,

			loadComments() {
				axios.get('/videos/' + this.videoUid + '/comments')
					 .then(({data}) => {
					 	this.comments = data.comments.data;
					 	this.currentUser = data.current_user.data;
					 })
					 .catch(({response}) => {
					 	this.error = response.data;
					 });
			},

			showCommentMessage(data) {
				this.showMessage = true;
				this.message = data.message;
				this.messageTitle = data.title;
			},

			updateCommentList(comment) {
				this.comments.unshift(comment);
			},

			updateCommentListAfterDeletion(comments) {
				this.comments = comments;
			},

			closeNotice() {
				this.showMessage = false;
			}
		},

		mounted() {
			this.loadComments();
			eventHub.$on('show-comment-message', this.showCommentMessage);
			eventHub.$on('update-comment-list', this.updateCommentList);
			eventHub.$on('update-comment-list-after-deletion', this.updateCommentListAfterDeletion);
			eventHub.$on('close-notice-box', this.closeNotice);
		}
	}
</script>

