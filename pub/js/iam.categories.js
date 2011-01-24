this.Iam.Categories = this.Iam.Categories || function() {

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

    var catRequest = function(cat){
        cat = cat.replace(/[_]/gi, " ");
        var requestStr = root+ds+'blog/category/'+cat;
        var request = AjaxR(requestStr, callback);
    };

    return {

        Load: function(cat){
            // initial load
            catRequest(cat);
        }
    };

}();