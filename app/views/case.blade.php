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
        .result-snippet {
            height: 60px;
        }
    </style>
<!-- Page wrapper -->
<div class="container">
    @include('components/menu')
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <!-- Case document -->
                <div id="case-information" class="span4 shadow" style="color:#fff; height: 800px; overflow-y: hidden;">
                    <h3 class="text-center">Case Information</h3>
                    <br>
                    <div id="case-information-container" style="font-size: 18px; height:700px; overflow-y: auto;">
                        @if (!$doc)
                            <div class="well">
                                <h5 class="text-center">Case ID {{{ $caseID }}} not found</h5>
                            </div>
                        @else
                            {{ $doc }}
                        @endif  
                    </div>
                </div>
                <!-- END case document -->
                
                @include('components/results-viewer')
            </div>
        </div>
    </div>
</div>

@include('components/query-form')
@include('components/case-lookup-form')
@include('components/footer')

<script type="text/javascript">
$(document).ready(function() {
    /**
     * Creates an add hashtag popover
     * @param placement the location of the popover
     * @param html allow html
     * @return title the title of the popover
     */
    $(".add-hashtag").popover({
        placement: 'top',
        html: 'true',
        title : 'Case Hashtags'
    });

    var hashtagPopover = '<strong>Add Hashtags</strong><br><small>Comma separate multiple tags</small><br><br>' +
                         '<input type="text" id="hashtag-input"><br><button id="add-hash"class="btn btn-inverse btn-small" onClick="addHashtag($(this).parents(&quot;.result-snippet&quot;).attr(&quot;id&quot;),$(&quot;#hashtag-input&quot;).val());">Add</button>' +
                         '<button type="button" id="close" class="btn btn-small btn-inverse" onclick="$(&quot;.add-hashtag&quot;).popover(&quot;hide&quot;);">Cancel</button>';

    $('.add-hashtag').attr('data-content', hashtagPopover);

});

$('.add-hashtag').on('click', function(e) {e.preventDefault(); e.stopPropagation(); return true;});

</script>

@include('components/hashtag')

</body>
</html>
