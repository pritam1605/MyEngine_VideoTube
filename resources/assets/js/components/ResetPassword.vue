<template>
    <div class="column is-4 is-offset-4">

		<!-- Notice -->
		<show-notice v-if="showMessage"
					 :message="message"
		             :messageTitle="messageTitle"
		             :noticeType="noticeType">
		</show-notice>

		<div class="box">
			<p class="title is-4 has-text-centered">Reset Password</p>
			<hr class="below-title">

			<div class="field">
				<label class="label">Email</label>
				<p class="control has-icons-left">
					<input class="input" disabled type="email" v-model="email">

					<span class="icon is-small is-left">
						<i class="fa fa-envelope"></i>
					</span>
				</p>
			</div>

			<div class="field">
				<label class="label">New Password</label>
				<p class="control has-icons-left">
					<input class="input" :class="{ ' is-danger': formErrors.password }" type="password" v-model="password" @keydown="formErrors.password = false">

					<span class="icon is-small is-left">
						<i class="fa fa-lock"></i>
					</span>

					<p class="help is-danger" v-if="formErrors.password">
						{{ formErrors.password[0] }}
					</p>
				</p>
			</div>

			<div class="field">
				<label class="label">Confirm New Password</label>
				<p class="control has-icons-left">
					<input class="input" type="password" v-model="confirmPassword" @keydown="formErrors.password = false">

					<span class="icon is-small is-left">
						<i class="fa fa-lock"></i>
					</span>
				</p>
			</div>

			<div class="field">
				<p class="control">
					<button class="button" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click="changePassword">
						<span class="icon is-small is-left"><i class="fa fa-check-square-o"></i></span>&nbsp;&nbsp;Confirm
					</button>
				</p>
			</div>
    	</div>
	</div>
</template>

<script>
	import eventHub from './../eventhub.js';

	export default {
		props: ['email','token'],

		data() {
			return {
				password: null,
				confirmPassword: null,

				showIsLoading: false,

				formErrors: {},

				showMessage: false,
				messageTitle: '',
				message: '',
				noticeType: ''
			};
		},

		methods: {
			changePassword() {
				this.showIsLoading = true;

				axios.put('/password/forgot/reset/' + this.token , {
						email: this.email,
						password: this.password,
						password_confirmation: this.confirmPassword,
					})
					.then(({data}) => {
						this.showIsLoading = false;
						if ((data.data.status).toLowerCase() === 'success') {
							this.showNotice('Success', data.data.message, 'primary');

							this.password = null;
							this.confirmPassword = null;

							setTimeout(() => {
							    this.showloginPage();
							}, 5000);
						}
					})
					.catch(({response}) => {
						this.showIsLoading = false;
						this.formErrors = response.data;
						this.formErrors.currentPassword = response.data.currentPassword[0];
					});

			},

			showloginPage() {
				window.location = this.$root.appUrl + '/login'
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