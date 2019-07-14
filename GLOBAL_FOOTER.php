</div>
<!-- /#wrapper -->
</body>
<div class="modal fade" id="modalAlert" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="alert alert-<?php echo $alertColor;?>">
            <strong><?php echo $alertMessage; ?></strong>
        </div>
    </div>
</div>
</html>
<script>
    let alertType = "<?php echo $alertType;?>";
    if(alertType !== ''){
        $('#modalAlert').modal('show');
        setTimeout(function(){
            $('#modalAlert').modal('hide');
        }, 3000);
    }
</script>