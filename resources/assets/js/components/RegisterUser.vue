<template>
	<div class="column is-4 is-offset-4">

			<!-- Notice -->
			<show-notice v-if="showMessage"
						 :message="message"
			             :messageTitle="messageTitle"
			             :noticeType="noticeType">
			</show-notice>

			<div class="box">
				<p class="title is-4 has-text-centered">Register an Account</p>
				<hr class="below-title">

			<div class="field">
				<label class="label">Username</label>
				<p class="control has-icons-left">
					<input class="input" :class="{ ' is-danger': formErrors.username }" type="email" placeholder="jsmith" v-model="username" @keydown="formErrors.username = false">

					<span class="icon is-small is-left">
						<i class="fa fa-user-o"></i>
					</span>

					<p class="help is-danger" v-if="formErrors.username">
						{{ formErrors.username[0] }}
					</p>
				</p>
			</div>

			<div class="field">
				<label class="label">First Name</label>
				<p class="control has-icons-left">
					<input class="input" :class="{ ' is-danger': formErrors.firstName }"type="text" placeholder="John" v-model="firstName" @keydown="formErrors.firstName = false">
					<span class="icon is-small is-left">
						<i class="fa fa-user"></i>
					</span>

					<p class="help is-danger" v-if="formErrors.firstName">
						{{ formErrors.firstName[0] }}
					</p>
				</p>
			</div>

			<div class="field">
				<label class="label">Last Name</label>
				<p class="control has-icons-left">
					<input class="input" :class="{ ' is-danger': formErrors.lastName }"type="text" placeholder="Smith" v-model="lastName" @keydown="formErrors.lastName = false">
					<span class="icon is-small is-left">
						<i class="fa fa-user"></i>
					</span>

					<p class="help is-danger" v-if="formErrors.lastName">
						{{ formErrors.lastName[0] }}
					</p>
				</p>
			</div>

			<div class="field">
				<label class="label">Email</label>
				<p class="control has-icons-left">
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
	            <label class="label">Default Channel Name</label>
	            <p class="control">
		            <input class="input" :class="{ ' is-danger': formErrors.channelName }" type="text" placeholder="Untitled" v-model="channelName" @keydown="formErrors.channelName = false">

		            <p class="help is-danger" v-if="formErrors.channelName">
		                    {{ formErrors.channelName[0] }}
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
				<label class="label">Confirm Password</label>
				<p class="control has-icons-left">
					<input class="input" type="password" placeholder="●●●●●●●" v-model="confirmPassword" @keydown="formErrors.password = false">

					<span class="icon is-small is-left">
						<i class="fa fa-lock"></i>
					</span>
				</p>
			</div>

			<div class="field">
				<p class="control">
					<button class="button" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click="registerUser">
						<span class="icon is-small is-left"><i class="fa fa-user-plus"></i></span>&nbsp;&nbsp;&nbsp;Register
					</button>
				</p>
			</div>
        </div>

        <p class="has-text-centered">
			Already a member? <a href="/login">Login</a>
        </p>
	</div>
</template>

<script>
	import eventHub from './../eventhub.js';

	export default {

		data() {
			return {

				username: null,
				firstName: null,
				lastName: null,
				email: null,
				password: null,
				confirmPassword: null,
				channelName: null,

				showIsLoading: false,

				formErrors: {},

				showMessage: false,
				messageTitle: '',
				message: '',
				noticeType: ''
			};
		},

		methods: {
			registerUser() {
				this.showIsLoading = true;

				axios.post('/register', {
					username: this.username,
					firstName: this.firstName,
					lastName: this.lastName,
			    	email: this.email,
			    	password: this.password,
			    	password_confirmation: this.confirmPassword,
			    	channelName: this.channelName
				})
				.then(({data}) => {
					this.showIsLoading = false;
					this.username = null;
					this.firstName = null;
					this.lastName = null;
					this.email = null;
					this.password = null;
					this.confirmPassword = null;
					this.channelName = null;

					if ((data.data.status).toLowerCase() === 'success') {
						this.showNotice('Success', data.data.message, 'primary');

						setTimeout(() => {
						    this.showloginPage();
						}, 5000);
					}
				})
				.catch(({response}) => {
					this.showIsLoading = false;
					this.formErrors = response.data;
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