/*
 *   Common jQuery functions
 */

$("#did-you-mean").click(function(event) {
    event.preventDefault();
    $("#main-query").val($("#did-you-mean").text());
    $("#search").click();
});

// display the selected case document
$(".show").click(function() {
    $(".result-snippet").each(function() {
        // clear any cases being viewed
        $(this).removeClass("viewing").css("background-color", 'gray');
    });

    var id = $(this).attr("id");
    $("#res-" + id).addClass("viewing").css("background-color", '#444444');

    $("#document-viewer").html("");
    $("#hashtag-container").html("");
    $("#image-component").html("");

    getHashtags($(this).attr('id'), function(hashtags) {
        // show the selected case
        $("#" + id + ".full-doc").clone().appendTo("#document-viewer").fadeIn("fast"), function() {
            $('#document-viewer').show();
        }
        $("#document-viewer").scrollTop(0);
        $("#hashtag-container").html(hashtags);
    });
});

// perform a search query
$("#search").click(function(event) {
    event.preventDefault();

    var q = $("#main-query").val();

    if (q == "") {
        // oops...the query input is empty
        alert("Please enter a search query.");
        // don't submit the form
        return false;
    }
    else {

        $("#keywords-container").find(".additional-keywords").each(function(index, value) {
            if (!$(this).find(".keyword").val() == "") {
                q += " " + $(this).find(".operator").val() + " " + $(this).find(".field").val() + ":" + $(this).find(".keyword").val();
            }
        });

        $("#q").val(encodeURIComponent(q));
        $("#start").val("0");

        $("#search-form").submit();
    }
 });

// remove the corresponding field
$("#remove-field").click(function() {
    $(this).parent().css('class', 'additional-keywords').remove();
    isKeywordsFull();
});

// display all cases in database
$("#all-search").click(function() {
    $("#main-query").val("*");
    $("#search").click();
});

// search on a hashtag
$("#hashtag-search-btn").click(function(event) {
    event.preventDefault();

    $("#hashtag-start").val("0");

    if ($("#hashtag-keyword").val() == "") {
        // the case id input is empty
        alert("Please enter a hashtag to search.");
        return false;
    }
    else {
        var hashtag = $("#hashtag-keyword").val();
        $("#hashtag").val(hashtag);

        $("#search-hashtags").submit();
    }
});

/*
 *   Helper functions
 */

// display the selected case document in document viewer
function reloadDocument(caseID) {
    $(".result-snippet").each(function() {
        // clear any cases being viewed
        $(this).removeClass("viewing").css("background-color", 'gray');
    });

    var id = caseID;
    $("#res-" + id).addClass("viewing").css("background-color", '#444444');

    $("#document-viewer").html("");
    $("#hashtag-container").html("");

    getHashtags(id, function(hashtags) {
        // show the selected case
        $("#" + id + ".full-doc").clone().appendTo("#document-viewer").fadeIn("fast"), function() {
            $('#document-viewer').show();
        }
        $("#document-viewer").scrollTop(0);
        $("#hashtag-container").html(hashtags);
    });
}

// clones and creates a new keyword field
function addKeywordFields(qty) {
    for (var i=0; i<qty; i++) {
        var keywordClone = $("#additional-keywords").clone(true);
        keywordClone.find("input[type^=text]").each(function() {
            $(this).val("");
        });
        keywordClone.find("button[id^=remove-field]").each(function() {
            // remove the default display:none property
            $(this).css("display", "");
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
    if ($('.additional-keyword').length < 10) {
        $("#add-field").show();
		return false;
    }
	//disable button
	$("#add-field").hide();
	return true;
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function split(val) {
    return val.split( / \s*/ );
}

function extractLast(term) {
    return split(term).pop();
}