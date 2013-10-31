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
    			<div class="span4 shadow" style="background-color:#A0A0A0; padding:12px; height: 800px; overflow-y: hidden;">
    				<button id="search" class="btn btn-inverse" style="float:right; position:relative;">Bookmark</button>
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

<!-- Load the common jQuery/JavaScript code -->
<script type="text/javascript" src="{{asset('assets/js/common.js')}}"></script>

 <!-- jQuery/JavaScript Code -->
<script type="text/javascript">
$(document).ready(function() {

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
        $(this).removeClass("viewing");
    });

    var id = $(this).attr("id");
    $("#res-" + id).addClass("viewing");
    $("#document-viewer").html("");

    // show the selected case
    $("#" + id + ".full-doc").clone().appendTo("#document-viewer").show();
    $("#document-viewer").scrollTop(0);
});

// display all cases in database
$("#all-search").click(function() {
    $("#main-query").val("*");
    $("#search").click();
});
</script>

</body>
</html>