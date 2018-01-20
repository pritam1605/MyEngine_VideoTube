<template>
	<div class="column" v-if="user">
		<div class="column is-4 is-offset-4">
			<div class="box">

				<div class="div-image">
					<div class="field">
				    	<figure class="avatar-profile">
	              			<img class="center-of-div" :src="user.profile_image_url">
	            		</figure>
				    </div>
				</div>

				<div class="upload-button-user-profile">
					<div class="field center-of-div">
						<div class="file is-small" :class="[formErrors.profileImage ? ' is-danger' : '', ' is-primary']">
							<label class="file-label">
								<input type="file" name="profileImage" id="profileImage" class="file-input" @change.prevent="getFileName" ref="inputFile">
								<span class="file-cta">
									<span class="file-icon">
										<i class="fa fa-upload"></i>
									</span>
									<span class="file-label">{{ fileNameLabel }}</span>
								</span>
							</label>
						</div>
					</div>
				</div>

				<div class="profile-image-validation">
					<p class="help is-danger center-of-div" v-if="formErrors.profileImage">
						{{ formErrors.profileImage }}
					</p>
				</div>

				<div class="field">
					<label class="label">Username</label>
				  	<p class="control">
				    	<input class="input" type="text" v-model="username" disabled="disabled">
				  	</p>
				</div>

				<div class="field">
					<label class="label">Email</label>
				  	<p class="control">
				    	<input class="input" type="text" v-model="email" disabled="disabled">
				  	</p>
				</div>

				<div class="field">
					<label class="label">First Name</label>
				  	<p class="control">
				    	<input class="input" :class="{ ' is-danger': formErrors.firstName }" type="text" placeholder="John" v-model="firstName" @keydown="formErrors.firstName = false">

						<p class="help is-danger" v-if="formErrors.firstName">
							{{ formErrors.firstName[0] }}
						</p>
				  	</p>
				</div>

				<div class="field">
					<label class="label">Last Name</label>
				  	<p class="control">
				    	<input class="input" :class="{ ' is-danger': formErrors.lastName }" type="text" placeholder="Smith" v-model="lastName" @keydown="formErrors.lastName = false">

						<p class="help is-danger" v-if="formErrors.lastName">
							{{ formErrors.lastName[0] }}
						</p>
				  	</p>
				</div>

				<div class="field">
	    	        <div class="columns">
	    	            <div class="column is-three-quarters">
	    	                <p class="control">
								<button class="button" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click.prevent="updateUserProfile">Update</button>
	    	                </p>
	    	            </div>
	    	            <div class="column is-one-quarter">
	    	                <span class="is-right">
                                <div v-if="saveChanges == 'saving'">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>

                                <div v-else>
                                    <p>{{ saveChanges }}</p>
                                </div>
                            </span>
	    	            </div>
	    	        </div>
	    	    </div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {

		data() {
			return {

				showEdit: false,
				showIsLoading: false,

				user: null,

				// edit form variables
				firstName: null,
                lastName: null,
                username: null,
                email: null,
                profileImage: null,

				// Form Validations
                formErrors: {
                    firstName: false,
                    lastName: false,
                    profileImage: false,
                },

                saveChanges: null,

                fileNameLabel: 'Choose a file...'
			};
		},

		methods: {

			loadUserData() {
				axios.get('/' + this.$root.user.username + '/get-profile')
				     .then(({data}) => {
						this.user = data.data.user;
						this.firstName = this.user.first_name;
						this.lastName = this.user.last_name;
						this.username = this.user.username;
						this.email = this.user.email;
						this.profileImage = null;
				     })
				     .catch(({response}) => {
				     	this.formErrors = response.data;
				     });
			},

			updateUserProfile() {
				this.saveChanges = 'saving';
				this.showIsLoading = true;
				this.formErrors.profileImage = false;

				this.profileImage = document.getElementById('profileImage').files[0];

				var form = new FormData();
                form.append('firstName', this.firstName);
                form.append('lastName', this.lastName);

                if (this.profileImage) {
                	form.append('profileImage', this.profileImage);
                }

				axios.post('/' + this.$root.user.username + '/profile', form)
				     .then(({data}) => {
						this.saveChanges = 'Changes saved';
						this.showIsLoading = false;
						this.showEdit = false;
						this.$refs.inputFile.value = null;
						this.fileNameLabel = 'Choose a file...';
					})
				     .catch(({response}) => {
						this.showIsLoading = false;
						this.saveChanges = 'Faild to save changes'
						this.formErrors = response.data;
						this.formErrors.profileImage = response.data.profileImage[0];
					});
			},

			getFileName() {
				this.formErrors.profileImage = false;
				this.fileNameLabel = null;

				var file = document.getElementById("profileImage").files[0];
				var extensions = ['gif', 'jpeg', 'jpg', 'png', 'bmp', 'svg'];

				if (file) {
					var fileExtension = file.name.split('.').pop().toLowerCase();
					var fileSize = (file.size/(1024*1024)).toFixed(2);
				}

			    if(file && file.name) {
					if (file.name.length > 20) {
						this.fileNameLabel = file.name.substring(0, 20) + '....' + fileExtension;
					} else {
						this.fileNameLabel = file.name;
					}
				} else {
					this.fileNameLabel = 'Choose a file...';
				}

				if (file && extensions.indexOf(fileExtension) === -1) {
					this.formErrors.profileImage = 'Only jpeg, jpg, png, bmp, gif and svg formats are supported.';
					return;
				}

				if (file && fileSize > 4) {
				    this.formErrors.profileImage = 'The user profile image may not be greater than 4 Megabyte.';
				    return;
				}
			}
		},

		mounted() {
			this.loadUserData();
		}
	}
</script>