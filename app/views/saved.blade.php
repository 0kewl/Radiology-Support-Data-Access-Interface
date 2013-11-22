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
    </style>
<!-- Page wrapper -->
<div class="container">
    @include('components/menu')
    <div class="row-fluid">
    	<div class="span12 shadow" style="height: 800px; overflow-y: hidden;">
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