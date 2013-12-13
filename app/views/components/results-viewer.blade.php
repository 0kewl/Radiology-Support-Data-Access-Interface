<!-- CSS -->
    <style>
		.alert-info{
			overflow: auto;
			height: 20px;
		}
		.alert{
			font-size: 17px;
			width: 255px;
		}
		.boxes{
			height: 40px;
			padding-left: 10px;
			border-width: thin;
			border-color: #d3d3d3;
			border-style: solid;
		}
		.move-right{
			margin-left: 320px;
		}
    </style>
<!-- Search results viewer -->
<div id="search-results" class="span8 shadow" style="height:800px;">
	<div id="document-loader" style="margin: 0 auto;"></div>
    <h3 class="text-center">Search Results</h3>
        @if (!$tables)
            <br>
            @if (!empty($suggestion))
                <p><i>Did you mean: <a href="#" style="color:#fff;"><strong><span id="did-you-mean">{{{ $suggestion }}}</span></strong></a></i></p>
            @endif 
            <div class="well">
            	@if (isset($caseID))
            		<h5 class="text-center text-18">Your selected case did not match any documents</h5>
            	@else
	                @if (isset($hashtag))
	                    <h5 class="text-center text-18">No documents tagged with <u>#{{{ $hashtag }}}</u> found</h5>
	                @else
	                    <h5 class="text-center text-18">Your search did not match any documents</h5>
	                @endif
	            @endif
            </div>
        @else
            @if (isset($hashtag))
                @if ($resultCount == "1")
                    <div class="alert alert-info"><b><u>#{{{ $hashtag }}}</u></b> matched <u>{{ $resultCount }}</u> case</div>
                @else
                    <div class="alert alert-info"><b><u>#{{{ $hashtag }}}</u></b> matched <u>{{ $resultCount }}</u> cases</div>
                @endif
            @else
                @if ($resultCount == "1")
                    <div class="alert alert-info"><b><u>{{ $resultCount }}</u> case found
                    	@if (isset($count))
                    		<small>(ONLY {{{ $count }}})</small>
                    	@endif
						</b>
                    </div>
                @else
                    <div class="alert alert-info"><b><u>{{ $resultCount }}</u> cases found
						@if (isset($count))
                    		<small>(TOP {{{ $count }}})</small>
                    	@endif
						</b>
                    </div>
                @endif						
            @endif
            @if (isset($query))
				<div id="query-container" class="move-right boxes" style="margin-top:-60px; margin-bottom:10px; overflow-x:hidden; overflow-y:auto;">
					<span id="query-string" class="text-18" style="color:white;"><b><u>Query</u>:</b>  <span style="font-size:14px; font-weight:normal; color:#fff;">{{{ $query }}}</span></span>
				</div>
			@endif
			<div style="margin-left:20px; width:350px;">
				<div class="span2">
					<div id="results-container" style="float:left; width:300px; height:625px; overflow-y:auto; overflow-x:hidden; margin-bottom:10px; margin-left:-15px;">{{ $tables }}</div>
					@if (isset($startPos))
						<div id="pagination" style="margin-left:-15px; height:25px; width:300px;">
							<div style="float:left;">
								@if ($startPos != 0)
									<a href="#"><strong id="previous-set" class="directional label label-inverse" style="color:#fff; text-decoration:underline;">< Previous</strong></a>
								@endif
							</div>
							<div style="float:right;">
								@if ($startPos + 10 < $resultCount)
									<a href="#"><strong id="next-set" class="directional label label-inverse" style="color:#fff; text-decoration:underline;">Next ></strong></a>
								@endif
							</div>
						</div>
					@endif
				</div>
			</div>
            <div id="hashtag-container" class="move-right boxes" style="overflow-x:auto; overflow-y:hidden;"><span id="tags-string" class="text-18" style="color:#F88017;"><b><u>Tags</u>:</b></span></div>
			<div class="move-right"><div id="document-viewer" class="span12 text-18 boxes" style="padding-right: 20px; height:590px; overflow-y:auto; overflow-x:hidden;"></div></div>
        @endif
</div>
<!-- END search results viewer -->