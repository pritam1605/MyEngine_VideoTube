<template>
    <div class="column">
        <div class="column is-two-thirds is-offset-2">
            <div class="box">

                <!-- File upload component -->
                <div class="field" v-if="!uploading || uploadingFailed">
                    <div class="file is-centered is-boxed is-medium has-name" :class="[formErrors.extension ? ' is-danger' : '', ' is-primary']">
                        <label class="file-label">
                            <input class="file-input" type="file" name="video" id="video" @change.prevent="uploadVideo">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <i class="fa fa-cloud-upload"></i>
                                </span>
                                <span class="file-label">Choose video to upload</span>
                            </span>
                        </label>
                    </div>

                    <div class="video-upload-message">
                        <label>{{ fileNameLabel }}</label>
                        <p class="help is-danger is-centered" v-if="formErrors.extension">
                            {{ formErrors.extension }}
                        </p>
                    </div>
                </div>

                <!-- <div class="notification is-danger" v-if="uploadingFailed && showNotification">
                    <button class="delete" @click.prevent="closeNotification"></button>
                    <p>Something went wrong. Please try again later.</p>
                </div> -->

                <div class="field" v-if="uploading && !uploadingFailed">

                    <div class="notification is-info" v-if="!uploadingComplete">
                        <p>The video will be available at <a :href="videoUrl" target="_blank">{{ videoUrl }}</a></p>
                    </div>

                    <div class="notification is-success" v-if="uploadingComplete && showNotification">
                        <button class="delete" @click.prevent="closeNotification"></button>
                        <p>Upload complete. Video is now processing. Go to your <a :href="userVideosUrl" target="_blank">videos</a> once you enter video details.</p>
                    </div>

                    <div class="progress-bar" v-if="!uploadingComplete">
                        <progress class="progress is-primary" :value="progressValue" max="100"></progress>
                    </div>

                    <div class="field">
                        <label class="label">Title</label>
                        <p class="control">
                            <input class="input" :class="{ 'is-danger': formErrors.title }" type="text" placeholder="Untitled" v-model="title" @keydown="formErrors.title = false">

                            <p class="help is-danger" v-if="formErrors.title">
                                {{ formErrors.title[0] }}
                            </p>
                        </p>
                    </div>

                    <div class="field">
                        <label class="label">Description</label>
                        <p class="control">
                            <textarea class="textarea" :class="{ 'is-danger': formErrors.description }" placeholder="Video description goes here..." v-model="description" @keydown="formErrors.description = false"></textarea>

                            <p class="help is-danger" v-if="formErrors.description">
                                {{ formErrors.description[0] }}
                            </p>
                        </p>
                    </div>

                    <div class="field">
                        <div class="columns">
                            <div class="column is-4">
                                <label class="label">Channel</label>
                                <p class="control">
                                    <span class="select" :class="{ 'is-danger': formErrors.channel }">
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
                                <label class="label">Type</label>
                                <p class="control">
                                    <span class="select" :class="{ 'is-danger': formErrors.type }">
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
                                <label class="label">Visibility</label>
                                <p class="control">
                                    <input class="input" type="text" :value="channel.visibility" disabled>
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
                                    <button class="button " :class="[showIsLoading ? 'is-loading' : '', 'is-primary']" @click.prevent="updateVideoMetaData">Submit</button>

                                    <button v-if="uploadSuccess" class="button is-primary" @click.prevent="newStartUpload">
                                        <span class="icon is-left"><i class="fa fa-repeat"></i></span>&nbsp;&nbsp;New Upload
                                    </button>
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
    </div>
</template>

<script>
    export default {

        data() {
            return {
                uid: null,
                uploading: false,
                uploadingComplete: false,
                uploadingFailed: false,

                // Form Variables
                file: null,
                title: 'untitled',
                description: null,
                type: 'free',
                price: 0,
                allowComments: true,
                allowVotes: true,

                saveChanges: null,

                progressValue: 0,
                showNotification: true,
                showIsLoading: false,

                // Form Validations
                formErrors: {
                    title: false,
                    description: false,
                    extension: false
                },

                // Type options
                typeOptions: [
                    'free',
                    'paid'
                ],

                channels: [],
                channel: null,

                fileNameLabel: null,
                uploadSuccess: false
            };
        },

        filters: {
            capitalize(string) {
                return str(string).capitalize().s;
            }
        },

        computed: {
            videoUrl() {
                return this.$root.appUrl + '/videos/' + this.uid;
            },

            userVideosUrl() {
                return this.$root.user.username + '/videos/';
            }
        },

        watch: {
            // whenever channel changes, this function will run
            channel(newChannel) {
                this.type = newChannel.type;
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

        methods: {

            uploadVideo() {
                this.formErrors.extension = false;
                this.fileNameLabel = null;

                this.file = document.getElementById('video').files[0];

                let extensions = ['3gp', 'mp4', 'mpeg', 'mov', 'webm', 'f4v', 'flv',' m4v', 'mkv', 'avi', 'wmv'];

                if (!this.file) {
                    return;
                }

                if (this.file) {
                    var fileExtension = this.file.name.split('.').pop().toLowerCase();
                    var fileSize = (this.file.size/(1024*1024)).toFixed(2);
                }

                if (this.file && this.file.name) {
                    if (this.file.name.length > 30) {
                        this.fileNameLabel = this.file.name.substring(0, 30) + '....' + fileExtension;
                    } else {
                        this.fileNameLabel = this.file.name;
                    }
                }

                if (this.file && extensions.indexOf(fileExtension) === -1) {
                    this.formErrors.extension = 'This video format is not supported.';
                    return;
                }

                if (this.file && fileSize > 100) {
                    this.formErrors.extension = 'The video may not be greater than 100 Megabytes.';
                    return;
                }

                this.uploading = true;
                this.uploadingComplete = false;
                this.uploadingFailed = false;

                this.store().then(() => {
                    var form = new FormData();
                    form.append('video', this.file);
                    form.append('uid', this.uid);

                    // Store the video on the server
                    axios.post('/upload', form, {
                        onUploadProgress: (progressEvent) => {
                            if (progressEvent.lengthComputable) {
                                this.updateProgressBarValue(progressEvent);
                            }
                        }
                    }).then(() => {
                        this.uploadingComplete = true;
                    }).catch(({response}) => {
                        this.uploadingFailed = true;
                    });
                }).catch(({response}) => {
                    this.uploadingFailed = true;
                });
            },

            updateProgressBarValue(e) {
                this.progressValue = Math.round((e.loaded/e.total) * 100);
            },

            // Store the default video metadata in the database and generate video UID
            store() {
                return axios.post('/videos', {
                    title: this.title,
                    description: this.description,
                    visibility: 'public',
                    type: this.type,
                    price: this.price,
                    extension: this.file.name.split('.').pop()
                }).then(({data}) => {
                    this.uid = data.data.uid;
                }).catch(({response}) => {
                    this.formErrors = response.data;
                    this.formErrors.extension = response.data.extension[0];
                });
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
                    channel_id: this.channel.id,
                    price: this.price,
                    type: this.type
                }).then(({data}) => {
                    this.saveChanges = 'Changes saved';
                    this.showIsLoading = false;

                    this.uploadSuccess = true;
                    setTimeout(() => {
                        this.saveChanges = null;
                    }, 3000);
                }).catch((error) => {
                    this.formErrors = error.response.data;
                    this.saveChanges = 'Faild to save changes';
                    this.showIsLoading = false;
                    this.uploadSuccess = false;
                });
            },

            closeNotification() {
                this.showNotification = false;
            },

            getUserChannels() {
                axios.get('/channels/user/' + this.$root.user.username + '/all-user-channels')
                     .then(({data}) => {
                        this.channels = data.data.channels;

                        let selectedChannel = this.channels[this.channels.length - 1];

                        this.channel = selectedChannel;
                        this.visibility = selectedChannel.visibility;
                        this.type = selectedChannel.type;
                        this.price = selectedChannel.price;
                     })
                     .catch(({response}) => {
                        this.error = response.data;
                     });
            },

            newStartUpload() {
                window.location = '/upload';
            }
        },

        mounted() {
            window.onbeforeunload = () => {
                if (this.uploading && !this.uploadingComplete && !this.uploadingFailed) {
                    return 'Are you sure you want to navigate away?';
                }
            };

            this.getUserChannels();
        }
    }
</script>
