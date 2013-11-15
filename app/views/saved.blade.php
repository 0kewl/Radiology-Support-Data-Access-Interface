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
			
    	</div>
    </div>
</div>

@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {

});
</script>
@include('components/hashtag')
</body>
</html>