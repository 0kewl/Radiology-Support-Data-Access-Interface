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
                    <form id="search-form" name="search-form" class="form-inline" action="results" method="post" enctype="multipart/form-data">
                        <fieldset>
                            <!-- Text input-->
                            <div class="control-group">
                                <div class="controls">
                                    <input id="main-query" name="main-query" placeholder="Search Query" class="input-block-level" type="text">
                                </div>
                            </div>
                            <br>
                            <input id="json" name="json" type="hidden">                
                            <div id="keywords-container">
                                <div id="additional-keywords" class="additional-keywords">
                                    <!-- Dropdown Select-->
                                    <div class="control-group">
                                        <div class="controls">
                                            {{ Form::select('operator', $operators, '', array('class' => 'operator input-small')) }}
                                            {{ Form::select('field', array('' => '- Field -') + $keywords, 'default', array('class' => 'field input-medium')) }}
                                            <input name="keyword" placeholder="Keyword" class="keyword input-xlarge additional-keyword" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button id="add-field" class="btn btn-small" type="button">+ Add Field</button>
                        </fieldset>
                    </form> 
                    <div style="margin:0 auto; text-align:center;">
                        <button id="search" class="btn btn-large">Search</button>
                    </div> 
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

    $("#search-form").submit();
 });

/*auto populates field once have an case ID*/ 
$("#case-search").click(function() {
	$("#search-case").submit();
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