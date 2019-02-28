<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:31 PM
 */

?>

    <!-- Panel -->
    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-fw fa-tasks"></i> Polls</div>
        <div class="panel-body">
            <label>Question</label>
            <input type="text" class="form-control input-md">
                <button class="btn btn-default addField" id="addField"><i class="fa fa-fw fa-plus"></i></button>
                <span id="fieldLocation"></span>
                <div class="row">
                    <div class="col-lg-2">
                        <input type="radio" class="form-control input-md">
                    </div>
                    <div class="col-lg-10">
                        <input type="text" class="form-control input-md option-input">
                    </div>
                </div>
        </div>
    </div>


        <script>
            $(document).ready( function(){
                $('.addField').on('click', function(){
                    $('#fieldLocation').append('<div class="row"><div class="col-lg-2"><input type="radio" class="form-control input-md"></div><div class="col-lg-10"><input type="text" class="form-control input-md option-input"></div> </div>');
                    $('.addField').attr('disabled','disabled');
                });
                $('.option-input').on('change', function(){
                    $('.addField').removeAttr('disabled');
                })

            });

            $('.option-input').on('change', function(){
                $('.addField').removeAttr('disabled');
            })

        </script>
