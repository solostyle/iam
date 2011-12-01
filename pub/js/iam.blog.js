this.Iam.Blog = this.Iam.Blog || function() {

    // Elements
    var blogWPElem = function() {return Ydom.get('blogEntries');};

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

    var allCallback ={
        method:"GET",
        success: handleAllSuccess,
        failure: handleFailure
    };

    var indexRequest = function(){
        var indexRequest = AjaxR(Iam.RootDir()+Iam.Ds()+'blog/index', allCallback);
    };
  
	return {
		
		Load: function(){
			// initial load
			indexRequest();

		}
	};

}();