<template>
   	<div class="column is-6 is-offset-3">

		<!-- Notice -->
		<show-notice v-if="showMessage"
					 :message="message"
		             :messageTitle="messageTitle"
		             :noticeType="noticeType">
		</show-notice>

		<div class="box">
			<div class="field">
				<label class="label">Email</label>
				<p class="control has-icons-left has-icons-right">
					<input class="input" :class="{ ' is-danger': formErrors.email }" type="email" placeholder="johnsmith@example.com" v-model="email" @keydown="formErrors.email = false">

					<span class="icon is-small is-left">
						<i class="fa fa-envelope"></i>
					</span>

					<p class="help is-danger" v-if="formErrors.email">
						{{ formErrors.email[0] }}
					</p>
				</p>
			</div>

			<div class="field">
				<div class="columns">
					<div class="column is-8">
						<p class="control">
							<button class="button is-small" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click.prevent="sendResetPasswordLink">
								<span class="icon is-small is-left"><i class="fa fa-envelope"></i></span>&nbsp;&nbsp;&nbsp;Send reset password link
							</button>
						</p>
					</div>
					<div class="column is-4">
						<p><a href="/login">Back to login &rarr;</a></p>
					</div>
				</div>
			</div>
    	</div>

        <p class="has-text-centered">
			Not a member yet? <a href="/register">Register</a>
        </p>
	</div>
</template>

<script>
	import eventHub from './../eventhub.js';

	export default {

		data() {
			return {
				email: null,

				showIsLoading: false,

				formErrors: {},

				showMessage: false,
				messageTitle: '',
				message: '',
				noticeType: ''
			};
		},

		methods: {
			sendResetPasswordLink() {
				this.showIsLoading = true;

				axios.post('/password/forgot', {
						email: this.email
					})
					.then(({data}) => {
						this.showIsLoading = false;

						if ((data.data.status).toLowerCase() === 'success') {
							this.showNotice('Success', data.data.message, 'primary');
							this.email = null;
						}
					})
					.catch(({response}) => {
						this.showIsLoading = false;
						this.formErrors = response.data;

						if (!formErrors.email) {
							this.showNotice('Notice', 'Sorry, a problem occured while sending reset password link', 'danger');
							this.email = null;
						}
					});

			},

			showNotice(title, message, noticeType) {
				this.showMessage = true;
				this.message = message;
				this.messageTitle = title;
				this.noticeType = noticeType;
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