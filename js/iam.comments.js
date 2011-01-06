this.Iam.Comments = this.Iam.Comments || function() {

	// Elements
	var commentsWPElem = function() {return Ydom.get('commentsWP');};
	commentsElem = function() {return Ydom.get('comments');},
	formNameElem = function() {return Ydom.get('commentsWPName');},
	formCommentElem = function() {return Ydom.get('commentsWPComment');},
	inpComment = function() {return formCommentElem().value;}, // TODO: escape quotes!
	inpName = function() {return formNameElem().value;}, // TODO: escape quotes!
	formDivElem = function() {return Ydom.get('commentsForm');},
	formToggleDivElem = function() {return Ydom.get('addAComment');};

	// Success and failure functions for different requests
	var handleSuccess = function(o){
		allRequest(false);
	};

	var handleFailure = function(o){
		if(o.responseText !== undefined){
			commentsWPElem().innerHTML = "request failure: " + o.responseText + commentsWPElem().innerHTML;
		}
	};

	var handleAllSuccess = function(o) {
		if(o.responseText !== undefined){
			commentsElem().innerHTML = o.responseText;
		}
	};

	var handleAllFormSuccess = function(o) {
		if(o.responseText !== undefined){
			commentsWPElem().innerHTML = o.responseText;
		}
	};

	/* Callback/Config objects for transactions */
	var callback = {
		method: "POST",
		success: handleSuccess,
		failure: handleFailure
	};

	var allCallback ={
		method:"GET",
		success: handleAllSuccess,
		failure: handleFailure
	};

	var allFormCallback = {
		method:"GET",
		success: handleAllFormSuccess,
		failure: handleFailure
	};

	//Handler to make XHR request for adding a comment
	var addCommentRequest = function(){
		callback.data = 'comment='+inpComment()+'&name='+inpName();
		var request = AjaxR('../comments/add', callback);
	};

	var deleteCommentRequest = function(id) {
		callback.data = 'id='+id;
		var request = AjaxR('../comments/delete', callback);
	};

	var allRequest = function(includeForm) {
		if (includeForm) AjaxR('../comments/all/1', allFormCallback);
		else AjaxR('../comments/all/0', allCallback);
	};

	var toggleForm = function() {
		// save off the current values of the input boxes
		var currNameVal = formNameElem().value || 'name';
		var currCommentVal = formCommentElem().value || 'comment';
		formDivElem().style.display = (formDivElem().style.display=='block')?'':'block';
		formToggleDivElem().innerHTML = (formDivElem().style.display=='block')?'Close':'Add a Comment';
		if (formDivElem().style.display=='') {
			formNameElem().value = currNameVal;
			formCommentElem().value = currCommentVal;
		}
	};

	var handleClick = function(e) {
		var targetId= e.target.getAttribute('id'),
		// clean the id string, everything before a number
		command = (targetId)?targetId.split('_', 2)[0]:null;
		id = (targetId)?targetId.split('_', 2)[1]:null;
		switch (command) {
		case "addComment": 
			addCommentRequest();
			break;
		case "deleteComment":
			deleteCommentRequest(id);
			break;
		case "addAComment":
			toggleForm();
			break;
		default:
			break;
		}
	};

	return {
		
		Load: function(){
			// initial load
			allRequest(true);

			// set event handle for clicks in the web part
			Listen("click", handleClick, 'commentsWP');
		}
	};

}();