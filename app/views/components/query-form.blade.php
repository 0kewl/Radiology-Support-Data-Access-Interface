<!-- Query form -->
<form id="search-form" name="search-form" class="form-inline" action="{{ route('results') }}" method="get" autocomplete="off">
    <input name="q" id="q" type="hidden" value="" />
    <input name="start" id="start" type="hidden" value="" />
    <input id="main-query" name="main-query" type="hidden">
    <button id="search" style="display:none;"></button>
</form>
<!-- END Query form -->