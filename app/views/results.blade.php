<!DOCTYPE html>
<html>
<head>
    <title>Radiology Support Data Access Interface</title>
    <!-- CSS Imports -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.css')}}" type="text/css" />

    <!-- JavaScript Imports -->
    <script src="{{asset('assets/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

    <!-- CSS -->
    <style>
        body {
            background-color:#AAB3AB;
        }
        .shadow {
            -webkit-box-shadow: 0 4px 2px -2px #808080;
            -moz-box-shadow: 0 4px 2px -2px #808080;
            box-shadow: 0 4px 2px -2px #808080;
        }
        .box {
            -webkit-border-radius: 20px;
            -moz-border-radius: 20px;
            border-radius: 20px;
            background-color:#F5F5F5;
        }
        .viewing {
            background: #CFF09E;
        }
        .icon-star2 {
            color: gray;
            font-size: 20px;
            cursor: pointer;
        }
        .container {
            width: 1550px;
        }
    </style>
</head>
<body>
    <div class="container" style="padding-top:20px;">
        <div class="row-fluid" style="padding-bottom:50px;">
            <h2 class="text-center">Radiology Support Data Access Interface</h2>
        </div>
    </div>
<div class="container">
    <div class="row-fluid">
    	<div class="span12">
    		<div class="row-fluid">
    			<div class="span3 shadow" style="background-color:#fff; padding:12px; height: 800px; overflow-y: auto;">
    				<h3 class="text-center">Case Search</h3>
    				<br>
                    <form id="search-form" name="search-form" class="form-inline" action="results" method="post" enctype="multipart/form-data">
                        <fieldset>
                            <!-- Text input-->
                            <div class="control-group">
                                <div class="controls">
                                    <input id="main-query" name="main-query" placeholder="Search Query" class="input-block-level" type="text" value="{{ $response->query }}">
                                </div>
                            </div>
                            <br>
                            <input id="json" name="json" type="hidden">
                            <div id="keywords-container">
                                <div id="additional-keywords" class="additional-keywords">
                                    <!-- Dropdown Select-->
                                    <div class="control-group">
                                        <div class="controls">
                                            {{ Form::select('operator', $operators, '', array('id' => 'operator','class' => 'operator input-small')) }}
                                            {{ Form::select('field', array('' => '- Field -') + $keywords, 'default', array('id' => 'field','class' => 'field input-medium')) }}
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <input id="keyword" name="keyword" placeholder="Keyword" class="keyword input-large additional-keyword" type="text">
                                        </div>
                                        <br>
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
                <div id="search-results" class="span5 shadow" style="background-color:#fff; padding:12px; height: 800px;">
                    <h3 class="text-center">Search Results</h3>
                        @if (!$results)
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
                                {{ $results }}
                            </div>
                            <div id="document-viewer" class="span7" style="float:left; height: 650px; overflow-y: auto; overflow-x: hidden;">
                            
                            </div>
                        @endif
                </div>
    			<div id="case-information" class="span4 shadow" style="background-color:#fff; padding:12px; height: 650px;">
    				<h3 class="text-center">Case Information</h3>
    				<br>
    			</div>
    		</div>
    	</div>
    </div>
</div>

 <!-- jQuery / JavaScript Code -->
<script type="text/javascript">
$(document).ready(function() {

    @if (!isset($caseid))
        $("#case-information").hide();
        $("#search-results").toggleClass('span5 span8');
    @endif

    @foreach ($response->keywords as $element)
    addPopulatedField("{{ $element->operator }}", "{{ $element->field }}", "{{ $element->keyword }}");
    @endforeach
});

$("#add-field").click(function() {
    if (!isKeywordsFull()) {
        addKeywordFields(1);
    }
});

$(".show").click(function() {
    $(".result-snippet").each(function() {
        $(this).removeClass("viewing");
    });

    var id = $(this).attr("id");
    $("#res-" + id).addClass("viewing");
     $("#document-viewer").html("");
     ($("#" + id + ".full-doc").clone().appendTo("#document-viewer").show());
});

$(".icon-star2").click(function() {
    $(this).css("color", "#F7C511");
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

function addPopulatedField(operator, field, keyword) {
    if (!$("#keyword").val()) {
        $("#operator").val(operator);
        $("#field").val(field);
        $("#keyword").val(keyword);
    }
    else {
        var keywordClone = $("#additional-keywords").clone(true);
        keywordClone.find("input[type^=text]").each(function() {
            $(this).val(keyword);
        });
        keywordClone.find("select[class^=operator]").each(function() {
            $(this).val(operator);
        });
        keywordClone.find("select[class^=field]").each(function() {
            $(this).val(field);
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