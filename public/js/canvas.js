function interactions()
{
	// Makes Note Draggable
	$(".note").draggable({
 		containment: "parent",
 		handle: "div.panel-heading",
	  	opacity: 0.5,
	  	distance: 30,
	  	cursor: "move",
	  	stack: ".note",
	  	stop: function( eve, u ) 
		{
		 	var id=$(this).children("input.post_id").val();
    		var left=u.position.left;
    		var top=u.position.top;
    		var zindex=$(this).zIndex();

    		var request=$.ajax({
		 		url: "updatepost",
		 		type: "POST",
		 		data: {'id':id, 'left':left, 'top':top, 'zindex': zindex}
	 		});
  		}
 	});

	// Make Note Resizable
 	$(".note").resizable({
 		containment: "parent",
 		minHeight:100,
 		minWidth:200,
 		resize: function(eve, u)
	  	{
	  		var width=u.size.width;
 			var height=u.size.height;

 			$(this).children("div.content").css("height",height-15);

	  	},
 		stop: function(eve,u)
 		{
 			var id=$(this).children("input.post_id").val();
 			var width=u.size.width;
 			var height=u.size.height;

 			$(this).children("div.content").css("height",height-15);

 			var request=$.ajax({
 				url: "updatepost",
 				type:"POST",
 				data: {'id':id,'width':width,'height':height}
 			});
 		}
 	});
}

function editpost(element)
{
	var id=$(element).parent().parent().parent().parent().find("input").val();

	var request=$.ajax({
		url: "getpost",
		type: "POST",
		data:{'id':id},
		success: function(data)
		{
			var obj=$.parseJSON(data);
			$("#editnote").find("#post_id").val(obj.id);
			$("#editnote").find("#title").val(obj.title);
			$("#editnote").find("#content").val(obj.content);

			$("#editnote").modal();
		}
	});

	loadposts();
}

function loadposts()
{
	var id=$("#group_id").val();

	var request=$.ajax({
		url: "loadposts",
		type: "POST",
		data:{'id':id},
		success: function(data)
		{
			obj=$.parseJSON(data);
			if(obj.status="success")
			$(".workspace").html(obj.message);

			interactions();
		}
	});
}


$(function(){
	interactions();
});