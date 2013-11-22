<!-- Search form template -->
<fieldset>
    <!-- Text input-->
    <div class="control-group">
        <div class="controls">
            @if (isset($response))
                <input id="main-query" name="main-query" class="input-block-level" type="text" value="{{ $response->query }}" autocomplete="off">
            @else
                <input id="main-query" name="main-query" class="input-block-level" type="text" autocomplete="off">
            @endif
        </div>
    </div>
</fieldset>

<!-- Query form -->
<form id="search-form" name="search-form" class="form-inline" action="{{ route('results') }}" method="get">
    <input name="q" id="q" type="hidden" value="" />
    <input name="start" id="start" type="hidden" value="" />
<!-- END Query form -->

    <div style="margin:0 auto; text-align:center;">
        <input id="search" type="submit" class="btn btn-large btn-inverse" value="Search" />
    </div>  
</form>
<!-- END Search Form -->