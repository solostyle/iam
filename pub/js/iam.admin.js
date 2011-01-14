this.Iam.Admin = this.Iam.Admin || function() {

    // Elements
    var adminEntryWPElem = function() {return Ydom.get('adminEntry');},
    //blogElem = function() {return Ydom.get('blog');},
    formTitleElem = function() {return Ydom.get('title');},
    formEntryElem = function() {return Ydom.get('entry');},
    formTimeElem = function() {return Ydom.get('time');},
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
	var handleAddSuccess = function(o){
		homeRequest(false);
	};

	var handleFailure = function(o){
		if(o.responseText !== undefined){
			adminEntryWPElem().innerHTML = "request failure: " + o.responseText + adminEntryWPElem().innerHTML;
		}
	};

    var handleSuccess = function(o) {
        // do nothing
    };

    /* Callback/Config objects for transactions */
    var callback = {
        method: "GET",
        success: handleSuccess,
        failure: handleFailure
    };

    var addCallback = {
        method:"POST",
        success: handleAddSuccess,
        failure: handleFailure
    };

    //Handler to make XHR request for just showing all entries
    var homeRequest = function(isAjaxR){
        AjaxR('../shells/index/', callback);
    };
    
    //Handler to make XHR request for adding an entry
    var addEntryRequest = function(isAjaxR){
        addCallback.data = 'title='+inpTitle()+'&category='+inpCategory()+'&entry='+inpEntry()+'&time='+inpTime()+'&year='+inpYear()+'&month='+inpMonth()+'&date='+inpDate();
        if (isAjaxR) AjaxR('../admin/add_entry/1', addCallback);
        else AjaxR('../admin/add_entry/0', addCallback);
    };
  
    var chooseCategory = function(cat_id) {
        var el = Ydom.getElementBy(findCatName, 'input', adminEntryWPElem());
        return el.getAttribute('id').split('_', 2)[1];
    };
    
    var findCatName = function(el) {
        if (el.getAttribute('id') && el.getAttribute('id').split('_', 2)[0] == 'category') {
            if (el.checked) return true;
            else return false;
        } else return false;
    };
    
    var handleClick = function(e) {
        var targetId= e.target.getAttribute('id'),
        // clean the id string, everything before a number
        command = (targetId)?targetId.split('_', 2)[0]:null;
        id = (targetId)?targetId.split('_', 2)[1]:null;
        switch (command) {
        case "addEntry": 
            addEntryRequest(1);
            break;
        case "deleteEntry":
            deleteEntryRequest(id);
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
			Listen("click", handleClick, 'adminEntry');
		}
	};

}();