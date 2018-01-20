<template>
	<div class="box">
		<article class="media">
			<div class="media-left">
				<figure class="thumbnail image is-150x128 with-border">
	        		<a :href="videoUrl"><img :src="video.thumbnail" :alt="imageAlt"></a>
	      		</figure>
	    	</div>

	    	<div class="media-content">
	      		<div class="content">
	      			<p>
		        		<strong><a :href="videoUrl">{{ video.title }}</a></strong> (<small class="processing-message"><i v-if="!video.video_processed" class="fa fa-spinner fa-spin"></i>{{ processingMsg }}</small>)
						<p class="is-medium closer"><strong><small><a :href="channelUrl">{{ video.channel.name }}</a></small></strong> (<strong><small>{{ video.visibility | capitalize }}</small></strong>) (<strong><small>{{ video.type | capitalize }}</small></strong>)</p>
						<p v-if="video.description" class="is-medium closer tooltip is-tooltip-multiline is-tooltip-bottom" :data-tooltip="video.description">{{ videoDescription }}</p>
						<p v-else class="is-medium closer">No description available.</p>
					</p>
	      		</div>

	      		<div class="columns">
	      			<!-- <div class="small-icons">
	      				<a v-if="video.can_be_accessed_by_current_user && video.allows_comment">
	      					<span class="icon is-small"><i class="fa fa-comment-o"></i></span>
	      			    </a>
	      			</div>

	      			<div class="small-icons">
	      				<a v-if="video.can_be_accessed_by_current_user && video.allows_vote">
	      					<span class="icon is-small"><i class="fa fa-thumbs-o-up"></i></span>
	      				</a>
	      			</div> -->

	      			<div class="small-icons">
	      				<a v-if="video.is_owned_by_current_user" @click="showEdit = !showEdit">
	      					<span class="icon is-small"><i class="fa fa-pencil"></i></span>
	      				</a>
	      			</div>

	      			<div class="small-icons">
	      				<a v-if="video.is_owned_by_current_user" @click="deleteVideo">
	      			    	<span class="icon is-small"><i class="fa fa-trash-o"></i></span>
	      			    </a>
	      			</div>
	      		</div>
	    	</div>
	  	</article>

		<transition name="fade">
		  	<div class="field" v-if="showEdit">

	    	    <div class="field">
	    	        <label class="label is-small">Title</label>
	    	        <p class="control">
	    	            <input class="input is-small" :class="{ ' is-danger': formErrors.title }" type="text" placeholder="Untitled" v-model="title" @keydown="formErrors.title = false">

	    	            <p class="help is-danger" v-if="formErrors.title">
	    	                {{ formErrors.title[0] }}
	    	            </p>
	    	        </p>
	    	    </div>

	    	    <div class="field">
	    	        <label class="label is-small">Description</label>
	    	        <p class="control">
	    	            <textarea class="textarea is-small" :class="{ ' is-danger': formErrors.description }" placeholder="Video description goes here..." v-model="description" @keydown="formErrors.description = false"></textarea>

		    	        <p class="help is-danger" v-if="formErrors.description">
		    	            {{ formErrors.description[0] }}
		    	        </p>
	    	        </p>

	    	    </div>

    			<div class="field">
				    <div class="columns">
				        <div class="column is-4">
				            <label class="label is-small">Channel</label>
				            <p class="control">
				                <span class="select is-small" :class="{ 'is-danger': formErrors.channel }">
				                    <select v-model="channel" @change.prevent="formErrors.channel = false">
				                        <option v-for="channel in channels" :value="channel">{{ channel.name }}</option>
				                    </select>
				                </span>
				            </p>

				            <p class="help is-danger" v-if="formErrors.channel">
				                {{ formErrors.channel[0] }}
				            </p>
				        </div>

				        <div class="column is-4">
    		    	    	<label class="label is-small">Type</label>
    		    	    	<p class="control">
    		    	    	    <span class="select is-small" :class="{ 'is-danger': formErrors.type }">
    		    	    	        <select v-model="type" @change="formErrors.type = false">
    		    	    	            <option v-for="option in typeOptions"  :value="option">{{ option |  capitalize }}</option>
    		    	    	        </select>
    		    	    	    </span>
    		    	    	</p>

    		    	    	<p class="help is-danger" v-if="formErrors.type">
								{{ formErrors.type }}
    		    	    	</p>
    		    	    </div>

				        <div class="column is-4">
    		    	        <label class="label is-small">Visibility</label>
    		    	        <p class="control">
								<input class="input is-small" type="text" :value="channel.visibility" disabled>
    		    	        </p>

    		    	        <p class="help is-danger" v-if="formErrors.visibility">
    		    	            {{ formErrors.visibility[0] }}
    		    	        </p>
    		    	    </div>

				    </div>
				</div>

	    	    <div class="field">
	    	        <div class="columns">
	    	            <div class="column is-half">
	    	                <p class="control">
								<input class="is-checkbox is-small has-background-color is-primary" id="allowVotes" type="checkbox" name="allow_votes" v-model="allowVotes">
	    	                    <label for="allowVotes">Allow votes</label>
	    	                </p>
	    	            </div>

	    	            <div class="column is-half">
	    	                <p class="control">
								<input class="is-checkbox is-small has-background-color is-primary" id="allowComments" type="checkbox" name="allow_comments" v-model="allowComments">
	    	                    <label for="allowComments">Allow comments</label>
	    	                </p>
	    	            </div>
	    	        </div>
	    	    </div>

	    	    <div class="field">
	    	        <div class="columns">
	    	            <div class="column is-three-quarters">
	    	                <p class="control">
	    	                    <button class="button is-small" :class="[showIsLoading ? ' is-loading' : '', ' is-primary']" @click.prevent="updateVideoMetaData">Submit</button>
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
	import eventHub from './../eventhub.js';

	export default {
		props: ['video', 'page'],

		data() {
			return {

				showEdit: false,
				showIsLoading: false,
				uid: this.video.uid,

				// edit form variables
				title: this.video.title,
                description: this.video.description,
                type: this.video.type,
                price: this.video.price,
				allowComments: this.video.allows_comment,
                allowVotes: this.video.allows_vote,


				// Form Validations
                formErrors: {
                    title: false,
                    description: false,
                    type: false
                },

                // Type options
                typeOptions: [
                	'free',
                	'paid'
                ],

                channels: [],
                channel: this.video.channel,

                saveChanges: null,
			};
		},

		filters: {
			capitalize(string) {
		    	return str(string).capitalize().s;
		    }
		},

		watch: {
		    // whenever channel changes, this function will run
		    channel(newChannel) {
		        this.type = newChannel.type;
		        this.price = newChannel.price;
		    },

            type(type) {
				if (this.channel.type === 'free' && type === 'paid') {
					this.formErrors.type = 'Sorry!! Free channels can not have paid videos.';
				}

                if (type === 'free') {
                    this.price = 0;
                } else {
					this.price = this.channel.price;
                }
            }
		},

		computed: {
			videoUrl() {
				return '/videos/' + this.video.uid;
			},

			imageAlt() {
				return this.video.title + ' thumbnail';
			},

			processingMsg() {
				if (this.video.video_processed) {
					return this.humanReadableDate(this.video.created_at);
				} else {
					if (this.video.processed_percentage) {
						return  ' Processing ' + this.video.processed_percentage + '% finished';
					}

					return ' Processing starting up...';
				}
			},

			videoDescription() {
				let description = this.video.description

				if (description.length > 65) {
					return description.substring(0,65) + '....';
				}

				return description;
			},

			channelUrl() {
				return '/channels/' + this.video.channel.slug;
			}
		},

		methods: {
			humanReadableDate(timeStamp) {
		    	return moment(timeStamp).fromNow();
		    },

		    updateVideoMetaData() {
		    	this.saveChanges = 'saving';
		    	this.showIsLoading = true;

				if (this.channel.type === 'free' && this.type === 'paid') {
					this.saveChanges = 'Faild to save changes'
					this.showIsLoading = false;
					return;
				}

		    	return axios.put('/videos/' + this.uid , {
		    	    title: this.title,
		    	    description: this.description,
		    	    visibility: this.channel.visibility,
		    	    allows_vote: this.allowVotes,
		    	    allows_comment: this.allowComments,
		    	    type: this.type,
		    	    channel_id: this.channel.id,
					page: this.page ? this.page : null,
					price: this.price,
		    	}).then(({data}) => {
		    	    this.saveChanges = 'Changes saved';
		    	    this.showIsLoading = false;
					this.showEdit = false;

					// Updating the info in the actual video component
					eventHub.$emit('videolist-updated', data.data.videos);

		    	    setTimeout(() => {
		    	        this.saveChanges = null;
		    	    }, 3000);
				}).catch(({response}) => {
					this.formErrors = response.data;
					this.formErrors.type = response.data.type[0] ? response.data.type[0] : false;
					this.saveChanges = 'Faild to save changes'
		    	    this.showIsLoading = false;
		    	});
		    },

		    deleteVideo() {
		    	if (confirm('Are you sure?')) {
			    	return axios.delete('/videos/' + this.uid)
	    			    .then(({data}) => {
						eventHub.$emit('videolist-updated', data.data.videos);
    			    });
		    	}

		    	return;
		    },

		    getUserChannels() {
				if (this.$root.user.username) {
			        axios.get('/channels/user/' + this.$root.user.username + '/all-user-channels')
			             .then(({data}) => {
			                this.channels = data.data.channels;
			             })
			             .catch(({response}) => {
			                this.error = response.data;
			             });
			    }
		    }
		},

		 mounted() {
            this.getUserChannels();
        }
	}
</script>