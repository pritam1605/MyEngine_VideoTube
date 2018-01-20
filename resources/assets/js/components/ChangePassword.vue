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
				<label class="label">Current Password</label>
				<p class="control has-icons-left">
					<input class="input" :class="{ ' is-danger': formErrors.currentPassword }" type="password" v-model="currentPassword" @keydown="formErrors.currentPassword = false" @blur="checkCurrentPassword">

					<span class="icon is-small is-left">
						<i class="fa fa-lock"></i>
					</span>

					<p class="help is-danger" v-if="formErrors.currentPassword">
						{{ formErrors.currentPassword }}
					</p>
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

		data() {
			return {
				currentPassword: null,
				password: null,
				confirmPassword: null,

				showIsLoading: false,

				formErrors: {},

				currentPasswordMatched: null,

				showMessage: false,
				messageTitle: '',
				message: '',
				noticeType: ''
			};
		},

		methods: {
			changePassword() {
				this.showIsLoading = true;

				axios.put('/password/change', {
						currentPassword: this.currentPassword,
						password: this.password,
						password_confirmation: this.confirmPassword,
					})
					.then(({data}) => {
						this.showIsLoading = false;
						if ((data.data.status).toLowerCase() === 'success') {
							this.showNotice('Success', data.data.message, 'primary');

							this.currentPassword = null;
							this.password = null;
							this.confirmPassword = null;
						}
					})
					.catch(({response}) => {
						this.showIsLoading = false;
						this.formErrors = response.data;
						this.formErrors.currentPassword = response.data.currentPassword[0];
					});

			},

			checkCurrentPassword() {
				if (this.currentPassword) {
					axios.post('/password/check', {
						currentPassword: this.currentPassword
					})
					.then(({data}) => {
						if (data.data.status === 'failed') {
							this.formErrors.currentPassword = data.data.message;
						}
					})
					.catch(({response}) => {
						this.error = response.data;
					});
				}
			},

			showNotice(title, message, noticeType) {
				this.showMessage = true;
				this.message = message;
				this.messageTitle = title;
				this.noticeType = noticeType;
			},
		},

		mounted() {
			eventHub.$on('close-notice-box', this.closeNotice);
		}
	}
</script>