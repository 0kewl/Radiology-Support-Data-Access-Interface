<!DOCTYPE html>
<html>
<head>
    <title>Radiology Support Data Access Interface</title>
    <!-- CSS Imports -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" type="text/css" />

    <!-- JavaScript Imports -->
    <script src="{{asset('assets/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

    <!-- CSS -->
    <style>
        body {
            background-color:#B6B6B1;
        }
        .shadow {
            -webkit-box-shadow: 0 4px 2px -2px #808080;
            -moz-box-shadow: 0 4px 2px -2px #808080;
            box-shadow: 0 4px 2px -2px #808080;
        }
    </style>
</head>
<body>
    <div class="container" style="padding-top:20px;">
        <div class="row-fluid" style="padding-bottom:50px;">
            <h2 class="text-center">Radiology Support Data Access Interface</h2>
        </div>
        <div id="content-window">
            <div class="row-fluid">
                <div class="span7 shadow" style="background-color:#fff; padding:12px; height:650px;">
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
                                            <input name="keyword" placeholder="Keyword" class="keyword input-large additional-keyword" type="text">
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
                <div class="span5 shadow" style="background-color:#fff; padding:12px; height:650px;">
                    <h3 class="text-center">Case Information Lookup</h3>   
                    <br>
                    <form class="form-inline">
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
                                <button class="btn btn-large" type="button">Auto-Populate Fields &amp; Search</button>
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