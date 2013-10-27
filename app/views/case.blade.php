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
                <!-- Case document -->
                <div id="case-information" class="span4 shadow" style="background-color:#A0A0A0; padding:12px; height: 800px; overflow-y: auto;">
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

<!-- Load the common jQuery/JavaScript code -->
<script type="text/javascript" src="{{asset('assets/js/common.js')}}"></script>

 <!-- jQuery/JavaScript Code -->
<script type="text/javascript">
$(document).ready(function() {

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
</script>

</body>
</html>
