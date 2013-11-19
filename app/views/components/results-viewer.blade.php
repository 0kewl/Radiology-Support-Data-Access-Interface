<!-- CSS -->
    <style>
        .text-18{
			font-size: 18px;
		}
		.alert{
			font-size: 17px;
			width: 255px
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
                    <div class="alert alert-info"><span><b>#{{{ $hashtag }}}</b> matched {{ $resultCount }} case.</span></div>
                @else
                    <div class="alert alert-info"><span><b>#{{{ $hashtag }}}</b> matched {{ $resultCount }} cases.</span></div>
                @endif
            @else
                @if ($resultCount == "1")
                    <div class="alert alert-info"><span><b>Your search matched {{ $resultCount }} case.</b></span></div>
                @else
                    <div class="alert alert-info"><span><b>Your search matched {{ $resultCount }} cases.</b></span></div>
                @endif
            @endif

            <div class="span4">
                <div id="results-container" style="float:left; height: 625px; overflow-y: auto; overflow-x: hidden; margin-bottom: 10px;">
                    {{ $tables }}
                </div>
                @if (isset($startPos))
                    <div id="pagination" style="margin-left: 14px; height: 25px; width: 240px;">
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
            <div id="document-loader" style="margin: 0 auto;"></div>
            <div id="hashtag-container" style="padding-left: 17px; height: 40px; overflow:auto;"></div>
			<div id="document-viewer" class="span7" style="font-size: 18px; background-color:#707070; float:left; height: 590px; overflow-y: auto; overflow-x: hidden;"></div>
        @endif
</div>
<!-- END search results viewer -->