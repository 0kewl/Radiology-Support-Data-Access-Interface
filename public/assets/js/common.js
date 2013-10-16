/*
 *   Common jQuery functions which support form rules and processing.
 */

// search form processing before HTTP POST action
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

            // push the element onto the data stack
            data.push(element);
        }
    });

    // generate a JSON object for Solr processing
    var jsonData = JSON.stringify(data);
    $("#json").val(jsonData);

    if ($("#main-query").val() == "") {
        // oops...the query input is empty
        alert("Please enter a search query.");
        // don't submit the form
        return false;
    }
    else {
        // send the HTTP POST request
        $("#search-form").submit();
    }
 });

// add a new keyword search field
$("#add-field").click(function() {
    if (!isKeywordsFull()) {
        addKeywordFields(1);
    }
});

// remove the corresponding field
$("#remove-field").click(function() {	
    $( this ).parent().css('class', 'additional-keywords').remove();
});

// clones and creates a new keyword field
function addKeywordFields(qty) {
    for (var i=0; i<qty; i++) {
        var keywordClone = $("#additional-keywords").clone(true);
        keywordClone.find("input[type^=text]").each(function() {
            $(this).val("");
        });
        keywordClone.appendTo("#keywords-container");
    }
}

// populate a field from previous form data
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

// determine if maximum search keywords is reached
function isKeywordsFull() {
    if ($('.additional-keyword').length >= 10) {
        return true;
    }
    return false;
}