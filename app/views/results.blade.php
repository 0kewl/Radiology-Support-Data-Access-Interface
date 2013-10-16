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
    			<div class="span4 shadow" style="background-color:#fff; padding:12px; height: 800px; overflow-y: auto;">
    				<h3 class="text-center">Case Search</h3>
    				<br>
                    <div id="search-container" style="height:700px; overflow-y: auto;">
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

// save a case for later
$(".icon-star2").click(function() {
    $(this).css("color", "#F7C511");
    // TODO: Implement bookmark feature
});

</script>

</body>
</html>