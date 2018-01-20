<template>
	<div class="column">
		<div class="column is-half is-offset-3">
			<div class="box">
				<p class="title is-4 has-text-centered">My new channel</p>
				<hr class="below-title">

				<div class="field">
					<label class="label">Name</label>
				  	<p class="control">
				    	<input class="input" :class="{ ' is-danger': formErrors.name }" type="text" placeholder="Untitled" v-model="name" @keydown="formErrors.name = false">

						<p class="help is-danger" v-if="formErrors.name">
							{{ formErrors.name[0] }}
						</p>
				  	</p>
				</div>

				<div class="field">
				  	<label class="label">Channel Link</label>
				  	<div class="field has-addons">
	  		  			<p class="control">
	  		    			<a class="button is-static">
	  		     				<strong>{{ channelUrl }}</strong>
	  		    			</a>
	  		  			</p>
	  		  			<p class="control is-expanded">
	  		    			<input class="input" :class="{ ' is-danger': formErrors.slug }" type="text" placeholder="untitled-slug" v-model="slug" @keydown="formErrors.slug = false">
	  		  			</p>
	  				</div>

	  				<p class="help is-danger" v-if="formErrors.slug">
						{{ formErrors.slug[0] }}
	    	        </p>
				</div>

				<div class="field">
	    	        <label class="label">Description</label>
	    	        <p class="control">
	    	            <textarea class="textarea" :class="{ ' is-danger': formErrors.description }" placeholder="Channel description goes here..." v-model="description" @keydown="formErrors.description = false"></textarea>
	    	        </p>

	    	        <p class="help is-danger" v-if="formErrors.description">
	    	            {{ formErrors.description[0] }}
	    	        </p>
	    	    </div>

	    	    <div class="field">
	    	    	<div class="columns">
						<div class="column is-4">
			    	        <label class="label">Visibility</label>
			    	        <p class="control">
			    	            <span class="select" :class="{ 'is-danger': formErrors.visibility }">
			    	                <select v-model="visibility" @change="formErrors.visibility = false">
			    	                    <option v-for="option in visibilityOptions"  :value="option">{{ option |  capitalize }}</option>
			    	                </select>
			    	            </span>
			    	        </p>

			    	        <p class="help is-danger" v-if="formErrors.visibility">
			    	            {{ formErrors.visibility[0] }}
			    	        </p>
			    	    </div>

						<div class="column is-4">
			    	    	<label class="label">Type</label>
			    	    	<p class="control">
			    	    	    <span class="select" :class="{ 'is-danger': formErrors.type }">
			    	    	        <select v-model="type" @change="formErrors.type = false">
			    	    	            <option v-for="option in typeOptions"  :value="option">{{ option |  capitalize }}</option>
			    	    	        </select>
			    	    	    </span>
			    	    	</p>

			    	    	<p class="help is-danger" v-if="formErrors.type">
			    	    	    {{ formErrors.type[0] }}
			    	    	</p>
			    	    </div>

						<div class="column is-4" v-if="type === 'paid'">
							<label class="label">Price ($)</label>
							<p class="control">
								<input class="input" :class="{ ' is-danger': formErrors.price }" type="number" placeholder="10.00" v-model.number="price" @keydown="formErrors.price = false">
							</p>

							<p class="help is-danger" v-if="formErrors.price">
								{{ formErrors.price[0] }}
							</p>
						</div>
					</div>

					<!-- Component to add button to create a stripe account -->
					<p class="help is-danger" v-if="type === 'paid'">
						<create-stripe-connect-account :channelOwner="channelOwner"></create-stripe-connect-account>
					</p>

	    	    </div>

				<div class="field">
					<div class="file is-small" :class="[formErrors.channelImage ? ' is-danger' : '', ' is-primary']">
						<label class="file-label">
							<input type="file" name="channelImage" id="channelImage" class="file-input" @change.prevent="getFileName" ref="inputFile">
							<span class="file-cta">
								<span class="file-icon">
									<i class="fa fa-upload"></i>
								</span>
								<span class="file-label">{{ fileNameLabel }}</span>
							</span>
						</label>
					</div>

					<p class="help is-danger center-of-div" v-if="formErrors.channelImage">
						{{ formErrors.channelImage }}
					</p>
				</div>

				<div class="field" v-if="type ==='free' || channelOwner.stripe_id">
	    	        <div class="columns">
	    	            <div class="column is-three-quarters">
	    	                <p class="control">
								<button class="button" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click.prevent="createChannel">Create</button>
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

                // Form variables
				name: null,
                description: null,
                slug: null,
                visibility: 'public',
                type: 'free',
                price: null,
                channelImage: null,

				// Form Validations
                formErrors: {
                    name: false,
                    description: false,
                    slug: false,
                    visibility: false,
                    type: false,
                    channelImage: false,
                    price: false,
                },

                saveChanges: null,

                // Visibility options
                visibilityOptions: [
                    'public',
                    'unlisted',
                    'private'
                ],

                // Type options
                typeOptions: [
                    'free',
                    'paid'
                ],

                fileNameLabel: 'Choose a file...',
				channelOwner: null,
			};
		},

		computed: {
			channelUrl() {
				return this.$root.appUrl + '/channels/';
			}
		},

		filters: {
            capitalize(string) {
                return str(string).capitalize().s;
            }
        },

		watch: {
			type(value) {
				if (value === 'free') {
					this.price = 0;
				}
			}
		},

		methods: {

			createChannel() {
				this.saveChanges = 'saving';
				this.showIsLoading = true;
				this.formErrors.channelImage = false;

				this.channelImage = document.getElementById('channelImage').files[0];
				this.description = this.description ? this.description : '';

				var form = new FormData();
                form.append('name', this.name);
                form.append('slug', this.slug);
                form.append('description', this.description);
                form.append('visibility', this.visibility);
                form.append('type', this.type);
                form.append('price', this.price);

                if (this.channelImage) {
                	form.append('channelImage', this.channelImage);
                }

				axios.post('/create-channel/', form)
				     .then(({data}) => {
						this.saveChanges = 'Changes saved';
						this.showIsLoading = false;
						this.showEdit = false;
						this.$refs.inputFile.value = null;
						this.fileNameLabel = 'Choose a file...';

						// redirecting to the channels page
						window.location = this.$root.appUrl + '/' + this.$root.user.username + '/channels';
					})
				     .catch(({response}) => {
						this.showIsLoading = false;
						this.saveChanges = 'Faild to save changes'
						this.formErrors = response.data;
						this.formErrors.channelImage = response.data.channelImage[0];
					});
			},

			getFileName() {
				this.formErrors.channelImage = false;
				this.fileNameLabel = null;

				var file = document.getElementById("channelImage").files[0];
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
					this.fileNameLabel = 'Choose a file...'
				}

				if (file && extensions.indexOf(fileExtension) === -1) {
					this.formErrors.channelImage = 'Only jpeg, jpg, png, bmp, gif and svg formats are supported.';
					return;
				}

				if (file && fileSize > 4) {
				    this.formErrors.channelImage = 'The channel image may not be greater than 4 Megabyte.';
				    return;
				}
			},

			getChannelOwnerData() {
				axios.get('/' + this.$root.user.username + '/get-info')
					 .then(({data}) => {
						this.channelOwner = data.data.user;
				     })
				     .catch(({response}) => {
						this.error = response.data;
				     });
			}
		},

		mounted() {
			this.getChannelOwnerData();
		}
	}
</script>