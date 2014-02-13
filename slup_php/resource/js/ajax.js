var LfAjax=function(access){
	/**
	 * param list.
	 */
	this.paramList={};
	/**
	 * name for output element.
	 */
	this.elementName;
	/**
	 * Access distribution
	 * @type AccessSupport
	 */
	this.access=access;
	
	/**
	 * Override required
	 */
		/**
		 * The URL for the request destination
		 */
		this.getUrl=function(){}
		/**
		 * run after the response
		 */
		this.exeReturn=function(){}
		this.runError=function(){}
		/**
		 * Exception for ajax
		 */
		this.ajaxException=function(transport, ex){}
		
	
	/**
	 * Initialization
	 */
		this.getMethodType=function(){//パラメーター送信の種類を返す
			return "post";//初期設定はPOST
		}
		this.getErrorRes=function(){//レスポンスエラーを表す文字列を返す
			return "error";
		}
		this.getFalseRes=function(){//画面の書き換えなしを表す文字列を返す
			return "false";
		}
	
	/**
	 * run
	 */
		//画面の書き換え処理
		this.output=function(data){
			$("#"+this.getElementName()).html(data);
			this.exeReturn();//レスポンス後処理を実行
		}
		//レスポンス処理
		this.response_exe=function(data){
			if(data==this.getErrorRes()){//エラーレスポンス
				this.runError();//ajaxエラーの処理
			}else if(data.responseText==this.getFalseRes()){//画面の書き換えをしない
				this.exeReturn();//レスポンス後処理を実行
			}else{
				this.output(data);//画面の書き換え処理
			}
		}
		//非同期通信を実行
		this.exe=function(){
			if(this.isExamine_exe()){//ajax処理が実行可能
				$.ajax({
					type: this.getMethodType(),
					url:this.getUrl(),
					data: this.getParamList(),
					success: this.response_exe.bind(this),
					error :this.ajaxException.bind(this)
				});
			}
		}
			this.isExamine_exe=function(){//ajax処理が実行可能か
				return true;
			}
			
		this.getParamList=function(){//パラメーターの配列
			return this.paramList;
		}
		this.getElementName=function(){
			return this.elementName;
		}
		this.setParamList=function(paramList){//new
			this.paramList=paramList;
		}
		this.setElementName=function(elementName){
			this.elementName=elementName;
		}
		this.getAccess=function(){
			return this.access;
		}
		
}
var AccessSupport=function(){
	this.hidePageLoading=function(){
		$.prettyLoader.hide();
	}
	this.showPageLoading=function(){
		$.prettyLoader.show();
	}
	this.exeReturn=function(element){}
}
var AccessSupport_mobile=function(){
	this.hidePageLoading=function(){
		$.mobile.hidePageLoadingMsg();
	}
	this.showPageLoading=function(){
		$.mobile.showPageLoadingMsg();
	}
	this.exeReturn=function(element){
		$("#"+element).trigger("create");
	}
}
AccessSupport_mobile.prototype=new AccessSupport();
/**
var AjaxFunk_pTypeTooltip=function(){
	//【オーバーライド】
		//画面の書き換え処理
		this.output=function(data){
			tooltip.show(data.responseText);
			this.returnFunk_exe();//レスポンス後処理を実行
		}
}
AjaxFunk_pTypeTooltip.prototype=new AjaxFunk_pType();
**/
var AjaxFunc_JAlert=function(){
	//【オーバーライド必須】
	//【オーバーライド】
		//画面の書き換え処理
		this.output=function(data){
			jAlert(data.responseText);
			this.returnFunk_exe();//レスポンス後処理を実行
		}
		
	//【処理】		
		this.isExamineConfirm=function(subText,confirmText,callback,callbackValue){
			var thisObj=this;
			jConfirm(confirmText,
				subText,function(d) {
				if(d){
					callback(callbackValue,thisObj);
				}else{
					thisObj.cancel();
				}
			});
		}
		this.isExaminePrompt=function(title,subText,text,confirmText,callback){
			var thisObj=this;
			jPrompt(subText,text,
				title,function(r) {
				if(r){
					thisObj.isExamineConfirm(title,r.escapeHTML()+confirmText,callback,r.escapeHTML());
				}
			});
		}
		this.isExamineText=function(title,subText,text,confirmText,callback){
			var thisObj=this;
			jText(subText,text,
				title,function(r) {
				if(r){
					thisObj.isExamineConfirm(title,r.escapeHTML()+confirmText,callback,r.escapeHTML());
				}
			});
		}
		this.cancel=function(){
			jAlert("キャンセルしました。");
		}		
}
//AjaxFunc_JAlert.prototype=new AjaxFunk_pType();