<script>
$('.modal').modal('hide');
$("#modalErrorAjax .modal-title").html("<i class=\"fa fa-ban\"></i> Error 403 : isAuthorized");
$('#modalErrorAjax .modal-body').html("<h2>Acceso denegado/prohibido</h2>" + 
	"<p class=\"error\"><strong>Error: </strong><?php echo $message;?></p>" + 
	"<pre><br>Sí el problema persiste, consulta con el administrador de la plataforma<br><br></pre>"
);
$('#modalErrorAjax .modal-footer').html("<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\"><?php echo __('Close');?></button>");
$('#modalErrorAjax').modal('show');
</script>