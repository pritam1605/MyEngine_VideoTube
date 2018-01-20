<template>
	<form class="searchbox" @submit.prevent="searchResult">
		<div class="field has-addons searchbox">
		  <div class="control">
		    <input class="input searchbox-navbar" type="text" id="search" name="q" placeholder="Search a video..." @keyup.enter="searchResult"  v-model="searchData">
		  </div>

		  <div class="control">
			<button class="button is-primary"><i class="fa fa-search"></i></button>
		  </div>
		</div>
	</form>
</template>

<script>
	export default {

		data() {
			return {
				searchData: null,
			};
		},

		methods: {
			searchResult() {
				if (!this.searchData) {
					return;
				}

				window.location.href = '/search?q=' + this.searchData;
			},

			getSearchResultFromAlgolia() {
				var videos = algoliaSearchClient.initIndex('myengine_videotube_video');
				var channels = algoliaSearchClient.initIndex('myengine_videotube_channel');

				autocomplete('#search', { hint: true }, [

					{
						source: autocomplete.sources.hits(videos, { hitsPerPage: 5, filters: 'visible=1' }),
						displayKey: 'title',
						templates: {
							suggestion: function(suggestion) {
								return '<p><img src="' + suggestion.thumbnail + '" class="searchbox-image-video"/> <span class="search-result-text" >' + suggestion._highlightResult.title.value + '</span></p>';
							}
						}
					},

					{
						source: autocomplete.sources.hits(channels, { hitsPerPage: 5, filters: 'visible=1' }),
						displayKey: 'name',
						templates: {
							suggestion: function(suggestion) {
								return '<p><img src="' + suggestion.imageUrl + '" class="searchbox-image-channel"/> <span class="search-result-text" >' + suggestion._highlightResult.name.value + '</span></p>';
							}
						}
					}
				]).on('autocomplete:selected', function(event, suggestion, dataset) {
					if (suggestion.name){
				    	this.searchData = suggestion.name;
					} else {
						this.searchData = suggestion.title;
					}

				    this.searchResult();
				}.bind(this));
			}
		},

		mounted() {
			this.getSearchResultFromAlgolia();
		}
	}
</script>