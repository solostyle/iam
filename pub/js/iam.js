// Global Libraries and functions
this.Ydom = this.Ydom || YAHOO.util.Dom;
this.Ycnxn = this.Ycnxn || YAHOO.util.Connect;
this.AjaxR = this.AjaxR || function (url, callback) {
	Ycnxn.asyncRequest(callback.method, url, callback, callback.data);
};
this.Yevent = this.Yevent || YAHOO.util.Event;
this.Listen = this.Listen || function (event, fn, elid) {
	Yevent.addListener(Ydom.get(elid), event, fn);
};

// Now define local website namespace
this.Iam = this.Iam || function() {
    var convertNewLines = function (text) {
        var finalText = "<p>" + text.replace(/\r\n\r\n/gi, "</p><p>") + "</p>";
        finalText = finalText.replace(/\r\n/gi, "<br />");
        // had to add the following two lines after i started using mysql_real_escape_string() on all inserts
        finalText = finalText.replace(/\n\n/gi, "</p><p>");            
        finalText = finalText.replace(/\n/gi, "<br />");
        return finalText;
    };
    
    var convertBrAndP = function (text) {
        var finalText = text.replace(/<br \/>/gi, "\n");
        finalText = finalText.replace(/<\/p><p>/gi, "\n\n");
        // takes care of the beginning and ending <p> tags
        finalText = finalText.replace(/<p>/gi, "");
        finalText = finalText.replace(/<\/p>/gi, "");
        return finalText;
    };
	
	var rootDir = function() {
		return 'http://iam.solostyle.net';
	};
	
	var ds = function() {
		return '/';
	};
    
    return {
        ConvertNewLines: convertNewLines,
        ConvertBrAndP: convertBrAndP,
		RootDir: rootDir,
		Ds: ds
    };
}();
