<template>
	<div class="column is-4 is-offset-4">

		<!-- Notice for login errors -->
		<show-notice v-if="showMessage"
					 :message="message"
		             :messageTitle="messageTitle">
		</show-notice>

		<div class="box">
			<p class="title is-4 has-text-centered">Login</p>
			<hr class="below-title">

			<div class="field">
				<label class="label">Username/Email</label>
				<p class="control has-icons-left has-icons-right">
					<input class="input" :class="{ ' is-danger': formErrors.usernameOrEmail }" type="email" placeholder="jsmith" v-model="usernameOrEmail" @keydown="formErrors.usernameOrEmail = false">

					<span class="icon is-small is-left">
						<i class="fa fa-user-o"></i>
					</span>

					<p class="help is-danger" v-if="formErrors.usernameOrEmail">
						{{ formErrors.usernameOrEmail[0] }}
					</p>
				</p>
			</div>

			<div class="field">
				<label class="label">Password</label>
				<p class="control has-icons-left">
					<input class="input" :class="{ ' is-danger': formErrors.password }" type="password" placeholder="●●●●●●●" v-model="password" @keydown="formErrors.password = false">

					<span class="icon is-small is-left">
						<i class="fa fa-lock"></i>
					</span>

					<p class="help is-danger" v-if="formErrors.password">
						{{ formErrors.password[0] }}
					</p>
				</p>
			</div>

			<div class="field">
				<input class="is-checkbox is-small has-background-color is-primary" id="rememberMe" type="checkbox" v-model="rememberMe">
				<label for="rememberMe">Remember me</label>
			</div>

			<div class="field">
				<p class="control">
					<button class="button" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click="registerUser">
						<span class="icon is-small is-left"><i class="fa fa-sign-in"></i></span>&nbsp;&nbsp;&nbsp;Login
					</button>
				</p>
			</div>
    	</div>

        <p class="has-text-centered">
			I want to <a href="/register">Register</a> | I<a href="/password/forgot"> Forgot</a> my password
			<p class="has-text-centered">Try <a href="/passwordless-entry">Passwordless Entry</a></p>
        </p>

		<hr>
		<p class="title is-5 has-text-centered">or Login with</p>
		<footer class="card-footer">
			<!-- <a class="card-footer-item" :href="getSocialLoginUrl('github')">Github</a> -->
			<a class="card-footer-item" :href="getSocialLoginUrl('twitter')"><img src="https://s3-us-west-2.amazonaws.com/images.myengine.me/twitter_signin.png" alt="Twitter"></a>
			<a class="card-footer-item" :href="getSocialLoginUrl('facebook')"><img src="https://s3-us-west-2.amazonaws.com/images.myengine.me/facebook_signin.png" alt="Facebook"></a>
			<a class="card-footer-item" :href="getSocialLoginUrl('google')"><img src="https://s3-us-west-2.amazonaws.com/images.myengine.me/google_signin.png" alt="Google"></a>
		</footer>
	</div>
</template>

<script>
	import eventHub from './../eventhub.js';

	export default {

		data() {
			return {

				usernameOrEmail: null,
				email: null,
				channelName: null,
				password: null,
				confirmPassword: null,
				rememberMe: false,

				showIsLoading: false,

				formErrors: {},

				showMessage: false,
				messageTitle: '',
				message: '',
			};
		},

		methods: {
			registerUser() {
				this.showIsLoading = true;

				axios.post('/login', {
					usernameOrEmail: this.usernameOrEmail,
			    	password: this.password,
			    	rememberMe: this.rememberMe,
				})
				.then(({data}) => {
					this.showIsLoading = false;

					if ((data.data.status).toLowerCase() === 'failed') {
						this.showNotice('Notice', data.data.message);
						return;
					}

					this.showHomePage();

					this.usernameOrEmail = null;
					this.password = null;
					this.rememberMe = false;

				})
				.catch(({response}) => {
					this.showIsLoading = false;
					this.formErrors = response.data;
				});
			},

			showHomePage() {
				window.location = this.$root.appUrl;
			},

			showNotice(title, message) {
				this.showMessage = true;
				this.message = message;
				this.messageTitle = title;
			},

			closeNotice() {
				this.showMessage = false;
			},

			getSocialLoginUrl(service) {
				return '/login/' + service;
			}
		},

		mounted() {
			eventHub.$on('close-notice-box', this.closeNotice);
		}
	}
</script>