function sendinvite(element)
{
	var email=$(element).parent().next().val();
	$(element).parent().parent().find("input").val("");
	var group_id=$("#groupId").val();
	var request=$.ajax({
		url: "sendinvite",
		type: "POST",
		data: {'id':group_id,'email':email},
		success: function (data)
		{
			var obj=$.parseJSON(data);
			if(obj.status=="success")
			{
				$("#message").fadeIn();
				$("#message").children().html(obj.message);
			}
			else if(obj.status=="error")
			{
				$("#message").fadeIn();
				$("#message").children().html(obj.error);
			}
		}
	});
}

function deleteuser(element)
{
	var group_id=$("#groupId").val();
	var user_id=$(element).parent().parent().find("input").val();
	var request=$.ajax({
		url: "deleteuser",
		type: "POST",
		data: {'id':group_id,'user_id':user_id},
		success: function(data)
		{
			var obj=$.parseJSON(data);
			if(obj.status=="error")
			{
				$("#message").fadeIn();
				$("#message").children().html(obj.error);
			}
			else
				$(element).parent().parent().fadeOut();
		}
	});
}


function changerole(element)
{
	var group_id=$("#groupId").val();
	var user_id=$(element).parent().parent().find("input").val();
	var request=$.ajax({
		url: "changerole",
		type: "POST",
		data: {'id':group_id,'user_id':user_id},
		success:function(data)
		{
			location.reload();
		}
	});
}