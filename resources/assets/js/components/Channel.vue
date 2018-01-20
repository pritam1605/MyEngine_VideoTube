<template>
	<div class="box">
		<article class="media">
	    	<div class="media-left">
				<figure class="thumbnail image is-150x128 with-border">
	        		<a :href="channelUrl"><img :src="channel.imageUrl" :alt="imageAlt"></a>
	      		</figure>
	    	</div>

	    	<div class="media-content">
	      		<div class="content">
	        		<p>
	        			<strong><a :href="channelUrl">{{ channel.name }}</a></strong>
						<p v-if="channel.description" class="is-medium closer tooltip is-tooltip-multiline is-tooltip-bottom" :data-tooltip="channel.description">{{ channelDescription }}</p>
						<p v-else class="is-medium closer">No description available.</p>

						<div class="columns ">
							<div class="column is-3">
								<p class="is-medium">
									<strong><small>{{ channel.visibility | capitalize }}</small></strong>  (<small>{{ channel.type | capitalize }}</small>)
								</p>
							</div>

							<div class="column is-9">
								<p class="is-medium">
									Last updated <strong><small>{{ humanReadableDate(channel.updated_at) }}</small></strong>
								</p>
							</div>
						</div>

						<div class="columns">
							<div class="column is-3">
								<p class="is-small disabled">
									<a class="tag subscription-count">{{ pluralize('Video', channel.total_video_count, true) }}</a>
								</p>
							</div>

							<div class="column is-3">
								<p class="is-small disabled">
									<a class="tag subscription-count">{{ pluralize('Video View', channel.total_video_views, true) }}</a>
								</p>
							</div>

							<div class="column is-3" v-if="channel.is_owned_by_current_user">
								<p class="is-small disabled">
									<a class="tag subscription-count">{{ pluralize('Subscriber', channel.total_subscriptions, true) }}</a>
								</p>
							</div>

							<div class="column is-6">
								<p class="is-small">
									<a v-if="!channel.is_owned_by_current_user">
		            					<!-- Subscription component -->
										<subscribe-button v-if="channel.slug" :channelSlug="channel.slug"></subscribe-button>
		          					</a>
		          				</p>
							</div>
						</div>
	         		</p>
	      		</div>

    			<div class="columns">
    				<div class="small-icons">
	          			<a v-if="channel.is_owned_by_current_user && !channel.is_default" @click="showEdit = !showEdit">
	            			<span class="icon is-small"><i class="fa fa-pencil"></i></span>
	          			</a>
          			</div>

					<div class="small-icons">
						<a v-if="channel.is_owned_by_current_user && !channel.is_default" @click="deleteChannel">
							<span class="icon is-small"><i class="fa fa-trash-o"></i></span>
						</a>
					</div>
      			</div>
	    	</div>
	  	</article>

		<transition name="fade">
		  	<div class="field" v-if="showEdit">

	    	    <div class="field">
	    	        <label class="label is-small">Name</label>
	    	        <p class="control">
	    	            <input class="input is-small" :class="{ ' is-danger': formErrors.name }" type="text" placeholder="Untitled" v-model="name" @keydown="formErrors.name = false">

	    	            <p class="help is-danger" v-if="formErrors.name">
	    	                {{ formErrors.name[0] }}
	    	            </p>
	    	        </p>
	    	    </div>

	    	    <div class="field">
				  	<label class="label is-small">Channel Link</label>
				  	<div class="field has-addons">
	  		  			<p class="control">
	  		    			<a class="button is-static is-small">
	  		     				<strong class="is-small">{{ channelBaseUrl }}</strong>
	  		    			</a>
	  		  			</p>
	  		  			<p class="control is-expanded">
	  		    			<input class="input is-small" :class="{ ' is-danger': formErrors.slug }" type="text" v-model="slug" @keydown="formErrors.slug = false">
	  		  			</p>
	  				</div>

	  				<p class="help is-danger" v-if="formErrors.slug">
						{{ formErrors.slug[0] }}
	    	        </p>
				</div>

	    	    <div class="field">
	    	        <label class="label is-small">Description</label>
	    	        <p class="control">
	    	            <textarea class="textarea is-small" :class="{ ' is-danger': formErrors.description }" placeholder="Channel description goes here..." v-model="description" @keydown="formErrors.description = false"></textarea>

		    	        <p class="help is-danger" v-if="formErrors.description">
		    	            {{ formErrors.description[0] }}
		    	        </p>
	    	        </p>
	    	    </div>

    			<div class="field">
        	    	<div class="columns">
						<div class="column is-5">
    		    	        <label class="label is-small">Visibility</label>
    		    	        <p class="control">
    		    	            <span class="select is-small" :class="{ 'is-danger': formErrors.visibility }">
    		    	                <select v-model="visibility" @change="formErrors.visibility = false">
    		    	                    <option v-for="option in visibilityOptions"  :value="option">{{ option |  capitalize }}</option>
    		    	                </select>
    		    	            </span>
    		    	        </p>

    		    	        <p class="help is-danger" v-if="formErrors.visibility">
    		    	            {{ formErrors.visibility[0] }}
    		    	        </p>
    		    	    </div>

						<div class="column is-5">
    		    	    	<label class="label is-small">Type</label>
    		    	    	<p class="control">
    		    	    	    <span class="select is-small" :class="{ 'is-danger': formErrors.type }">
									<select v-model="type" @change.prevent="changeChannelType">
    		    	    	            <option v-for="option in typeOptions"  :value="option">{{ option |  capitalize }}</option>
    		    	    	        </select>
    		    	    	    </span>
    		    	    	</p>

    		    	    	<p class="help is-danger" v-if="formErrors.type">
    		    	    	    {{ formErrors.type[0] }}
    		    	    	</p>
    		    	    </div>

						<div class="column is-2" v-if="type === 'paid'">
							<label class="label is-small">Price ($)</label>
							<p class="control">
								<input class="input is-small" :class="{ ' is-danger': formErrors.price }" type="number" placeholder="10.00" v-model.number="price" @keydown="formErrors.price = false">
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
        	    	<label class="label is-small">Channel Image</label>
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
        	    	<p class="help is-danger" v-if="formErrors.channelImage">
        	    		{{ formErrors.channelImage }}
        	    	</p>
        	    </div>

				<div class="field" v-if="type ==='free' || channelOwner.stripe_id">
	    	        <div class="columns">
	    	            <div class="column is-three-quarters">
	    	                <p class="control">
	    	                    <button class="button is-small" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click.prevent="updateChannelData">Submit</button>
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
	    </transition>
	</div>
</template>

<script>
	import pluralize from 'pluralize';
	import eventHub from './../eventhub.js';

	export default {
		props: ['channel'],

		data() {
			return {

				showEdit: false,
				showIsLoading: false,

				// edit form variables
				name: this.channel.name,
                description: this.channel.description,
                slug: this.channel.slug,
                visibility: this.channel.visibility,
                type: this.channel.type,
                channelImage: null,
                price: this.channel.price,

				// Form Validations
                formErrors: {
                    name: false,
                    description: false,
                    slug: false,
                    type: false,
                    visibility: false,
                    channelImage: false,
                    price: false,
                },

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

                saveChanges: null,

                fileNameLabel: 'Choose a file...',

                channelSlugUrl: this.channel.slug,

                channelOwner: null,
			};
		},

		filters: {
			capitalize(string) {
		    	return str(string).capitalize().s;
		    }
		},

		computed: {
			imageAlt() {
				return this.channel.name + ' thumbnail';
			},

			channelUrl() {
				return '/channels/' + this.channel.slug;
			},

			channelBaseUrl() {
				return this.$root.appUrl + '/channels/';
			},

			channelDescription() {
				let description = this.channel.description

				if (description.length > 65) {
					return description.substring(0,65) + '....';
				}

				return description;
			}
		},

		watch: {
			type(value) {
				if (value === 'free') {
					this.price = 0;
				} else {
					this.price = this.channel.price;
				}
			}
		},

		methods: {
			pluralize,

			humanReadableDate(timeStamp) {
		    	return moment(timeStamp).fromNow();
		    },

		    updateChannelData() {
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

				axios.post('/channels/' + this.channelSlugUrl + '/edit', form)
				     .then(({data}) => {
						this.saveChanges = 'Changes saved';
						this.showIsLoading = false;
						this.showEdit = false;
						this.$refs.inputFile.value = null;
						this.fileNameLabel = 'Choose a file...';

						setTimeout(() => {
						    this.saveChanges = null;
						}, 3000);

						// Updating the info in the actual video component
						eventHub.$emit('channellist-updated', data.data.channel);
					})
				     .catch(({response}) => {
						this.showIsLoading = false;
						this.saveChanges = 'Faild to save changes'
						this.formErrors = response.data;
						this.formErrors.channelImage = response.data.channelImage[0];
					});
			},

		    deleteChannel() {
		    	if (confirm('Are you sure?')) {
			    	return axios.delete('/channels/' + this.slug)
	    			    .then(({data}) => {
						eventHub.$emit('channellist-updated', data.data.channel);
	    			});
		    	}

		    	return;
		    },

		    changeChannelType() {
				this.formErrors.type = false;
				console.log(this.type);
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
				if (this.$root.user.id === this.channel.user_id) {
					axios.get('/' + this.$root.user.username + '/get-info')
						 .then(({data}) => {
							this.channelOwner = data.data.user;
					     })
					     .catch(({response}) => {
							this.error = response.data;
					     });
				}
			}
		},

		mounted() {
			this.getChannelOwnerData();
		}
	}
</script>