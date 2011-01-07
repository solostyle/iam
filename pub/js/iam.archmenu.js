this.Iam.Archmenu = this.Iam.Archmenu || function() {

	// Elements
	var archmenuWPElem = function() {return Ydom.get('left');};

  // Success and failure functions for different requests
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			archmenuWPElem().innerHTML = "request failure: " + o.responseText + archmenuWPElem().innerHTML;
		}
	};

	var handleSuccess = function(o) {
		if(o.responseText !== undefined){
			archmenuWPElem().innerHTML = o.responseText;
		}
	};

	var allCallback ={
		method:"GET",
		success: handleSuccess,
		failure: handleFailure
	};

	//Handler to make XHR request for just showing all entries
  var indexRequest = function(isAjaxR){
      if (isAjaxR) AjaxR('../archmenu/index/1', allCallback);
      else AjaxR('../archmenu/index/0', allCallback);
  };
  
// 	var handleClick = function(e) {
// 		var targetId= e.target.getAttribute('id'),
// 		// clean the id string, everything before a number
// 		command = (targetId)?targetId.split('_', 2)[0]:null;
// 		id = (targetId)?targetId.split('_', 2)[1]:null;
// 		switch (command) {
// 		case "addArchlink": 
// 			addArchlinkRequest();
// 			break;
// 		case "deleteArchlink":
// 			deleteArchlinkRequest(id);
// 			break;
// 		default:
// 			break;
// 		}
// 	};

	return {
		
		Load: function(){
			// initial load
			indexRequest(true);

			// set event handle for clicks in the web part
      // TODO: Can keep track of clicks for formatting!
			//Listen("click", handleClick, 'archmenuWP');
		}
	};

}();