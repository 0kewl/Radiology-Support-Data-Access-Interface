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
    @include('components/menu')
    <div class="row-fluid">
        <div class="span12 shadow" style="height:600px; overflow-y:auto;">
            @if ($hashtags->isEmpty())
                <div class="well">
                    <p class="fancy">No Hashtags Found</p>
                </div>
            @else
                <div id="hashtag-pages">
                    <div class="well">
                        <p class="fancy" style="text-align:left;">Total hashtags: {{{ $count }}}</p>
                        {{ $hashtags->links() }}
                    </div>
                </div>
                <ul>
                @foreach($hashtags AS $hashtag)
                    <li><a href="#" id="{{{ $hashtag->tag }}}" class="hashtag" style="color:#fff;">#{{{ $hashtag->tag }}}</a></li>
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

    $(".hashtag").click(function(event) {
        event.preventDefault();
        $("#hashtag").val($(this).attr('id'));
        $("#hashtag-search-btn").click();
    });
});
</script>
</body>
</html>