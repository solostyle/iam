this.Iam.Blog = this.Iam.Blog || function() {

	// Elements
	var blogWPElem = function() {return Ydom.get('blog');};
	//blogElem = function() {return Ydom.get('blog');},
	//formTitleElem = function() {return Ydom.get('blogWPTitle');},
	//formEntryElem = function() {return Ydom.get('blogWPEntry');},
	//inpEntry = function() {return formEntryElem().value;}, // TODO: escape quotes!
	//inpTitle = function() {return formTitleElem().value;}, // TODO: escape quotes!
	//formDivElem = function() {return Ydom.get('blogForm');},
	//formToggleDivElem = function() {return Ydom.get('addAnEntry');};

	// Success and failure functions for different requests
	var handleSuccess = function(o){
		allRequest(false);
	};

	var handleFailure = function(o){
		if(o.responseText !== undefined){
			blogWPElem().innerHTML = "request failure: " + o.responseText + blogWPElem().innerHTML;
		}
	};

	var handleAllSuccess = function(o) {
		if(o.responseText !== undefined){
			blogWPElem().innerHTML = o.responseText;
		}
	};

	/* Callback/Config objects for transactions */
/*	var callback = {
		method: "POST",
		success: handleSuccess,
		failure: handleFailure
	}*/;

	var allCallback ={
		method:"GET",
		success: handleAllSuccess,
		failure: handleFailure
	};

	//Handler to make XHR request for just showing all entries
  var indexRequest = function(isAjaxR){
      if (isAjaxR) AjaxR('../blog/index/1', allCallback);
      else AjaxR('../blog/index/0', allCallback);
  };
  
// 	var toggleForm = function() {
// 		// save off the current values of the input boxes
// 		var currTitleVal = formTitleElem().value || 'title';
// 		var currEntryVal = formEntryElem().value || 'entry';
// 		formDivElem().style.display = (formDivElem().style.display=='block')?'':'block';
// 		formToggleDivElem().innerHTML = (formDivElem().style.display=='block')?'Close':'Add an Entry';
// 		if (formDivElem().style.display=='') {
// 			formTitleElem().value = currTitleVal;
// 			formEntryElem().value = currEntryVal;
// 		}
// 	};

// 	var handleClick = function(e) {
// 		var targetId= e.target.getAttribute('id'),
// 		// clean the id string, everything before a number
// 		command = (targetId)?targetId.split('_', 2)[0]:null;
// 		id = (targetId)?targetId.split('_', 2)[1]:null;
// 		switch (command) {
// 		case "addEntry": 
// 			addEntryRequest();
// 			break;
// 		case "deleteEntry":
// 			deleteEntryRequest(id);
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
			//Listen("click", handleClick, 'blogWP');
		}
	};

}();