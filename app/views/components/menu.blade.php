<!-- Top menu bar with links -->
<div class="navbar">
	<div class="navbar-inner">
	    <a class="brand" href="{{route('home') }}">Radiology Support Data Access Interface</a>
	    <ul class="nav">
	        <li><a href="{{ route('home') }}">Search Page</a></li>
	        <li><a href="#">Saved Searches</a></li>
			<li><a id="all-search" href="#">All Cases</a></li>
	    </ul>
		<div class="hash-search" style="margin-top:5px; float:right;">
			<input id="keyword" name="keyword" class="keyword input-large additional-keyword" type="text" color="white" style="height:15px; margin-top:5px;">
			<button id="hash-search" class="btn btn-small btn-inverse" type="button" style="margin-top:-5px;">Search Hash-Tags</button>
		</div>
	</div>
</div>
<!-- END Menu -->
