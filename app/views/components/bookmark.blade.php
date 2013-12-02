<script type="text/javascript">

/**
* Posts a new bookmark to a case
* @param bookmarkName the name of the bookmark
* @return void
*/
function addBookmark(bookmarkName) {
  var url = window.location.href;
  var queryString = url.slice(window.location.href.indexOf('?q='));
  if (bookmarkName == '') {
    alert("You must enter a bookmark name.");
    return false;
  }
  else {
    $.ajax({
        type: "POST",
        url: "{{ route('add-bookmark') }}",
        data: {
            bookmarkName : bookmarkName,
            url : queryString
        }
    })
    .done(function(msg) {
        $("#bookmark-search").popover("hide");
		alert("Bookmark added.");
    });
  }
}
</script>