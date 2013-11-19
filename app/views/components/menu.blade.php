<!-- Top menu bar with links -->
<div class="navbar">
	<div class="navbar-inner">
	    <a class="brand" href="{{route('home') }}">Radiology Support Data Access Interface</a>
	    <ul class="nav">
	        <li><a href="{{ route('home') }}">Search</a></li>
	        <li><a href="{{route('get-bookmarks') }}">Bookmarks</a></li>
			<li><a id="all-search" href="#">All Cases</a></li>
	    </ul>
		<div class="hashtag-search" style="margin-top:5px; float:right;">
		<form id="search-hashtags" name="search-hashtags" action="{{ route('hashtag-results') }}" method="get">
		    <input id="hashtag" name="hashtag" type="hidden" value="">
		    <input id="hashtag-start" name="start" type="hidden" value="">
		</form>
			<input id="hashtag-keyword" name="hashtag-keyword" class="keyword input-large additional-keyword" type="text" color="white" style="height:20px; margin-top:5px;">
			<button id="hashtag-search-btn" class="btn btn-small btn-inverse" type="button" style="margin-top:-5px;">Search Hashtags</button>
		</div>
	</div>
</div>
<!-- END Menu -->
