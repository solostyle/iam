this.Iam.Tags = this.Iam.Tags || function() {

    // Globals, bah!
    var root = "http://iam.solostyle.net", ds = "/";

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

    var idRequest = function(tag){
        var requestStr = root+ds+'blog/tag/'+tag;
        var request = AjaxR(requestStr, callback);
    };

    return {

        Load: function(tag){
            // initial load
            tsgRequest(tag);
        }
    };

}();