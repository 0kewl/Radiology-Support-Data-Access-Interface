<!DOCTYPE html>
<html>
@include('components/header')
<body>
<!-- CSS -->
    <style>
		.container{
			min-height:622;
		}
    </style>
<!-- Page wrapper -->
<div class="container">
@include('components/menu')
    <div id="content-window">
        <div class="row-fluid">
            <!-- Cases search form -->
            <div class="span8 shadow" style="height:622px;">
                <h3 class="text-center">Case Search</h3>
                <br>
				<h4 style="color: #fff;">Search Query</h4>
                <div id="search-container" style="overflow-y: auto;">
                    @include('components/search-form')
                </div>
            </div>
            <!-- END search form -->

            <!-- Case lookup form -->
            <div class="span4 shadow" style="height:622px;">
                <h3 class="text-center">Case Lookup</h3>   
                <br>
				<h4 style="color: #fff;">Enter Case ID</h4>
                <fieldset>
                    <!-- Text input-->
                    <div class="control-group">
                        <div class="controls">
                            <input id="case-id" name="case-id" class="input-block-level" type="text">
                            <br>
                            <br>
                            <h5>Find similar cases based on:</h5>
                            {{ Form::select('similar-keywords[]', $keywords, '', array('id' => 'similar-keywords-list','class' => 'selectpicker', 'multiple' => 'multiple')) }}
                        </div>
                    </div>
                    <br>
                    <div style="margin:0 auto; text-align:center;">
                        <button id="case-search" class="btn btn-large btn-inverse" type="button" style="margin-top: 75px;">Search &amp; Compare Cases</button>
                    </div>
                </fieldset>
                @include('components/case-lookup-form')
            </div>
            <!-- END case lookup form -->
        </div>
    </div>
</div>

@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {

    $(".selectpicker").selectpicker();

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

$("#case-search").click(function(event) {
    event.preventDefault();

    $("#lookup-start").val("0");

    if ($("#case-id").val() == "") {
        // the case id input is empty
        alert("Please enter a Case ID.");
        return false;
    }
    else {
        var caseID = $("#case-id").val();
        $("#id").val(caseID);

        var selectedKeywords = $("#similar-keywords-list").val();
        $("#keywords").val(selectedKeywords);

	    $("#search-case").submit();
    }
});
</script>
</body>
</html>