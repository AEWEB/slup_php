function select_changeDisplay(
	selectChange_obj,
	selectChange_obj_list,
	display_value
){
	select=selectChange_obj.options[selectChange_obj.selectedIndex].value;
	for(i=0;i<selectChange_obj_list.length;i++){
		if(selectChange_obj_list[i][0]==select){
			document.getElementById(selectChange_obj_list[i][1]).style["display"]=display_value;
		}else{
			document.getElementById(selectChange_obj_list[i][1]).style["display"]="none";
		}
	}
}
function isExamine_checkLength(str,first_num,endnum,name){
	if(str.length<first_num||str.length>endnum){
		return name+"は"+first_num+"文字以上、"+endnum+"文字以下で入力してください";
	}
	return null;
	
}
function isExamine_checkLength_noTitle(element,endnum,name,jpNotitle){
	var valLen=(jQuery(element).val()).length;
	if(valLen>endnum){
		return name+"は"+endnum+"文字以下で入力してください。";
	}else if(valLen<1){
		jQuery(element).val("無題");
	}
	return null;
}

function getCookie(key){
	var cookieString = document.cookie;
	var cookieKeyArray = cookieString.split(";");
	for (var i=0; i<cookieKeyArray.length; i++) {
		var targetCookie = cookieKeyArray[i];
		targetCookie = targetCookie.replace(/^\s+|\s+$/g, "");
		var valueIndex = targetCookie.indexOf("=");
		if (targetCookie.substring(0, valueIndex) == key) {
			return unescape(targetCookie.slice(valueIndex + 1));
		}
	}
	return null;
}
function setCookie(key,value){
	document.cookie = key+"="+value+";";
}
function isExamine_html5(){
	if(navigator.userAgent.indexOf("MSIE 8")!= -1
			||navigator.userAgent.indexOf("MSIE 7")!=-1
			||navigator.userAgent.indexOf("MSIE 6")!=-1){
		return true;
	}
	return false;
}
function displayOpen_close(element){
	var display_list={"block":"none","none":"block"};
	jQuery(element).css("display",
		display_list[jQuery(element).css("display")]);
}
function regTwitter(com){
	window.open('http://twitter.com/?status='+encodeURIComponent(com));
}
function open_display(element){
	list={"block":"none","none":"block"};
	jQuery(element).css("display",list[jQuery(element).css("display")]);
}
function analysisSex(){
	var man = $("#idSex_0").is(':checked');
	var woman = $("#idSex_1").is(':checked');
	if(man==true){
		return $("#idSex_0").val();
	}else if(woman==true){
		return $("#idSex_1").val();
	}
	return "noInput";
}
function analysisCheckBox(checkBoxList){//チェックボックスのチェック状況を解析して、二進数で返す
	var charaType="";
	for(i=0;i<checkBoxList.length;i++){
		//alert(checkBoxList[i]);
		if(jQuery("#"+checkBoxList[i]).attr('checked')){
			charaType="1"+charaType;
		}else{
			charaType="0"+charaType;
		}
	}
	return charaType;
}
function mouse_dragOn(index,value,event){
	event.dataTransfer.setData(index, value);	
}
function mouse_dragOver(event){
	event.preventDefault();
}