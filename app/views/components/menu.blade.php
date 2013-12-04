<!-- Top menu bar with links -->
<div class="navbar">
	<div class="navbar-inner" style="text-align:center;">
	    <h4>Radiology Support Data Access Interface</h4>
	    <div style="margin-top:-25px;">
		    <ul class="nav">
		        <li>
		        	<a href="{{ route('home') }}">
		        		<button id="search-btn" class="btn btn-small btn-inverse" type="button" style="margin-left: 25px; margin-top: 25px;">Search</button>
		        	</a>
		        </li>
				<li>
					<a id="all-search" href="#">
						<button id="all-cases-btn" class="btn btn-small btn-inverse" type="button" style="margin-left: 25px; margin-top: 25px;">All Cases</button>
					</a>
				</li>
		        <li>
		        	<a href="{{route('get-bookmarks') }}">
		        		<button id="bookmarks-btn" class="btn btn-small btn-inverse" type="button" style="margin-left: 95px; margin-top: 25px;">Bookmarks</button>
		        	</a>
		        </li>
		        <li>
		        	<a href="{{route('get-bookmarks') }}">
		        		<button id="bookmarks-btn" class="btn btn-small btn-inverse" type="button" style="margin-left: 25px; margin-top: 25px;">Hashtags</button>
		        	</a>
		        </li>
		    </ul>
		</div>
		<div style="margin-top:-25px;">
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
</div>
<!-- END Menu -->
