<template>
	<transition name="fade">
		<article class="message " :class="messageType" v-if="showNotice">
			<div class="message-header">
		    	<p><strong>{{ messageTitle }}</strong></p>
		    	<button class="delete" @click="closeNoticeBox"></button>
		  	</div>
		  	<div class="message-body" v-html="message"></div>
		</article>
	</transition>
</template>

<script>

	import eventHub from './../eventhub.js';

	export default {
		props: ['messageTitle', 'message', 'noticeType'],

		data() {
			return {
				showNotice: true
			};
		},

		computed: {
			messageType() {
				if (this.noticeType) {
					return 'is-' + this.noticeType.toLowerCase();
				}

				return 'is-danger';
			}
		},

		methods: {
			closeNoticeBox() {
				this.showNotice = false;
				eventHub.$emit('close-notice-box');
			}
		}
	}
</script>