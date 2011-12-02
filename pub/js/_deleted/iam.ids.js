this.Iam.Ids = this.Iam.Ids || function() {

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

    var idRequest = function(id){
        var requestStr = Iam.RootDir()+Iam.Ds()+'blog/id/1/'+id;
        var request = AjaxR(requestStr, callback);
    };

    return {

        Load: function(id){
            // initial load
            idRequest(id);
        }
    };

}();