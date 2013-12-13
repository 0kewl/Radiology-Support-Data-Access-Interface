<!-- Common footer across all pages-->
<footer>
	<!-- JavaScript Imports -->
    <script src="{{asset('assets/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('assets/js/spin.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.tooltipster.min.js')}}"></script>
    <script src="{{asset('assets/js/common.js')}}"></script>

<div class="navbar header-footer" style="margin-top:12px; margin-bottom:0px">
	<div class="navbar-inner">
		<div>
			<div style="margin-top:5px; margin-right:70px; float:right">
				<button id="view-documentation-btn" class="btn btn-small btn-inverse" type="button">View Documentation</button>
			</div>
		</div>
	</div>
</div>
</footer>
<script type="text/javascript">
	$(document).ready(function() {
	    $("#view-documentation-btn").popover({
	        placement: 'top',
	        html: 'true',
	        title : '<u>Documentation</u>'
	    });
	    var documentationPopover = '<div style="width:224px"><div style="width:196px"><a href="{{ asset("assets/doc/Radiology_Support_Data_Access_Interface_Documentation.pdf") }}" target="_blank" class="btn" style="width:100%">View Documentation PDF</a></div>' +
	    						   '<div style="width:196px"><a href="{{ asset("assets/doc/Radiology_Support_Data_Access_Interface_Programmers_Guide.pdf") }}" target="_blank" class="btn" style="width:100%">View Programmer&#39;s Guide PDF</a></div></div>';

	    $('#view-documentation-btn').attr('data-content', documentationPopover);
	});
</script>

<!-- END footer -->