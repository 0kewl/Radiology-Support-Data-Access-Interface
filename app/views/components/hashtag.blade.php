<script type="text/javascript">

function getHashtags(caseID, editMode, callback) {

    var opts = {
        lines:9, // The number of lines to draw
        length: 10, // The length of each line
        width: 4, // The line thickness
        radius: 10, // The radius of the inner circle
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
        top: '0', // Top position relative to parent in px
        left: '125' // Left position relative to parent in px
      };
      var target = document.getElementById('document-loader');
      var spinner = new Spinner(opts).spin(target);

  $.ajax({
      type: "GET",
      dataType: "json",
      url: "{{ route('get-hashtags') }}",
      data: {
          caseID: caseID
      }
  })
  .done(function(msg) {
    spinner.stop();
	var element = '<span class="text-18" style="color:#F88017;"><b><u>Tags:</u></b> </span>';
    if (msg != '') {
      $.each(msg, function(index, value) {
        if (editMode) {
          element += '<p onClick="deleteHashtag($(this));" id="' + value.tag + '" case="' + caseID + '" class="delete-hashtag label label-inverse" style="cursor:pointer;">X</p><a href="{{ route('hashtag-results') }}?hashtag=' + value.tag + '&start=0" class="hashtag" style="cursor:pointer; cursor:hand; font-size:15px; margin-right:10px; color:#fff;">#' + value.tag + '</a>';
        }
        else {
          element += '<a href="{{ route('hashtag-results') }}?hashtag=' + value.tag + '&start=0" class="hashtag" style="cursor:pointer; cursor:hand; font-size:15px; margin-right:10px; color:#fff;">#' + value.tag + '</a>';
        }
      });
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
      var data = $.parseJSON(msg);

      if (data.error) {
        alert(data.message);
      }
      else {
        reloadDocument(msg, false);
        $(".add-hashtag").popover("hide");
      }
    });
  }
}

function deleteHashtag(h) {
  var hashtag = $(h).attr("id");
  var caseID = $(h).attr("case");

  $.ajax({
        type: "POST",
        url: "{{ route('delete-hashtag') }}",
        data: {
            caseID: caseID,
            hashtag: hashtag
        }
    })
    .done(function(msg) {
      reloadDocument(msg, true);
    });
}
</script>