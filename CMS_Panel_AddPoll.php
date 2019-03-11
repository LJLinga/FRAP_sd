<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:31 PM
 */

?>

    <!-- Panel -->
    <div class="card">
        <div class="card-header"><i class="fa fa-fw fa-tasks"></i> Create Polls</div>
        <div class="card-body">
            <div class="form-group">
                <label>Type of Poll</label>
                <select class="form-control">
                    <option>Single Response</option>
                    <option>Multiple Response</option>
                </select>
            </div>
            <label>Question</label>
            <input type="text" class="form-control input-md" placeholder="Ask a question">
                <span id="fieldLocation"></span>
                <!-- <button class="btn btn-default addField" id="addField"><i class="fa fa-fw fa-plus"></i></button>-->
                <div class="row">
                    <div class="col-lg-2">
                        <h4><i class="fa fa-fw fa-plus-circle addField" style="cursor: pointer; "></i></h4>
                    </div>
                    <div class="col-lg-10">
                        <input type="text" class="form-control input-md option-input" placeholder="Add an answer">
                    </div>
                </div>
        </div>
    </div>


        <script>
            $(document).ready( function(){
                $('.addField').on('click', function(){
                    $('#fieldLocation').append('<div class="row"><div class="col-lg-2"><h4><i class="fa fa-fw fa-minus-circle removeField" style="cursor: pointer; "></i></h4></div><div class="col-lg-10"><input type="text" class="form-control input-md option-input" placeholder="Add an answer"></div> </div>');
                    $('.addField').attr('disabled','disabled');
                    $('.removeField').on('click', function(){
                        $(this).closest('.row').remove();
                    });
                });
                $('.option-input').on('change', function(){
                    $('.addField').removeAttr('disabled');
                });



            });

        </script>
