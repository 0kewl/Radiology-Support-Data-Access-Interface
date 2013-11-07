<!-- Top menu bar with links -->
<div class="navbar">
	<div class="navbar-inner">
	    <a class="brand" href="{{route('home') }}">Radiology Support Data Access Interface</a>
	    <ul class="nav">
	        <li><a href="{{ route('home') }}">Search Page</a></li>
	        <li><a href="#">Saved Searches</a></li>
			<li><a id="all-search" href="#">All Cases</a></li>
	    </ul>
		<input id="keyword" name="keyword" placeholder="Search Hash tags" class="keyword input-large additional-keyword" type="text" color="white" style="float:right;">
	</div>
</div>
<!-- END Menu -->
