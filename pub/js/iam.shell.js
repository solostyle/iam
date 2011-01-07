this.Iam.Shell = this.Iam.Shell || function() {

	var handleDomReady = function(obj) {
		//onDOMReady uses the Custom Event signature, with the object
		//passed in as the third argument:
		//type <string>, args <array>, customobject <object>
		//"DOMReady", [], obj
		
		// load comments web part
		Iam.Comments.Load();
		
		// load updates web part
		Iam.Updates.Load();
	};

	return {
		LoadWebParts: function() {
			Yevent.onDOMReady(handleDomReady);
		}
	};

}();