<!-- Search results viewer -->
<div id="search-results" class="span8 shadow" style="background-color:#fff; padding:12px; height: 800px;">
    <h3 class="text-center">Search Results</h3>
        @if (!$tables)
            <br>
            <div class="alert alert-info">
                <h5 class="text-center">Your search did not match any documents.</h5>
            </div>
        @else
            @if ($resultCount == "1")
                <div class="alert alert-success" style="width: 250px;"><span><b>Your search matched {{ $resultCount }} document.</b></span></div>
            @else
                <div class="alert alert-success" style="width: 250px;"><span><b>Your search matched {{ $resultCount }} documents.</b></span></div>
            @endif
            <div id="results-container" class="span4" style="margin-right: 20px; float:left; height: 650px; overflow-y: auto; overflow-x: hidden;">
                {{ $tables }}
            </div>
				<div id="document-viewer" class="span7" style="float:left; height: 650px; overflow-y: auto; overflow-x: hidden;">
            </div>
        @endif
</div>
<!-- END search results viewer -->