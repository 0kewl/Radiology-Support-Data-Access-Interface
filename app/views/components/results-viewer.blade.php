<!-- CSS -->
    <style>
		.alert{
			font-size: 17px;
			width: 255px;
		}
		.boxes{
			height: 40px;
			padding-left: 10px;
			border-width: thin;
			border-color: #d3d3d3;
			border-style:solid;
		}
		.move-right{
			margin-left: 320px;
		}
    </style>
<!-- Search results viewer -->
<div id="search-results" class="span8 shadow" style="height: 800px;">
    <h3 class="text-center">Search Results</h3>
        @if (!$tables)
            <br>
            @if (!empty($suggestion))
                <p><i>Did you mean: <a href="#" style="color:#fff;"><strong><span id="did-you-mean">{{ $suggestion }}</span></strong></a></i></p>
            @endif 
            <div class="well">
                @if (isset($hashtag))
                    <h5 class="text-center text-18">No documents tagged with #{{{ $hashtag }}} found. </h5>
                @else
                    <h5 class="text-center text-18">Your search did not match any documents.</h5>
                @endif
            </div>
        @else
            @if (isset($hashtag))
                @if ($resultCount == "1")
                    <div class="alert alert-info"><b>#{{{ $hashtag }}}</b> matched {{ $resultCount }} case.</div>
                @else
                    <div class="alert alert-info"><b>#{{{ $hashtag }}}</b> matched {{ $resultCount }} cases.</div>
                @endif
            @else
                @if ($resultCount == "1")
                    <div class="alert alert-info"><b>Your search matched {{ $resultCount }} case.</b></div>					
                @else
                    <div class="alert alert-info"><b>Your search matched {{ $resultCount }} cases.</b></div>
                @endif						
            @endif
			<div id="query-container" class="move-right boxes" style="margin-top:-60px; margin-bottom:10px; overflow:hidden;">
				<div id="query-string" class="text-18" style="overflow:auto;"><b><u>Query:</u></b> All Cases</div>
			</div>
			<div style="margin-left:20px; width:350px;">
				<div class="span4">
					<div id="results-container" style="float:left; width:300px; height:625px; overflow-y:auto; overflow-x:hidden; margin-bottom:10px; margin-left:-15px;">				
						{{ $tables }}
					</div>
					@if (isset($startPos))
						<div id="pagination" style="margin-left:-15px; height:25px; width:300px;">
							<div style="float:left;">
								@if ($startPos != 0)
									<a href="#"><strong id="previous-set" class="directional" style="color:#fff; text-decoration:underline;">< Previous</strong></a>
								@endif
							</div>
							<div style="float:right;">
								@if ($startPos + 10 < $resultCount)
									<a href="#"><strong id="next-set" class="directional" style="color:#fff; text-decoration:underline;">Next ></strong></a>
								@endif
							</div>
						</div>
					@endif
				</div>
			</div>            
            <div id="document-loader" style="margin: 0 auto;"></div>
            <div id="hashtag-container" class="move-right boxes" style="overflow:hidden;"><div id="tags-string" class="text-18" style="overflow:auto;"><b><u>Tags:</u></b></div></div>
			<div class="move-right"><div id="document-viewer" class="span12 text-18 boxes" style="height:590px; overflow-y:auto; overflow-x:hidden;"></div></div>
        @endif
</div>
<!-- END search results viewer -->