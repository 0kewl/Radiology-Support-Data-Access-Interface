/**
 *   Common jQuery functions
 */

 $(".edit-hashtag").click(function(event) {
    event.preventDefault();
    reloadDocument($(this).attr("doc"), true);
 });

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

    getHashtags($(this).attr('id'), false, function(hashtags) {
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

// display all cases in database
$("#all-search").click(function() {
    $("#main-query").val("*");
    $("#search").click();
});

// search on a hashtag
$("#hashtag-search-btn").click(function(event) {
    event.preventDefault();

    $("#hashtag-start").val("0");

    if ($("#hashtag").val() != "") {
        $("#search-hashtags").submit();
    }
    else {

        if ($.trim($("#hashtag-keyword").val()) == "") {
            // the case id input is empty
            alert("Please enter a hashtag to search.");
            $("#hashtag-keyword").val("");
            return false;
        }
        else {
            var hashtag = $("#hashtag-keyword").val();
            $("#hashtag").val(hashtag);

            $("#search-hashtags").submit();
        }
    }
});

/*
 *   Helper functions
 */

// display the selected case document in document viewer
function reloadDocument(caseID, editMode) {
    $(".result-snippet").each(function() {
        // clear any cases being viewed
        $(this).removeClass("viewing").css("background-color", 'gray');
    });

    var id = caseID;
    $("#res-" + id).addClass("viewing").css("background-color", '#444444');

    $("#document-viewer").html("");
    $("#hashtag-container").html("");

    getHashtags(id, editMode, function(hashtags) {
        // show the selected case
        $("#" + id + ".full-doc").clone().appendTo("#document-viewer").fadeIn("fast"), function() {
            $('#document-viewer').show();
        }
        $("#document-viewer").scrollTop(0);
        $("#hashtag-container").html(hashtags);
    });
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