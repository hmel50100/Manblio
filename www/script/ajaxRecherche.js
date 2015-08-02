console.log('AjaxRecherche est chargé ');
$("#loading").hide();
$("documents").ready(function(){ 
	$('#resultats_recherche').hide();
	
	$(".recherche").keyup(function(){
		if ($(this).val().length >= 3){
			$('#site').hide();
				$.ajax({
	        		type: "POST",
	        		url: "/find/user/"+ $(".recherche").val(),
	        		beforeSend: function(){
	        			$("#loading").show();
	        		},
	        		success: function(data){
	        			$("#loading").hide();
	        			$('#resultats_recherche').show();
	        			$('#resultats_recherche').html(data);
	        		},
	        		
	    		});   
        }
        else{
        	$('#resultats_recherche').hide();
        	$('#site').show();
        	$("#loading").hide();
        }
	})
});