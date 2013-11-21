<script type="text/javascript">

function getHashtags(caseID, callback) {

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
      url: "{{ route('get-hashtags') }}",
      data: {
          caseID: caseID
      }
  })
  .done(function(msg) {
    spinner.stop();

    var element = '<span style="font-size: 18px;">Tags: </span>';
    if (msg['hashtags'] != null) {
      $.each(msg['hashtags'], function(index, value) {
        element = element + '<a href="{{ route('hashtag-results') }}?hashtag=' + value + '&start=0" class="hashtag" style="cursor: pointer; cursor: hand; font-size: 15px; margin-right:10px; color:#fff;">#' + value + '</a>';
      });
    }
    else {
      element = '';
    }
      callback(element);
  });
}

function addHashtag(caseID, hashtags) {
  if (hashtags == '') {
    alert("You must enter at least one hashtag.");
    return false;
  }
  else {
    $.ajax({
        type: "POST",
        url: "{{ route('add-hashtags') }}",
        data: {
            caseID: caseID,
            hashtags: hashtags
        }
    })
    .done(function(msg) {
        reloadDocument(msg);
        $(".add-hashtag").popover("hide");
    });
  }
}
</script>