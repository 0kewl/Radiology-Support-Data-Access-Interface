<!DOCTYPE html>
<html>
@include('components/header')
<body>
    <!-- CSS -->
    <style>
        .container {
            width: 1550px;
        }
    </style>
<div class="container" style="margin-top: 22px;">
    @include('components/menu')
    <div class="row-fluid">
    	<div class="span12">
    		<div class="row-fluid">
    			<div class="span4 shadow" style="background-color:#fff; padding:12px; height: 800px; overflow-y: auto;">
    				<h3 class="text-center">Case Information</h3>
    				<br>
					@if (!$results)
                            <br>
                            <div class="alert alert-info">
                                <h5 class="text-center">Your search did not match any documents.</h5>
                            </div>
					@else		
						{{ $results }}
					@endif	
    			</div>

 
    		</div>
    	</div>
    </div>
</div>

 <!-- jQuery / JavaScript Code -->
<script type="text/javascript">
$(document).ready(function() {
	
}
</script>

</body>
</html>