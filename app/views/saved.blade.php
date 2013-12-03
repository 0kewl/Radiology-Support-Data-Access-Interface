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
            -webkit-text-stroke: 1px white;
        }
    </style>
<!-- Page wrapper -->
<div class="container">
    @include('components/menu')
    <div class="row-fluid">
    	<div class="span12 shadow" style="height: 800px; overflow-y: hidden;">
            @if (empty($bookmarks))
                <div class="well">
                    <p class="fancy">No Bookmarks Found</p>
                </div>
            @endif
            <ul>
            @foreach($bookmarks AS $bookmark)
                <li><a href=" {{ URL::to('results') }}/{{ $bookmark[1] }}" style="color:#fff;">{{ $bookmark[0] }}</a> -- {{ $bookmark[2] }} (EST) -- 
                    <a href="#" id="{{ $bookmark[3] }}" class="label label-inverse">X</a>
                </li>
                <br>
            @endforeach
            </ul>
    	</div>
    </div>
</div>

@include('components/query-form')
@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {

});
</script>
</body>
</html>