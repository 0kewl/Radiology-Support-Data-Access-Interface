<!DOCTYPE html>
<html>
@include('components/header')
<body>
<!-- Page wrapper -->
<div class="container" style="margin-top: 22px;">
    @include('components/menu')
        <div id="content-window">
            <div class="row-fluid">
                <!-- Cases search form -->
                <div class="span8 shadow" style="background-color:#A0A0A0 ; padding:12px; height:675px;">
                    <h3 class="text-center">Case Search</h3>
                    <br>
                    <div id="search-container" style="height:575px; overflow-y: auto;">
                        @include('components/search-form')
                    </div>
                </div>
                <!-- END search form -->

                <!-- Case lookup form -->
                <div class="span4 shadow" style="background-color:#A0A0A0 ; padding:12px; height:675px;">
                    <h3 class="text-center">Case Lookup</h3>   
                    <br>
                    <form class="form-inline" id="search-case" name="search-case" action="case-lookup" method="post">
                        <fieldset>
                            <!-- Text input-->
                            <div class="control-group">
                                <div class="controls">
                                    <input id="case-id" name="case-id" placeholder="Enter Case ID" class="input-block-level" type="text">
                                    <br>
                                    <br>
                                    <h5>Find similar cases based on:</h5>
                                    {{ Form::select('similar-keywords[]', $keywords, '', array('id' => 'similar-keywords','class' => 'selectpicker', 'multiple' => 'multiple')) }}
                                </div>
                            </div>
                            <input id="keywords-array" name="keywords-array" type="hidden">
                            <br>
                            <div style="margin:0 auto; text-align:center;">
                                <button id="case-search" class="btn btn-large" type="button" style="margin-top: 75px;">Search &amp; Compare Cases</button>
                            </div>
                        </fieldset>
                    </form>             
                </div>
                <!-- END case lookup form -->
            </div>
        </div>
    </div>

<!-- Load the common jQuery/JavaScript code -->
<script type="text/javascript" src="{{asset('assets/js/common.js')}}"></script>

<!-- jQuery/JavaScript Code -->
<script type="text/javascript">
$(document).ready(function() {
    $('.selectpicker').selectpicker();
    // show two additional keyword inputs on page load
    addKeywordFields(2);
});

// case search processing before HTTP POST action
$("#case-search").click(function() {

    if ($("#case-id").val() == "") {
        // oops...the case id input is empty
        alert("Please enter a Case ID.");
        // don't submit the form
        return false;
    }
    else {
       // send the HTTP POST request
	   $("#search-case").submit();
    }
});

//display all cases in database
$("#all-search").click(function() {
	$("#main-query").val("*");
	$("#search").click();
});
</script>

</body>
</html>