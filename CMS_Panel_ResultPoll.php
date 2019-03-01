<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:31 PM
 */

?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="http://cdn.oesmith.co.uk/morris-0.4.1.min.js"></script>
    <!-- Panel -->
    <div class="card card-default">
        <div class="card-header"><i class="fa fa-fw fa-tasks"></i> Poll Results</div>
        <div class="card-body">
            <div id="poll-result" style="height: 250px;">

            </div>
        </div>
    </div>

    <script>
        $(document).ready( function(){

        });

        var donut =  Morris.Donut({
            element: 'poll-result',
            data: [
                {label: "Download Sales", value: 12},
                {label: "In-Store Sales", value: 30},
                {label: "Mail-Order Sales", value: 20}
            ],
        });

    </script>


