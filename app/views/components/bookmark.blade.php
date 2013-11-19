<script type="text/javascript">

// Read a page's URL
function getUrl()
{
    return window.location.href;
}

function getBookmark(url) {
	
	var url = getUrl();
	var queryURL = url.slice(window.location.href.indexOf('q=')+2);
    var opts = {
        lines:9, // The number of lines to draw
        length: 18, // The length of each line
        width: 5, // The line thickness
        radius: 12, // The radius of the inner circle
        corners: 1, // Corner roundness (0..1)
        rotate: 0, // The rotation offset
        direction: 1, // 1: clockwise, -1: counterclockwise
        color: '#fff', // #rgb or #rrggbb or array of colors
        speed: 1, // Rounds per second
        trail: 50, // Afterglow percentage
        shadow: false, // Whether to render a shadow
        hwaccel: false, // Whether to use hardware acceleration
        className: 'spinner', // The CSS class to assign to the spinner
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        top: '200', // Top position relative to parent in px
        left: '615' // Left position relative to parent in px
      };
      var target = document.getElementById('document-loader');
      var spinner = new Spinner(opts).spin(target);

  $.ajax({
      type: "GET",
      url: "{{ route('get-bookmarks') }}",
      data: {
          URL : url
      }
  })
  .done(function(msg) {
    spinner.stop();

    var element = '<span style="font-size: 18px;">Tags: </span>';
    if (msg['bookmark'] != null) {
      $.each(msg['bookmark'], function(index, value) {
        element = element + '<a href="{{ route('get-bookmark') }}?bookmark=' + value + '&start=0" class="bookmark" style="cursor: pointer; cursor: hand; font-size: 15px; margin-right:10px; color:#fff;">#' + value + '</a>';
      });
    }
    else {
      element = '';
    }
      callback(element);
  });
}

function addBookmark(queryString, bookmark) {
  if (bookmark == '') {
    alert("You must enter a bookmark name.");
    return false;
  }
  else {
    $.ajax({
        type: "POST",
        url: "{{ route('add-bookmark') }}",
        data: {
            URL : queryURL
            bookmark: bookmark
        }
    })
    .done(function(msg) {
        reloadDocument(msg);
        $("#bookmark-search").popover("hide");
    });
  }
}
</script>