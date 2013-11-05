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
                <div class="span8 shadow" style="background-color:#707070; padding:12px; height:675px;">
                    <h3 class="text-center">Case Search</h3>
                    <br>
					<h4 style="color: #FFFFFF;">Search Query</h4>
                    <div id="search-container" style="height:575px; overflow-y: auto;">
                        @include('components/search-form')
                    </div>
                </div>
                <!-- END search form -->

                <!-- Case lookup form -->
                <div class="span4 shadow" style="background-color:#707070; padding:12px; height:675px;">
                    <h3 class="text-center">Case Lookup</h3>   
                    <br>
					<h4 style="color: #FFFFFF;">Enter Case ID</h4>
                    <form class="form-inline" id="search-case" name="search-case" action="case-lookup" method="post">
                        <fieldset>
                            <!-- Text input-->
                            <div class="control-group">
                                <div class="controls">
                                    <input id="case-id" name="case-id" class="input-block-level" type="text">
                                    <br>
                                    <br>
                                    <h5>Find similar cases based on:</h5>
                                    {{ Form::select('similar-keywords[]', $keywords, '', array('id' => 'similar-keywords','class' => 'selectpicker', 'multiple' => 'multiple')) }}
                                </div>
                            </div>
                            <input id="keywords-array" name="keywords-array" type="hidden">
                            <br>
                            <div style="margin:0 auto; text-align:center;">
                                <button id="case-search" class="btn btn-large btn-inverse" type="button" style="margin-top: 75px;">Search &amp; Compare Cases</button>
                            </div>
                        </fieldset>
                    </form>             
                </div>
                <!-- END case lookup form -->
            </div>
        </div>
    </div>

@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {
    $(".selectpicker").selectpicker();
    addKeywordFields(2); // show two additional keyword inputs on page load
});

$("#case-search").click(function() {

    if ($("#case-id").val() == "") {
        // oops...the case id input is empty
        alert("Please enter a Case ID.");
        return false;
    }
    else {
	   $("#search-case").submit();
    }
});

</script>

</body>
</html>