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
                
                <!-- Search results viewer -->
                <div id="search-results" class="span5 shadow" style="background-color:#fff; padding:12px; height: 800px;">
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
        $("#search-results").toggleClass('span5 span8');
    @endif

    // re-populate the from with the POST data
    @foreach ($response->keywords as $element)
        addPopulatedField("{{ $element->operator }}", "{{ $element->field }}", "{{ $element->keyword }}");
    @endforeach
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