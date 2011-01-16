this.Iam.Admin = this.Iam.Admin || function() {

    // Elements
    var addEntryWPElem = function() {return Ydom.get('blogAddForm');},
    //blogElem = function() {return Ydom.get('blog');},
    formDivElem = function() {return Ydom.get('addForm');},
    formToggleDivElem = function() {return Ydom.get('addAnEntry');},
    formTitleElem = function() {return Ydom.get('addFormTitle');},
    formEntryElem = function() {return Ydom.get('addFormEntry');},
    formTimeElem = function() {return Ydom.get('addFormTime');},

    formYearElem = function() {return Ydom.get('year');},
    formMonthElem = function() {return Ydom.get('month');},
    formDateElem = function() {return Ydom.get('date');},
        
    inpEntry = function() {return formEntryElem().value;}, // TODO: escape quotes!
    inpTitle = function() {return formTitleElem().value;}, // TODO: escape quotes!
    inpCategory = function() {return chooseCategory();},
    inpTime = function() {return formTimeElem().value;},
    inpYear = function() {return formYearElem().value;},
    inpMonth = function() {return formMonthElem().value;},
    inpDate = function() {return formDateElem().value;};
    
    // Success and failure functions for different requests
    var handleFailure = function(o){
        if(o.responseText !== undefined){
            addEntryWPElem().innerHTML = "request failure: " + o.responseText + addEntryWPElem().innerHTML;
        }
    };

    var handleSuccess = function(o) {
        // load the entries again into #blogEntries
        if(o.responseText !== undefined){
            addEntryWPElem().innerHTML = "request success: " + o.responseText + addEntryWPElem().innerHTML;
        }
    };

    /* Callback/Config objects for transactions */
    var allCallback = {
        method: "GET",
        success: handleSuccess,
        failure: handleFailure
    };

    var callback = {
        method:"POST",
        success: handleSuccess,
        failure: handleFailure
    };

    //Handler to make XHR request for just showing all entries
    var homeRequest = function(isAjaxR){
        AjaxR('../shells/index/', callback);
    };
    
    //Handler to make XHR request for adding an entry
    var addEntryRequest = function(){
        callback.data = 'title='+inpTitle()+'&category='+inpCategory()+'&entry='+inpEntry()+'&time='+inpTime()+'&year='+inpYear()+'&month='+inpMonth()+'&date='+inpDate();
        var addRequest = AjaxR('../blog/add', callback);
    };

    var deleteEntryRequest = function(id) {
        callback.data = 'id='+id;
        var deleteRequest = AjaxR('../blog/delete', callback);
    };
  
    var chooseCategory = function(cat_id) {
        var el = Ydom.getElementBy(findCatName, 'input', addEntryWPElem());
        return el.getAttribute('id').split('_', 2)[1];
    };
    
    var findCatName = function(el) {
        if (el.getAttribute('id') && el.getAttribute('id').split('_', 2)[0] == 'addFormCategory') {
            if (el.checked) return true;
            else return false;
        } else return false;
    };
    
    var toggleForm = function() {
        // save off the current values of the input boxes
        var currTitleVal = formTitleElem().value || 'title';
        var currEntryVal = formEntryElem().value || 'entry';
        formDivElem().style.display = (formDivElem().style.display=='block')?'':'block';
        formToggleDivElem().innerHTML = (formDivElem().style.display=='block')?'Close':'Add an Entry';
        
        if (formDivElem().style.display=='') {
            formTitleElem().value = currTitleVal;
            formEntryElem().value = currEntryVal;
        }
    };
    
    var handleClick = function(e) {
        var targetId= e.target.getAttribute('id'),
        // clean the id string, everything before a number
        command = (targetId)?targetId.split('_', 2)[0]:null;
        id = (targetId)?targetId.split('_', 2)[1]:null;
        switch (command) {
        case "addFormSubmit": 
            addEntryRequest(1);
            break;
        case "deleteEntry":
            deleteEntryRequest(id);
            break;
        case "addAnEntry":
            toggleForm();
            break;
        default:
            break;
        }
    };

    return {

        Load: function(){
            // initial load
            //indexRequest(true);

            // set event handle for clicks in the web part
            Listen("click", handleClick, 'right');
        }
    };

}();