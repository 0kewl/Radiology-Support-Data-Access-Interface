<!DOCTYPE html>
<html>
@include('components/header')
<body>
    <!-- CSS -->
    <style>
        .container {
            width: 1550px;
        }
        .result-snippet {
            height: 60px;
        }
        /* All cases does not work on case lookup page */
        #all-search {
            display: none;
        }
    </style>
<!-- Page wrapper -->
<div class="container" style="margin-top: 22px;">
    @include('components/menu')
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <!-- Case document -->
                <div id="case-information" class="span4 shadow" style="background-color:#707070; color:#fff; padding:12px; height: 800px; overflow-y: auto;">
                    <h3 class="text-center">Case Information</h3>
                    <br>
                    <div id="case-information-container" style="height:700px; overflow-y: auto;">
                        @if (!$doc)
                            <div class="alert alert-info">
                                <h5 class="text-center">Your search did not match any documents.</h5>
                            </div>
                        @else
                            {{ $doc }}
                        @endif  
                    </div>
                </div>
                <!-- END case document -->
                
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
        title : 'Add hashtag'
    });

    var hashtagPopover = '<small>Comma separate multiple tags</small><br><br>' +
                     '<input type="text" id="hashtag-input"><br><button id="add-hash"class="btn btn-inverse btn-small" onClick="addHashtag($(this).parents(&quot;.result-snippet&quot;).attr(&quot;id&quot;),$(&quot;#hashtag-input&quot;).val());">Add</button>' +
                     '<button type="button" id="close" class="btn btn-small btn-inverse" onclick="$(&quot;.add-hashtag&quot;).popover(&quot;hide&quot;);">Cancel</button>';

    $('.add-hashtag').attr('data-content', hashtagPopover);

});

// display the selected case document
$(".show").click(function() {
    $(".result-snippet").each(function() {
        // clear any cases being viewed
        $(this).removeClass("viewing").css("background-color", 'gray');
    });

    var id = $(this).attr("id");
    $("#res-" + id).addClass("viewing").css("background-color", '#444444');
    
    $("#document-viewer").html("");

    // show the selected case
    $("#" + id + ".full-doc").clone().appendTo("#document-viewer").show();
    $("#document-viewer").scrollTop(0);
});
</script>

@include('components/hashtag')

</body>
</html>
