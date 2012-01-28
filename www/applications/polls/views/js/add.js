$("#addImg").bind('click', {field: $(".panswer:last"), content: $("#answers")}, add);

function add(e) {
	if(e.data.content.find("span").length < 5) {
		var fieldClone = e.data.field.clone();
		
		fieldClone.hide();
		fieldClone.find("span").text((e.data.content.find("span").length + 1) + ".-");
		fieldClone.find("input[type=text]").val("");
		
		e.data.content.append(fieldClone);
		fieldClone.show(300);
	}
}
