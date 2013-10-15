<!-- Search form template -->
<form id="search-form" name="search-form" class="form-inline" action="results" method="post" enctype="multipart/form-data">
    <fieldset>
        <!-- Text input-->
        <div class="control-group">
            <div class="controls">
                @if (isset($response))
                    <input id="main-query" name="main-query" placeholder="Search Query" class="input-block-level" type="text" value="{{ $response->query }}">
                @else
                    <input id="main-query" name="main-query" placeholder="Search Query" class="input-block-level" type="text">
                @endif
            </div>
        </div>
        <br>
        <input id="json" name="json" type="hidden">
        <div id="keywords-container">
            <div id="additional-keywords" class="additional-keywords">
                <!-- Dropdown Select-->
                <div class="control-group">
                    <div class="controls">
                        {{ Form::select('operator', $operators, '', array('id' => 'operator','class' => 'operator input-small')) }}
                        {{ Form::select('field', array('' => '- Field -') + $keywords, 'default', array('id' => 'field','class' => 'field input-medium')) }}
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input id="keyword" name="keyword" placeholder="Keyword" class="keyword input-xlarge additional-keyword" type="text">
                    </div>
                </div>
            </div>
        </div>
        <button id="add-field" class="btn btn-small" type="button">+ Add Field</button>
    </fieldset>
</form>
<div style="margin:0 auto; text-align:center;">
    <button id="search" class="btn btn-large">Search</button>
</div>