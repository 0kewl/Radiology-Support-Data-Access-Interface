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
                <div id="case-information" class="span4 shadow" style="background-color:#fff; padding:12px; height: 800px; overflow-y: auto;">
                    <h3 class="text-center">Case Information</h3>
                    <br>
                    <div id="case-information-container" style="height:700px; overflow-y: auto;">
                        @if (!$results)
                            <div class="alert alert-info">
                                <h5 class="text-center">Your search did not match any documents.</h5>
                            </div>
                        @else       
                            {{ $results }}
                        @endif  
                    </div>
                </div>
                
                <div id="similar-cases" class="span8 shadow" style="background-color:#fff; padding:12px; height: 800px;">
                    <h3 class="text-center">Similar Cases</h3>
                    <div id="results-container" class="span4" style="float:left; height: 650px; overflow-y: auto; overflow-x: hidden;">
                    </div>
                    <div id="document-viewer" class="span7" style="float:left; height: 650px; overflow-y: auto; overflow-x: hidden;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- jQuery / JavaScript Code -->
<script type="text/javascript">
$(document).ready(function() {
    // code here
});
</script>

</body>
</html>