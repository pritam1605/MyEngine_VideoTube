
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('buy-button', require('./components/Buy.vue'));
Vue.component('comment', require('./components/Comment.vue'));
Vue.component('playlist', require('./components/Playlist.vue'));
Vue.component('searchbox', require('./components/SearchBox.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('video-vote', require('./components/VideoVote.vue'));
Vue.component('video-list', require('./components/VideoList.vue'));
Vue.component('login-component', require('./components/Login.vue'));
Vue.component('video-component', require('./components/Video.vue'));
Vue.component('comment-box', require('./components/CommentBox.vue'));
Vue.component('channel-list', require('./components/ChannelList.vue'));
Vue.component('video-player', require('./components/VideoPlayer.vue'));
Vue.component('user-profile', require('./components/UserProfile.vue'));
Vue.component('video-upload', require('./components/VideoUpload.vue'));
Vue.component('channel-component', require('./components/Channel.vue'));
Vue.component('register-user', require('./components/RegisterUser.vue'));
Vue.component('subscribe-button', require('./components/Subscribe.vue'));
Vue.component('user-purchases', require('./components/UserPurchases.vue'));
Vue.component('user-dashboard', require('./components/UserDashboard.vue'));
Vue.component('video-comments', require('./components/VideoComments.vue'));
Vue.component('create-channel', require('./components/CreateChannel.vue'));
Vue.component('reset-password', require('./components/ResetPassword.vue'));
Vue.component('forgot-password', require('./components/ForgotPassword.vue'));
Vue.component('change-password', require('./components/ChangePassword.vue'));
Vue.component('show-notice', require('./components/ShowNoticeComponent.vue'));
Vue.component('channel-dashboard', require('./components/ChannelDashboard.vue'));
Vue.component('search-result-list', require('./components/SearchResultList.vue'));
Vue.component('user-subscriptions', require('./components/UserSubscriptions.vue'));
Vue.component('passwordless-entry', require('./components/PasswordlessEntry.vue'));
Vue.component('video-player-layout', require('./components/VideoPlayerLayout.vue'));
Vue.component('dashboard-video-list', require('./components/DashboardVideoList.vue'));
Vue.component('dashboard-channel-list', require('./components/DashboardChannelList.vue'));
Vue.component('create-stripe-connect-account', require('./components/CreateStripeConnectAccount.vue'));

const app = new Vue({
    el: '#app',
    data: window.myengine
});
