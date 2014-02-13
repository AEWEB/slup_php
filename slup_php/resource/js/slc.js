var ajax_flag=true;
var SLCAjax=function(){
	//override
	this.exeReturn=function(){
		ajax_flag=true;
		this.getAccess().hidePageLoading();
		this.getAccess().exeReturn(this.getElementName());
	}
	this.runError=function(){
		alert("予期せぬ動作が発生しました。ページを更新します。");
		document.location = error_url;
	}
	this.ajaxException=function(transport, ex){
	//	alert(ex);
		this.runError();
	}
	this.isExamine_exe=function(){//ajax処理が実行可能か
		if(ajax_flag){
			ajax_flag=false;
			this.getAccess().showPageLoading();
			return true;
		}
		return false;
	}
	this.getRunExe=function(){
		return "run_exe";
	}
	this.init=function(){
		this.setParamList({});
		this.setElementName("run_exe");
		this.exe();
	}
}
SLCAjax.prototype=new LfAjax(access);

var Option=function(){
	
	this.show=function(element,pager){
		this.setParamList({"ajaxProcess":"show",
			"securityKey":$("#securityKey").val(),
			"pager":pager,
			"element":element
		});
		this.setElementName(element);
		this.exe();
	}
	this.pushImage=function(id,element){
		this.setElementName(element+"_text");
		this.setParamList({"ajaxProcess":"getImageTag",
			"securityKey":$("#securityKey").val(),
			"id":id});
		this.exe();
	}
	
	//override
	//画面の書き換え処理
	this.output=function(data){
		var list=this.getParamList();
		if(list["ajaxProcess"]=="getImageTag"){
			$("#"+this.getElementName()).val($("#"+this.getElementName()).val()+data);
		}else{
			$("#"+this.getElementName()).html(data);
		}
		this.exeReturn();//レスポンス後処理を実行
	}
	this.getUrl=function(){//url of a request place 
		return global_path+"slup/option/ajax";
	}
}
Option.prototype=new SLCAjax();
var option=new Option();
var Problem=function(){
	this.showTrainingByLearning=function(learningId,view,mode){
		this.setParamList({"process":"showTrainingByLearning",
			"securityKey":$("#securityKey").val(),
			"learningId":learningId,
			"view":view,
			"mode":mode
		});
		this.setElementName(view);
		this.exe();
	}
	this.answerTraining=function(id){
		if($("#pro_answer").val()!=null){
			answer=$("#pro_answer").val();
		}else{
			answer=$("input[name='pro_answer']:checked").val();
		}
		this.setParamList({"process":"showAnswer",
			"securityKey":$("#securityKey").val(),
			"id":id,
			"answer":answer
		});
		this.setElementName("showTraining");
		this.exe();
	}
	this.initCreate=function(){
		this.setParamList({"process":"create"});
		this.setElementName("run_exe");
		this.exe();
	}
	this.create=function(){
		thisObj=this;
		jConfirm("登録します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"create",
					"securityKey":$("#securityKey").val(),
					"problemTitle":$("#pro_title").val(),
					"content":$("#contentImage_text").val(),
					"comment":$("#commentImage_text").val(),
					"answer":$("#pro_answer").val(),
					"exp":$("#pro_exp").val(),
					"requireExp":$("#pro_requireExp").val(),
					"slte_id":$("#pro_slte_id option:selected").val()
					});
				thisObj.setElementName("run_exe");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	this.show=function(pager,element){
		this.setParamList({"process":"show",
			"securityKey":$("#securityKey").val(),
			"pager":pager});
		this.setElementName(element);
		this.exe();
	}
	this.detail=function(id){
		this.setParamList({"process":"detail","id":id,"securityKey":$("#securityKey").val()});
		this.setElementName("run_exe");
		this.exe();
	}
	this.deleteData=function(id){
		thisObj=this;
		jConfirm("削除します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"delete","id":id,"securityKey":$("#securityKey").val()});
				thisObj.setElementName("sub_exe");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	
	this.initUpdate=function(id){
		this.setParamList({"process":"update","id":id});
		this.setElementName("run_exe");
		this.exe();
	}
	this.update=function(id){
		thisObj=this;
		if($("#pro_answer").val()!=null){
			answer=$("#pro_answer").val();
		}else{
			answer=$("input[name='pro_answer']:checked").val();
		}
		jConfirm("更新します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"update",
					"id":id,
					"securityKey":$("#securityKey").val(),
					"problemTitle":$("#pro_title").val(),
					"content":$("#contentImage_text").val(),
					"comment":$("#commentImage_text").val(),
					"answer":answer,
					"exp":$("#pro_exp").val(),
					"requireExp":$("#pro_requireExp").val(),
					"slte_id":$("#pro_slte_id option:selected").val()
					});
				thisObj.setElementName("run_exe");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	this.addAnswer=function(id){
		thisObj=this;
		jConfirm("追加します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"addAnswer","id":id,"securityKey":$("#securityKey").val(),"answer":$("#pro_add_answer").val()});
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	this.deleteAnswer=function(answerId,answer,problemId){
		thisObj=this;
		jConfirm("解答を削除します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"deleteAnswer","id":answerId,"securityKey":$("#securityKey").val(),
					"answer":answer,"problemId":problemId});
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	
	//override
	//画面の書き換え処理
	this.output=function(data){
		var list=this.getParamList();
		if(list["process"]=="addAnswer"){
			ajax_flag=true;
			this.detail(list["id"]);
			return;
		}else if(list["process"]=="deleteAnswer"){
			ajax_flag=true;
			this.detail(list["problemId"]);
			return;
		}else{
			$("#"+this.getElementName()).html(data);
		}
		this.exeReturn();//レスポンス後処理を実行
	}
	
	this.getUrl=function(){//url of a request place 
		return global_path+"slup/problem/";
	}
}
Problem.prototype=new SLCAjax();
var problem=new Problem();
var Partner=function(){
		//ajax access
		this.init=function(){
			this.setParamList({});
			this.setElementName("sub_exe");
			this.exe();
		}
		//Initialization
		this.initCreate=function(){
			this.setParamList({"process":"create"});
			this.setElementName("sub_exe");
			this.exe();
		}
		//Registration processing
		this.create=function(){
			thisObj=this;
			jConfirm("登録します。よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"create",
						"securityKey":$("#securityKey").val(),
						"name":$("#pa_name").val(),
						"type":$("input[name='pa_type']:checked").val(),
						"relation":$("#pa_relation option:selected").val(),
						"memo":$("#pa_memo").val()});
					thisObj.setElementName("sub_exe");
					thisObj.exe();
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		this.show=function(page){
			this.setParamList({"page":page});
			this.setElementName("partnerPage");
			this.exe();
		}
		
		//Initialization
		this.initEdit=function(id){
			this.setParamList({"process":"edit","id":id});
			this.setElementName("sub_exe");
			this.exe();
		}
		this.edit=function(id){
			thisObj=this;
			jConfirm("更新します。よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"edit",
						"id":id,
						"securityKey":$("#securityKey").val(),
						"name":$("#pa_name").val(),
						"type":$("input[name='pa_type']:checked").val(),
						"relation":$("#pa_relation option:selected").val(),
						"memo":$("#pa_memo").val()});
					thisObj.setElementName("sub_exe");
					thisObj.exe();
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		this.deletePartner=function(id){
			thisObj=this;
			jConfirm("パートナーに関する情報が全て削除されます。<br/>よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"delete",
						"id":id,
						"securityKey":$("#securityKey").val()});
					thisObj.exe();
					thisObj.setElementName("sub_exe");
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		
	//override
		this.getUrl=function(){//url of a request place 
			return global_path+"slup/partner/";
		}
}
Partner.prototype=new SLCAjax();
var partner=new Partner();

var Task=function(){
	//ajax access
	this.init=function(selectMode,selectMode_prder){
		this.setParamList({"process":"show","selectMode":selectMode,"selectMode_order":selectMode_prder});
		this.setElementName("run_exe");
		this.exe();
	}
	this.show=function(page,method,selectMode,selectMode_prder){
		this.setParamList({"process":method,"page":page,"selectMode":selectMode,"selectMode_order":selectMode_prder});
		this.setElementName("taskPage");
		this.exe();
	}
	//Initialization
	this.initCreate=function(){
		this.setParamList({"process":"create"});
		this.setElementName("sub_exe");
		this.exe();
	}
	//Registration processing
	this.runCreate=function(){
		thisObj=this;
		jConfirm("登録します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"create",
					"securityKey":$("#securityKey").val(),
					"name":$("#ta_name").val(),
					"purpose":$("#ta_purpose").val(),
					"state":$("#ta_state option:selected").val(),
					"priority":$("#ta_priority option:selected").val(),
					"weather":$("#ta_weather option:selected").val(),
					"security":$("#ta_security option:selected").val(),
					"startYear":$("#startYear option:selected").val(),
					"startMonth":$("#startMonth option:selected").val(),
					"startDate":$("#startDate option:selected").val(),
					"startHour":$("#startHour option:selected").val(),
					"startMinute":$("#startMinute option:selected").val(),
					"endYear":$("#endYear option:selected").val(),
					"endMonth":$("#endMonth option:selected").val(),
					"endDate":$("#endDate option:selected").val(),
					"endHour":$("#endHour option:selected").val(),
					"endMinute":$("#endMinute option:selected").val(),
					"pa_id":$("#ta_pa_id option:selected").val(),
					"memo":$("#ta_memo").val()			
				});
				thisObj.setElementName("sub_exe");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	this.detail=function(id,detail,type){
		if(type==1){
			negotiator.detail(id, detail);
		}else{
			this.setParamList({"process":detail,
				"id":id,"securityKey":$("#securityKey").val()});
			this.setElementName("sub_exe");
			this.exe();
		}		
	}
	this.initEdit=function(id){
		this.setParamList({"process":"edit","id":id});
		this.setElementName("sub_exe");
		this.exe();
	}
	this.edit=function(id){
		thisObj=this;
		jConfirm("更新します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"edit",
					"id":id,
					"securityKey":$("#securityKey").val(),
					"name":$("#ta_name").val(),
					"purpose":$("#ta_purpose").val(),
					"state":$("#ta_state option:selected").val(),
					"priority":$("#ta_priority option:selected").val(),
					"weather":$("#ta_weather option:selected").val(),
					"security":$("#ta_security option:selected").val(),
					"startYear":$("#startYear option:selected").val(),
					"startMonth":$("#startMonth option:selected").val(),
					"startDate":$("#startDate option:selected").val(),
					"startHour":$("#startHour option:selected").val(),
					"startMinute":$("#startMinute option:selected").val(),
					"endYear":$("#endYear option:selected").val(),
					"endMonth":$("#endMonth option:selected").val(),
					"endDate":$("#endDate option:selected").val(),
					"endHour":$("#endHour option:selected").val(),
					"endMinute":$("#endMinute option:selected").val(),
					"pa_id":$("#ta_pa_id option:selected").val(),
					"memo":$("#ta_memo").val(),
					"contact":$("#ne_contact option:selected").val(),
					"needs":$("#ne_needs").val(),
					"strong":$("#ne_strong").val(),
					"weak":$("#ne_weak").val(),
					"partner_strong":$("#ne_partner_strong").val(),
					"partner_weak":$("#ne_partner_weak").val(),
					"maximum":$("#ne_maximum").val(),
					"goal":$("#ne_goal").val(),
					"bottom_line":$("#ne_bottom_line").val(),
					"result":$("#ne_result option:selected").val()						
				});
				thisObj.setElementName("sub_exe");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	this.runDelete=function(id){
		thisObj=this;
		jConfirm("削除します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"delete",
					"id":id,"securityKey":$("#securityKey").val()});
				thisObj.setElementName("run_exe");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	this.selectType=function(){
		this.setParamList({"process":"selectPurpose","purposeType":$("#nes_purpose option:selected").val()});
		this.setElementName("tecExe");
		this.exe();
	}
	this.createStory=function(id){
		thisObj=this;
		jConfirm("登録します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"createStory",
					"storyId":id,
					"securityKey":$("#securityKey").val(),
					"who":$("input[name='nes_who']:checked").val(),
					"storyMemo":$("#nes_memo").val(),
					"storyResult":$("input[name='nes_result']:checked").val(),
					"time":$("#nes_time option:selected").val(),
					"storyPurpose":$("input[name='nes_tec']:checked").val()	
				});
				thisObj.setElementName("storyNew");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	this.initUpdateStory=function(id,storyId){
		this.setParamList({"process":"updateStory","id":id,"storyId":storyId,
			"storyPurpose":"1"});
		this.setElementName("storyNew");
		this.exe();
	}
	
	this.updateStory=function(id,storyId){
		thisObj=this;
		jConfirm("更新します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"updateStory",
					"id":id,
					"storyId":storyId,
					"securityKey":$("#securityKey").val(),
					"who":$("input[name='nes_who']:checked").val(),
					"storyMemo":$("#nes_memo").val(),
					"storyResult":$("input[name='nes_result']:checked").val(),
					"time":$("#nes_time option:selected").val(),
					"storyPurpose":$("input[name='nes_tec']:checked").val()	
				});
				thisObj.setElementName("storyNew");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	this.deleteStory=function(id,storyId){
		thisObj=this;
		jConfirm("削除します。よろしいですか？",
			"確認",function(d) {
			if(d){
				thisObj.setParamList({"process":"deleteStory",
					"id":id,
					"storyId":storyId,
					"securityKey":$("#securityKey").val()
				});
				thisObj.setElementName("storyNew");
				thisObj.exe();
			}else{
				jAlert("キャンセルしました。");
			}
		});	
	}
	
	this.nextShowStory=function(taskId,pager){
		this.setParamList({"process":"nextShowStory","id":taskId,"pager":pager,"securityKey":$("#securityKey").val()});
		this.setElementName("storyList");
		this.exe();
	}
	this.nextShowStoryExe=function(taskId,pager){
		this.setParamList({"process":"nextShowStoryExe","id":taskId,"pager":pager,"securityKey":$("#securityKey").val()});
		this.setElementName("storyList");
		this.exe();
	}
	this.storyDrop=function(event,id,dropId) {
		if(dropId==(dragId=event.dataTransfer.getData("storyId"))){
		}else{
			this.setParamList({"process":"replaceStory",
	  			"id":id,
	  			"securityKey":$("#securityKey").val(),
	  			"storyId":dragId,
	  			"replaceId":dropId});
	  		this.setElementName("storyNew");
	  		this.exe();
		}
	}
	
	
	this.showBackLog=function(showType){
		this.setParamList({"process":"showBackLog",
			"showType":showType});
		this.setElementName(this.getRunExe());
		this.exe();
	}
	this.showBackLogPage=function(page){
		this.setParamList({"process":"showBackLog","page":page});
		this.setElementName("taskPage");
		this.exe();
	}
	this.getUrl=function(){//url of a request place 
		return global_path+"slup/task/";
	}	
}
Task.prototype=new SLCAjax();
var task=new Task();

var Negotiator=function(){
	//ajax access
		this.init=function(){
			this.setParamList({"process":"show"});
			this.setElementName("run_exe");
			this.exe();
		}
		this.show=function(page,method){
			this.setParamList({"process":method,"page":page});
			this.setElementName("negotiatorPage");
			this.exe();
		}
		//Initialization
		this.initCreate=function(){
			this.setParamList({"process":"create"});
			this.setElementName("sub_exe");
			this.exe();
		}
		//Registration processing
		this.runCreate=function(){
			thisObj=this;
			jConfirm("登録します。よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"create",
						"securityKey":$("#securityKey").val(),
						"name":$("#ta_name").val(),
						"purpose":$("#ta_purpose").val(),
						"state":$("#ta_state option:selected").val(),
						"priority":$("#ta_priority option:selected").val(),
						"weather":$("#ta_weather option:selected").val(),
						"security":$("#ta_security option:selected").val(),
						"startYear":$("#startYear option:selected").val(),
						"startMonth":$("#startMonth option:selected").val(),
						"startDate":$("#startDate option:selected").val(),
						"startHour":$("#startHour option:selected").val(),
						"startMinute":$("#startMinute option:selected").val(),
						"endYear":$("#endYear option:selected").val(),
						"endMonth":$("#endMonth option:selected").val(),
						"endDate":$("#endDate option:selected").val(),
						"endHour":$("#endHour option:selected").val(),
						"endMinute":$("#endMinute option:selected").val(),
						"pa_id":$("#ta_pa_id option:selected").val(),
						"memo":$("#ta_memo").val(),
						"contact":$("#ne_contact option:selected").val(),
						"needs":$("#ne_needs").val(),
						"strong":$("#ne_strong").val(),
						"weak":$("#ne_weak").val(),
						"partner_strong":$("#ne_partner_strong").val(),
						"partner_weak":$("#ne_partner_weak").val(),
						"maximum":$("#ne_maximum").val(),
						"goal":$("#ne_goal").val(),
						"bottom_line":$("#ne_bottom_line").val(),
						"result":$("#ne_result option:selected").val()						
					});
					thisObj.setElementName("sub_exe");
					thisObj.exe();
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		this.detail=function(id,detail){
			this.setParamList({"process":detail,
				"id":id,"securityKey":$("#securityKey").val()});
			this.setElementName("sub_exe");
			this.exe();
		}
		this.initEdit=function(id){
			this.setParamList({"process":"edit","id":id});
			this.setElementName("sub_exe");
			this.exe();
		}
		this.edit=function(id){
			thisObj=this;
			jConfirm("更新します。よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"edit",
						"id":id,
						"securityKey":$("#securityKey").val(),
						"name":$("#ta_name").val(),
						"purpose":$("#ta_purpose").val(),
						"state":$("#ta_state option:selected").val(),
						"priority":$("#ta_priority option:selected").val(),
						"weather":$("#ta_weather option:selected").val(),
						"security":$("#ta_security option:selected").val(),
						"startYear":$("#startYear option:selected").val(),
						"startMonth":$("#startMonth option:selected").val(),
						"startDate":$("#startDate option:selected").val(),
						"startHour":$("#startHour option:selected").val(),
						"startMinute":$("#startMinute option:selected").val(),
						"endYear":$("#endYear option:selected").val(),
						"endMonth":$("#endMonth option:selected").val(),
						"endDate":$("#endDate option:selected").val(),
						"endHour":$("#endHour option:selected").val(),
						"endMinute":$("#endMinute option:selected").val(),
						"pa_id":$("#ta_pa_id option:selected").val(),
						"memo":$("#ta_memo").val(),
						"contact":$("#ne_contact option:selected").val(),
						"needs":$("#ne_needs").val(),
						"strong":$("#ne_strong").val(),
						"weak":$("#ne_weak").val(),
						"partner_strong":$("#ne_partner_strong").val(),
						"partner_weak":$("#ne_partner_weak").val(),
						"maximum":$("#ne_maximum").val(),
						"goal":$("#ne_goal").val(),
						"bottom_line":$("#ne_bottom_line").val(),
						"result":$("#ne_result option:selected").val()						
					});
					thisObj.setElementName("sub_exe");
					thisObj.exe();
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		this.runDelete=function(id){
			thisObj=this;
			jConfirm("削除します。よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"delete",
						"id":id,"securityKey":$("#securityKey").val()});
					thisObj.setElementName("run_exe");
					thisObj.exe();
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		this.selectType=function(){
			this.setParamList({"process":"selectPurpose","purposeType":$("#nes_purpose option:selected").val()});
			this.setElementName("tecExe");
			this.exe();
		}
		this.createStory=function(id){
			thisObj=this;
			jConfirm("登録します。よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"createStory",
						"storyId":id,
						"securityKey":$("#securityKey").val(),
						"who":$("input[name='nes_who']:checked").val(),
						"storyMemo":$("#nes_memo").val(),
						"storyResult":$("input[name='nes_result']:checked").val(),
						"time":$("#nes_time option:selected").val(),
						"storyPurpose":$("input[name='nes_tec']:checked").val()	
					});
					thisObj.setElementName("storyNew");
					thisObj.exe();
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		this.initUpdateStory=function(id,storyId){
			this.setParamList({"process":"updateStory","id":id,"storyId":storyId,
				"storyPurpose":"1"});
			this.setElementName("storyNew");
			this.exe();
		}
		
		this.updateStory=function(id,storyId){
			thisObj=this;
			jConfirm("更新します。よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"updateStory",
						"id":id,
						"storyId":storyId,
						"securityKey":$("#securityKey").val(),
						"who":$("input[name='nes_who']:checked").val(),
						"storyMemo":$("#nes_memo").val(),
						"storyResult":$("input[name='nes_result']:checked").val(),
						"time":$("#nes_time option:selected").val(),
						"storyPurpose":$("input[name='nes_tec']:checked").val()	
					});
					thisObj.setElementName("storyNew");
					thisObj.exe();
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		this.deleteStory=function(id,storyId){
			thisObj=this;
			jConfirm("削除します。よろしいですか？",
				"確認",function(d) {
				if(d){
					thisObj.setParamList({"process":"deleteStory",
						"id":id,
						"storyId":storyId,
						"securityKey":$("#securityKey").val()
					});
					thisObj.setElementName("storyNew");
					thisObj.exe();
				}else{
					jAlert("キャンセルしました。");
				}
			});	
		}
		
		this.nextShowStory=function(taskId,pager){
			this.setParamList({"process":"nextShowStory","id":taskId,"pager":pager,"securityKey":$("#securityKey").val()});
			this.setElementName("storyList");
			this.exe();
		}
		this.nextShowStoryExe=function(taskId,pager){
			this.setParamList({"process":"nextShowStoryExe","id":taskId,"pager":pager,"securityKey":$("#securityKey").val()});
			this.setElementName("storyList");
			this.exe();
		}
		this.storyDrop=function(event,id,dropId) {
			if(dropId==(dragId=event.dataTransfer.getData("storyId"))){
			}else{
				this.setParamList({"process":"replaceStory",
		  			"id":id,
		  			"securityKey":$("#securityKey").val(),
		  			"storyId":dragId,
		  			"replaceId":dropId});
		  		this.setElementName("storyNew");
		  		this.exe();
			}
		}
		
		
		this.showBackLog=function(showType){
			this.setParamList({"process":"showBackLog",
				"showType":showType});
			this.setElementName(this.getRunExe());
			this.exe();
		}
		this.showBackLogPage=function(page){
			this.setParamList({"process":"showBackLog","page":page});
			this.setElementName("taskPage");
			this.exe();
		}
		
		
	//override
		//override
		//画面の書き換え処理
		this.output=function(data){
			var list=this.getParamList();
			if(list["process"]=="nextShowStory"||list["process"]=="nextShowStoryExe"){
				$("#nextButton").remove();
				$("#"+this.getElementName()).append(data);
			}else{
				$("#"+this.getElementName()).html(data);
			}
			this.exeReturn();//レスポンス後処理を実行
		}
		this.getUrl=function(){//url of a request place 
			return global_path+"slup/negotiator/";
		}
}
Negotiator.prototype=new SLCAjax();
var negotiator=new Negotiator();

