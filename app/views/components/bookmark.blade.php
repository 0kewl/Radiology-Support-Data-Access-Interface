<script type="text/javascript">

function getCurrentURL()
{
    return window.location.href;
}

function addBookmark(bookmarkName) {

  var queryString = getCurrentURL();
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