<!DOCTYPE html>
<html>
@include('components/header')
<body>
    <!-- CSS -->
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
    				<button id="bookmark-search" class="btn btn-inverse" style="float:right; position:relative;">Bookmark</button>
					<h3 style="text-align:center; margin-left: 88px;">Case Search</h3>
    				<br>
					<h4 style="color: #FFFFFF;">Search Query</h4>
                    <div id="search-container" style="height:650px; overflow-y: auto;">
                        @include('components/search-form')
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

    // re-populate the from with the POST data
    @foreach ($response->keywords as $element)
        addPopulatedField("{{ $element->operator }}", "{{ $element->field }}", "{{ $element->keyword }}");
    @endforeach

    $("#search-results").toggleClass("span8" , "span5");
});

// display the selected case (document)
$(".show").click(function() {
    $(".result-snippet").each(function() {
        // clear any cases being viewed
        $(this).removeClass("viewing").css("background-color", 'gray');
    });

    var id = $(this).attr("id");
    $("#res-" + id).addClass("viewing").css("background-color", '#444444');

    $("#document-viewer").html("");
    $("#hashtag-container").html("");

    getHashtags($(this).attr('id'), function(hashtags) {
        // show the selected case
        $("#" + id + ".full-doc").clone().appendTo("#document-viewer").fadeIn("fast"), function() {
            $('#document-viewer').show();
        }
        $("#document-viewer").scrollTop(0);
        $("#hashtag-container").html(hashtags);
    });
});
// prevents bad things from happening :)
$('.add-hashtag').on('click', function(e) {e.preventDefault(); e.stopPropagation(); return true;});



// display the selected case (document)
function reloadDocument(caseID) {
    $(".result-snippet").each(function() {
        // clear any cases being viewed
        $(this).removeClass("viewing").css("background-color", 'gray');
    });

    var id = caseID;
    $("#res-" + id).addClass("viewing").css("background-color", '#444444');

    $("#document-viewer").html("");
    $("#hashtag-container").html("");

    getHashtags(id, function(hashtags) {
        // show the selected case
        $("#" + id + ".full-doc").clone().appendTo("#document-viewer").fadeIn("fast"), function() {
            $('#document-viewer').show();
        }
        $("#document-viewer").scrollTop(0);
        $("#hashtag-container").html(hashtags);
    });
}
</script>

@include('components/hashtag')

</body>
</html>