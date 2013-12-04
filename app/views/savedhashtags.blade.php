<!DOCTYPE html>
<html>
@include('components/header')
<body>
<!-- CSS -->
    <style>
		.container {
			width: 1322;
			height: auto; 
		}
        .fancy {
            text-align: center;
            color: #eee;
            font-size: 20px;
            text-shadow: 2px 3px 3px #292929;
        }
    </style>
<!-- Page wrapper -->
<div class="container">
    @include('components/menu')
    <div class="row-fluid">
        <div class="span12 shadow" style="height: 800px; overflow-y: hidden;">
            @if ($hashtags->isEmpty())
                <div class="well">
                    <p class="fancy">No Hashtags Found</p>
                </div>
            @else
                {{ $hashtags->links() }}
                <ul>
                @foreach($hashtags AS $hashtag)
                    <li> #{{{ $hashtag->tag }}}</li>
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

        var deleteBookmark = confirm("Delete '" + $(this).attr("title") + "' bookmark?");
        if (deleteBookmark) {
            $.ajax({
                type: "POST",
                url: "{{ route('delete-bookmark') }}",
                data: {
                    bookmarkID: $(this).attr("id")
                }
            })
            .done(function(msg) {
                location.reload();
            });
        }
    });
});
</script>
</body>
</html>