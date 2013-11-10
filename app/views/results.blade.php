<!DOCTYPE html>
<html>
@include('components/header')
<body>
    <!-- Additional CSS -->
    <style>
        .container {
            width: 1550px;
        }
    </style>
<!-- Page wrapper -->
<div class="container" style="margin-top: 22px;">
    @include('components/menu')
    <div class="row-fluid">
    	<div class="span12">
    		<div class="row-fluid">
                <!-- Case search form -->
    			<div class="span4 shadow" style="background-color:#707070; padding:12px; height: 800px; overflow-y: hidden;">
					<div id="search-option">
						<button id="bookmark-search" class="btn btn-inverse" style="float:right; position:relative;">Bookmark</button>
						<h3 style="text-align:center; margin-left: 88px;">Case Search</h3>
						<h4 style="color: #FFFFFF;">Search Query</h4>
						<div id="search-container" style="height:650px; overflow-y: auto;">
						  @include('components/search-form')
						</div>
					</div>
					<div id="image-option">
						<!-- <img id="image-holder" src="{{ asset('assets/img/medical_images/')}}/" alt="case image"> -->
					</div>
    			</div>
                <!--END case search form -->
                
                @include('components/results-viewer')

    		</div>
    	</div>
    </div>
</div>

@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {

    var currentQuery = getParameterByName("q");
    var query = decodeURIComponent(currentQuery);

    // re-build the search form with updated values
    $("#main-query").val(query);

    $("#start").val(getParameterByName("start"));
    $("#hashtag-start").val(getParameterByName("start"));
	
    $(".add-hashtag").popover({
        placement: 'top',
        html: 'true',
        title : 'Case Hashtags'
    });

    var hashtagPopover = '<strong>Add Hashtags</strong><br><small>Comma separate multiple tags</small><br><br>' +
                         '<input type="text" id="hashtag-input"><br><button id="add-hash"class="btn btn-inverse btn-small" onClick="addHashtag($(this).parents(&quot;.result-snippet&quot;).attr(&quot;id&quot;),$(&quot;#hashtag-input&quot;).val());">Add</button>' +
                         '<button type="button" id="close" class="btn btn-small btn-inverse" onclick="$(&quot;.add-hashtag&quot;).popover(&quot;hide&quot;);">Cancel</button>';

    $('.add-hashtag').attr('data-content', hashtagPopover);

    @if (!isset($caseid))
        $("#search-results").toggleClass('span8', 'span5');
    @endif
    $("#search-results").toggleClass("span8" , "span5");

    $("#main-query").autocomplete({
        source: "{{ route('spellcheck') }}",
        minLength: 3,
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

// prevents bad things from happening :)
$('.add-hashtag').on('click', function(e) {e.preventDefault(); e.stopPropagation(); return true;});

// toggle between images and search fields
$("#image-option").click(function() {
	$("#image-option").hide();
	$("#search-option").show();
});

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

// add a new keyword search field
$("#add-field").click(function() {
    if (!isKeywordsFull()) {
        addKeywordFields(1);
    }   
});
</script>
@include('components/hashtag')
</body>
</html>