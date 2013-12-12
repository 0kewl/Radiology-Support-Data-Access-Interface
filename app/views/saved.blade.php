<!DOCTYPE html>
<html>
@include('components/header')
<body>
<!-- CSS -->
    <style>
		.container {
			width: 1220px;
			height: auto;
		}
        .fancy {
            text-align: center;
            color: #eee;
            font-size: 20px;
            text-shadow: 2px 3px 3px #292929;
        }
		.pagination {
			margin:0px;
		}
    </style>
<!-- Page wrapper -->
<div class="container">
    <div class="row-fluid">
    	<div class="span12 shadow" style="height:600px; overflow-y:auto;">
            @if ($bookmarks->isEmpty())
                <div class="well">
                    <p class="fancy">No Bookmarks Found</p>
                </div>
            @else
                <div id="bookmark-pages">
                    <div class="well">
                        <p class="fancy" style="text-align:left;">Total bookmarks: {{{ $count }}}</p>
                        {{ $bookmarks->links() }}
                    </div>
                </div>
                <ul>
                @foreach($bookmarks AS $bookmark)
                    <li><a href=" {{ URL::to('results') }}/{{ $bookmark->url }}" style="color:#fff; font-size:16px;">{{{ $bookmark->name }}}</a> -- {{{ $bookmark->timestamp }}} (EST) -- 
                        <a href="#" id="{{{ $bookmark->id }}}" title="{{{ $bookmark->name }}}" class="label label-inverse bookmark-del">X</a>
                    </li>
                    <br>
                @endforeach
                </ul>
            @endif
    	</div>
    </div>
</div>

@include('components/query-form')
@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {

    $(".bookmark-del").click(function(event) {
        event.preventDefault();

        var deleteBookmark = confirm("Are you sure you want to delete '" + $(this).attr("title") + "' ?");
        if (deleteBookmark) {
            $.ajax({
                type: "POST",
                url: "{{ route('delete-bookmark') }}",
                data: {
                    bookmarkID: $(this).attr("id")
                }
            })
            .done(function(msg) {
                var currentPage = getParameterByName("page");
                var currentBookmarksPageCount = $('.bookmark-del').length;

                var baseURL = "{{ route('get-bookmarks') }}";
                var prevPage = currentPage - 1;
                
                if (currentBookmarksPageCount == 1) {
                    if (currentPage > 1) {
                        window.location = baseURL + "?page=" + prevPage;
                    }
                    else {
                        location.reload();
                    }
                }
                else {
                    location.reload();
                }
            });
        }
    });
});
</script>
</body>
</html>