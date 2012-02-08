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
	// function that converts breaks, new lines, carriage returns into
	// html while respecting other block-level elements
	// Use when saving text to the database, so that it is stored as HTML
    var htmlize = function (text) {
        var finalText = "<p>" + text.replace(/[\r\n]+/gi, "</p><p>") + "</p>";
		// remove any p tags wrapped around headers, list items, and lists
		finalText = finalText.replace(/<p>(<h[1-6]>|<li>|<[u|o]l>)/gi, "$1");
		finalText = finalText.replace(/(<\/h[1-6]>|<\/li>|<\/[u|o]l>)<\/p>/gi, "$1");
		// remove empty paragraphs?
		//finalText = finalText.replace();
		
        return finalText;
    };
    
	// function that converts html paragraphs and breaks into
	// textarea-friendly text for viewing and editing
    var textize = function (text) {
        var finalText = text.replace(/<br[ ]?[\/]?>/gi, "\n");
        finalText = finalText.replace(/<\/p><p>/gi, "\n\n");
		// headers and list items
		finalText = finalText.replace(/[\n\r]*(<h[1-6]>|<li>)[\n\r]*/gi, "\n\n$1");
		finalText = finalText.replace(/[\n\r]*(<\/h[1-6]>|<\/li>)[\n\r]*/gi, "$1\n\n");
		// then the list elements
		finalText = finalText.replace(/[\n\r]*(<[u|o]l>)[\n\r]*/gi, "\n\n$1");
		finalText = finalText.replace(/[\n\r]*(<\/[u|o]l>)[\n\r]*/gi, "$1\n\n");
        
		// remove beginning and ending <p> tags
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
        Htmlize: htmlize,
        Textize: textize,
		RootDir: rootDir,
		Ds: ds
    };
}();
