<!DOCTYPE html>
<html>
@include('components/header')
<body>
<!-- CSS -->
    <style>
		.container {
			width: auto;
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
                <li><a href=" {{ URL::to('results') }}/{{ $bookmark[1] }}" style="color:#fff;">{{ $bookmark[0] }}</a> -- {{ $bookmark[2] }} (EST) </li>
                <br>
            @endforeach
            </ul>
    	</div>
    </div>
</div>

<!-- Query form -->
<form id="search-form" name="search-form" class="form-inline" action="{{ route('results') }}" method="get">
    <input name="q" id="q" type="hidden" value="" />
    <input name="start" id="start" type="hidden" value="" />

    <input id="main-query" name="main-query" type="hidden">
    <button id="search" style="display:none;"></button>
</form>
<!-- END Query form -->
@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {

});
</script>
</body>
</html>