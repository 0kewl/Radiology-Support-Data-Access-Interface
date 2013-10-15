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
<div class="container" style="margin-top: 22px;">
    @include('components/menu')
    <div class="row-fluid">
    	<div class="span12">
    		<div class="row-fluid">
    			<div class="span4 shadow" style="background-color:#fff; padding:12px; height: 800px; overflow-y: auto;">
    				<h3 class="text-center">Case Search</h3>
    				<br>
                    @include('components/search-form')
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
    		</div>
    	</div>
    </div>
</div>

 <!-- jQuery / JavaScript Code -->
<script type="text/javascript">
$(document).ready(function() {

    @if (!isset($caseid))
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
     $("#document-viewer").scrollTop(0);
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