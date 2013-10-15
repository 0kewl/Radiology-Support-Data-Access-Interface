<!DOCTYPE html>
<html>
@include('components/header')
<body>
<div class="container" style="margin-top: 22px;">
    @include('components/menu')
        <div id="content-window">
            <div class="row-fluid">
                <div class="span8 shadow" style="background-color:#fff; padding:12px; height:675px;">
                    <h3 class="text-center">Case Search</h3>
                    <br>
                    @include('components/search-form')
                </div>
                <div class="span4 shadow" style="background-color:#fff; padding:12px; height:675px;">
                    <h3 class="text-center">Case Lookup</h3>   
                    <br>
                    <form class="form-inline" id="search-case" name="search-case" action="case-lookup" method="post">
                        <fieldset>
                            <!-- Text input-->
                            <div class="control-group">
                                <div class="controls">
                                    <input id="case-id" name="case-id" placeholder="Enter Case ID" class="input-block-level" type="text">
                                </div>
                            </div>
                            <input id="keywords-array" name="keywords-array" type="hidden">
                            <br>
                            <div style="margin:0 auto; text-align:center;">
                                <button id="case-search" class="btn btn-large" type="button">Populate Fields &amp; Search</button>
                            </div>
                        </fieldset>
                    </form>             
                </div>
            </div>
        </div>
    </div>

<!-- jQuery / JavaScript Code -->
<script type="text/javascript">
$(document).ready(function() {
    addKeywordFields(2);
});

$("#add-field").click(function() {
    if (!isKeywordsFull()) {
        addKeywordFields(1);
    }
});

/* form processing before POST */
$("#search").click(function() {
    var data = [];
    // get our operators, fields, and keywords
    $("#keywords-container").find(".additional-keywords").each(function() {
        // check if a keyword exists
        if (!$(this).find(".keyword").val() == "") {
            element = {};

            element ["operator"] = $(this).find(".operator").val();
            element ["field"] = $(this).find(".field").val();
            element ["keyword"] = $(this).find(".keyword").val();

            data.push(element);
        }
    });
    var jsonData = JSON.stringify(data);
    $("#json").val(jsonData);

    if ($("#main-query").val() == "") {
        alert("Please enter a search query.");
        return false;
    }
    else {
        $("#search-form").submit();
    }
 });

/*auto populates field once have an case ID*/ 
$("#case-search").click(function() {
    if ($("#case-id").val() == "") {
        alert("Please enter a Case ID.");
        return false;
    }
    else {
	   $("#search-case").submit();
    }
});
 
/* Clones then creates a new keyword field */
function addKeywordFields(qty) {
    for (var i=0; i<qty; i++) {
        var keywordClone = $("#additional-keywords").clone(true);
        keywordClone.find("input[type^=text]").each(function() {
            $(this).val("");
        });
        keywordClone.appendTo("#keywords-container");
    }
}

/* Test to determine if maximum search keywords is reached */
function isKeywordsFull() {
    if ($('.additional-keyword').length >= 10) {
        return true;
    }
    return false;
}
</script>

</body>
</html>