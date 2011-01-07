this.Iam.Shell = this.Iam.Shell || function() {

    var handleDomReady = function(obj) {
        //onDOMReady uses the Custom Event signature, with the object
        //passed in as the third argument:
        //type <string>, args <array>, customobject <object>
        //"DOMReady", [], obj

        // load blog entries web part
        Iam.Blog.Load();

        // load archive navigation web part
        //Iam.ArchNav.Load();
    };

    return {
        LoadWebParts: function() {
            Yevent.onDOMReady(handleDomReady);
        }
    };

}();