function usernotified(element)
{
	var id=$(element).val();
	var request=$.ajax({
		url: "usernotified",
		type: "POST",
		data:{id:id}
	});
}


function processinvite(element, action)
{
	var id=$(element).parent().parent().find("input").val();
	var request=$.ajax({
		url: "processinvite",
		type: "POST",
		data: {id:id,action:action}
	});

	$(element).parent().parent().fadeOut();
}