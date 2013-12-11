/**
 * Submit the search using the enter key
 * @return boolean
 */
$('input').keydown(function(event) {
    if (event.keyCode == 13) {
        focused = $(":focus");
        targetForm = $(focused).attr('id');

        if (targetForm == "main-query") {
            $("#search").click();
            return false;
        }
        else if (targetForm == "hashtag-keyword") {
            $("#hashtag-search-btn").click();
            return false;     
        }
        else if (targetForm == "case-id") {
            $("#case-search").click();
            return false;     
        }
     }
});

/**
 * Gets auto-complete for search query terms
 * @return boolean
 */
$("#hashtag-keyword").autocomplete({
    source: "hashtagSuggest",
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

/**
 * Displays the edit hashtag control
 * @return void
 */
$(".edit-hashtag").click(function(event) {
   event.preventDefault();
   reloadDocument($(this).attr("doc"), true);
});

/**
 * Gets spell check results 
 * @return void
 */
$("#did-you-mean").click(function(event) {
    event.preventDefault();
    $("#main-query").val($("#did-you-mean").text());
    $("#search").click();
});

/**
 * Displays the selected case document
 * @return void
 */
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

/**
 * Performs a search query when the search button is clicked
 * @return void
 */
$("#search").click(function(event) {
    event.preventDefault();

    var q = $("#main-query").val();
    q = $.trim(q);

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

/**
 * Displays all cases in database
 * @return void
 */
$("#all-search").click(function() {
    $("#main-query").val("*");
    $("#search").click();
});

/**
 * Searches on a hashtag when hashtag search button is clicked
 * @return void
 */
$("#hashtag-search-btn").click(function(event) {
    event.preventDefault();

    $("#hashtag-start").val("0");

    if ($("#hashtag").val() != "") {
        $("#hashtag").val($.trim($("#hashtag").val()));
        $("#hashtag-keyword").val($.trim($("#hashtag-keyword").val()));
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
            var hashtag = $.trim($("#hashtag-keyword").val());
            $("#hashtag").val(hashtag);

            $("#search-hashtags").submit();
        }
    }
});

/**
 * Displays the selected case document in document viewer
 * @param String caseID
 * @param Boolean editMode
 * @return void
 */
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

/**
 * Returns the value of the given parameter from the query string
 * @param String name
 * @return results
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

/**
 * Splits a string with a whitespace delimiter
 * @param val
 * @return
 */
function split(val) {
    return val.split( / \s*/ );
}

/**
 * Returns the last term in an array
 * @param term
 * @return
 */
function extractLast(term) {
    return split(term).pop();
}