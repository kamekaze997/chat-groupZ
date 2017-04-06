$(document).ready(function(){

	$("#invite-people").html(function(index,html){
		return '<i class="fa fa-plus" aria-hidden="true"></i> '+html;
	});
	$("#threads").html(function(index,html){
		return '<i class="fa fa-commenting-o" aria-hidden="true"></i> '+html;
	});

	$(".chat-group a").html(function(index,html){
		return '<i class="fa fa-slack" aria-hidden="true"></i> '+html;
	});
	
	$(".second-level a.offline").html(function(index,html){
		return '<i class="fa fa-circle-o" aria-hidden="true"></i> '+html;
	});	
	
	$(".second-level a.online,#chat-username.online").html(function(index,html){
		return '<i class="fa fa-circle" aria-hidden="true"></i> '+html;
	});
    $(".message").mouseover(function(e) {
    	$(this).find('.message-toolbar').addClass('active');
    });
    $(".message").mouseout(function(e) {
    	$(this).find('.message-toolbar').removeClass('active');
    });

    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
});