<!DOCTYPE html>
<html>
@include('components/header')
<body>
<!-- CSS -->
    <style>
		.container {
			width: auto;
			height: auto;
		}
    </style>
<!-- Page wrapper -->
<div class="container">
    @include('components/menu')
    <div class="row-fluid">
    	<div class="span12">
    		<div class="row-fluid">
                <!-- Case search form -->
    			<div class="span4 shadow" style="height: 215px; overflow-y: hidden;">
					<div id="search-option">
						<button id="bookmark-search" class="btn btn-inverse" style="float:right; position:relative;">Bookmark Query</button>
						<h3 style="text-align:center; margin-left: 88px;">Case Search</h3>
						<h4 style="color: #FFFFFF;">Search Query</h4>
						<div id="search-container" style="overflow-y: auto;">
						  @include('components/search-form')
						</div>
					</div>
    			</div>
                <!--END case search form -->                
                @include('components/results-viewer')
    		</div>
    	</div>
    </div>
    <div class="row-fluid">
        <div class="span4 shadow" id="image-component" style="margin-top: -550px; height: 550px; overflow-y: hidden; text-align: center;">
        </div>
    </div>
</div>

@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {

    $('.hashtag').tooltipster();

	/**
	 * Submit the search using the enter key
	 * @return boolean
	 */
    $('input').keydown(function(event) {
        if (event.keyCode == 13) {
            $("#search").click();
            return false;
         }
    }); 

    // populate the search form with updated values
    var currentQuery = getParameterByName("q");
    var query = decodeURIComponent(currentQuery);
    $("#main-query").val(query);
    $("#start").val(getParameterByName("start"));
    $("#hashtag-start").val(getParameterByName("start"));
	
	/**
	 * Hash-tag pop-up
	 * @param case ID
	 * @return void
	 */	
    $(".add-hashtag").popover({
        placement: 'top',
        html: 'true',
        title : 'Case Hashtags'
    });
    var hashtagPopover = '<strong>Add Hashtags</strong><br><small>Comma separate multiple tags</small><br><br>' +
                         '<input type="text" id="hashtag-input"><br><button id="add-hash"class="btn btn-inverse btn-small" onClick="addHashtag($(this).parents(&quot;.result-snippet&quot;).attr(&quot;id&quot;),$(&quot;#hashtag-input&quot;).val());">Add</button>' +
                         '<button type="button" id="close" class="btn btn-small btn-inverse" onclick="$(&quot;.add-hashtag&quot;).popover(&quot;hide&quot;);">Cancel</button>';

    $('.add-hashtag').attr('data-content', hashtagPopover);

	/**
	 * Bookmark pop-up
	 * @param page URL
	 * @return void
	 */
	$("#bookmark-search").popover({
        placement: 'bottom',
        html: 'true',
        title : 'Add Bookmark'
    });
    var bookmarkPopover = '<strong>Name your saved search:</strong><br><br>' +
                          '<input type="text" id="bookmark-input"><br><button id="add-bookmark"class="btn btn-inverse btn-small" onClick="addBookmark($(&quot;#bookmark-input&quot;).val());">Add</button>' +
                          '<button type="button" id="close" class="btn btn-small btn-inverse" onclick="$(&quot;#bookmark-search&quot;).popover(&quot;hide&quot;);">Cancel</button>';

    $('#bookmark-search').attr('data-content', bookmarkPopover);
	
	/**
	 * Spell check
	 * @return void
	 */
    @if (!isset($caseid))
        $("#search-results").toggleClass('span8', 'span5');
    @endif
    $("#search-results").toggleClass("span8" , "span5");

	/**
	 * Accesses main query to find keywords that match current value in search box
	 * @param #main-query to access possible keywords
	 * @return boolean
	 */
    $("#main-query").autocomplete({
        source: "{{ route('autocomplete') }}",
        minLength: 2,
        select: function(event, ui) {
            var terms = split(this.value);
            // remove the current input
            terms.pop();
            // add the selected item
            terms.push(ui.item.value);
            // add placeholder to get the comma-and-space at the end
            terms.push( "" );
            this.value = terms.join( " " );
            return false;
        }
    });
});

/**
 * Prevents the default href action from firing
 * @return boolean
 */
$('.add-hashtag').on('click', function(e) {e.preventDefault(); e.stopPropagation(); return true;});

/**
 * 
 * @param 
 * @return void
 */
$(".directional").click(function(event) {

    @if (isset($hashtag))
        event.preventDefault();

        var hashtag = getParameterByName("hashtag");
        $("#hashtag").val(encodeURIComponent(hashtag));

        var currentPos = parseInt($("#hashtag-start").val());
        if ($(this).attr("id") == "next-set") {
            $("#hashtag-start").val(currentPos + 10);
        }
        else {
            $("#hashtag-start").val(currentPos - 10);
        }
        $("#search-hashtags").submit();
    @else
        event.preventDefault();
        var q = getParameterByName("q");
        $("#q").val(q);

        var currentPos = parseInt($("#start").val());
        if ($(this).attr("id") == "next-set") {
            $("#start").val(currentPos + 10);
        }
        else {
            $("#start").val(currentPos - 10);
        }

        $("#search-form").submit();
    @endif
 });
</script>
@include('components/hashtag')
@include('components/bookmark')
</body>
</html>