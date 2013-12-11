<!DOCTYPE html>
<html>
@include('components/header')
<body>
<!-- CSS -->
    <style>
		.container{
			width: 1220px;
			height: auto;
		}
    </style>
<!-- Page wrapper -->
<div class="container">
@include('components/menu')
    <div id="content-window">
        <div class="row-fluid">
            <!-- Cases search form -->
            <div class="span8 shadow" style="padding:14px; height:542px;">
                <h3 class="text-center">Case Search</h3>
                <br>
				<h4 style="color: #fff;">Search Query</h4>
                <div id="search-container" style="overflow-y: auto;">
                    @include('components/search-form')
                </div>
            </div>
            <!-- END search form -->

            <!-- Case lookup form -->
            <div class="span4 shadow" style="padding:14px; height:540px;">
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
                        <button id="case-search" class="btn btn-large btn-inverse" type="button" style="margin-top:75px;">Search &amp; Compare Cases</button>
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
    
	/**
	 * Creates the select picker drop-down form element
     * for case lookup similarity
	 * @return SelectPickerFormInstance
	 */
    $(".selectpicker").selectpicker();

	/**
	 * Accesses main query to find keywords that match current value in search box
	 * @param main-query id to access possible keywords
	 * @return boolean
	 */
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

/**
 * Takes the parameters to perform a case search
 * @param int #case-id to access a particular case
 * @param keywords is to search by related cases
 * @return boolean
 */
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