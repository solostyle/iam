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
      // seems like only works if cache-control is given alone
      // then both cache-control and if-modified-since have to be given together
      // then the browser takes from cache, but it still has long "wait" time
      Ycnxn.initHeader('Cache-Control','max-age=259200',true);
      Ycnxn.initHeader('If-Modified-Since','Sat, 08 Jan 2011 01:01:38 GMT', false);
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