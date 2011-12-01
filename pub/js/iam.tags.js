this.Iam.Tags = this.Iam.Tags || function() {

    // Elements
    var blogWPElem = function() {return Ydom.get('blogEntries');};

    // Success and failure functions for different requests
    var handleFailure = function(o){
        if(o.responseText !== undefined){
            blogWPElem().innerHTML = "request failure: " + o.responseText + blogWPElem().innerHTML;
        }
    };

    var handleSuccess = function(o) {
        if(o.responseText !== undefined){
            blogWPElem().innerHTML = o.responseText;
        }
    };

    var callback ={
        method:"GET",
        success: handleSuccess,
        failure: handleFailure
    };

    var tagRequest = function(tag){
        var requestStr = Iam.RootDir()+Iam.Ds()+'blog/tag/1/'+tag;
        var request = AjaxR(requestStr, callback);
    };

    return {

        Load: function(tag){
            // initial load
            tagRequest(tag);
        }
    };

}();