<!DOCTYPE html>
<html>
<head>
    <title>CS-470 Project</title>
    <!-- CSS Imports -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" type="text/css" />

    <!-- JavaScript Imports -->
    <script src="{{asset('assets/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

    <!-- CSS -->
    <style>
    .shadow {
    -webkit-box-shadow: 0 4px 2px -2px #808080;
       -moz-box-shadow: 0 4px 2px -2px #808080;
            box-shadow: 0 4px 2px -2px #808080;
    }
    </style>
</head>
<body style="background-color:#E7E7E7;">

    <div class="container" style="padding-top:20px;">
        <div class="row-fluid" style="padding-bottom:50px;">
            <h2 class="text-center">Radiology Support Data Access Interface</h2>
        </div>

        <div class="row-fluid">
            <div class="span7 shadow" style="background-color:#fff; padding:12px; height:650px;">
                <h3 class="text-center">Case Search</h3>
                <br>
                <form class="form-inline" action="results" method="post">
                    <fieldset>
                    <!-- Text input-->
                    <div class="control-group">
                      <div class="controls">
                        <input id="main-keywords" name="keywords" placeholder="Search Keywords" class="input-block-level" type="text">
                      </div>
                    </div>

                    <br>

                    <div id="keywords-container">
                        <div id="additional-keywords">
                            <!-- Dropdown Select-->
                            <div class="control-group">
                              <div class="controls">
                                {{ Form::select('operator', $operators, 'default', array('class' => 'input-small')) }}
                                {{ Form::select('field', array('default' => '-- Select Field --') + $keywords, 'default', array('class' => 'input-medium')) }}
                                 <input name="keywords" placeholder="Additional Keywords" class="input-large additional-keyword" type="text">
                              </div>
                            </div>
                        </div>
                    </div>

                    <button id="add-field" class="btn btn-small" type="button">+ Add Field</button>

                    <div style="margin:0 auto; text-align:center;">
                        <button class="btn btn-large" type="submit">Search</button>
                    </div>

                    </fieldset>
                    </form>

            </div>
    
            <div class="span5 shadow" style="background-color:#fff; padding:12px; height:650px;">
                <h3 class="text-center">Case Information</h3>   
                <br>
                <form class="form-inline">
                    <fieldset>
                    <!-- Text input-->
                    <div class="control-group">
                      <div class="controls">
                        <input id="case-id" name="case-id" placeholder="Enter Case ID" class="input-block-level" type="text">
                      </div>
                    </div>

                    <br>

                    <div style="margin:0 auto; text-align:center;">
                        <button class="btn btn-large" type="button">Auto-Populate Fields &amp; Search</button>
                    </div>

                    </fieldset>
                    </form>             
            </div>
        </div>
    </div>

    <!-- jQuery / JavaScript Code -->
    <script type="text/javascript">
        $(document).ready(function() {

            addKeywordFields(2);
        });

        $("#add-field").click(function() {
            if (!isKeywordsFull()) {
                addKeywordFields(1);
            }
        });

        /* Clones then creates a new keyword field */
        function addKeywordFields(qty) {
            for (var i=0; i<qty; i++) {
                $("#additional-keywords").clone(true).appendTo("#keywords-container");
            }
        }

        /* Test to determine if maximum search keywords is reached */
        function isKeywordsFull() {
            if ($('.additional-keyword').length >= 10) {
                return true;
            }
            return false;
        }
    </script>

</body>
</html>