this.Iam.Admin = this.Iam.Admin || function() {

    // Elements
    var addEntryWPElem = function() {return Ydom.get('blogAddForm');},
    blogEntriesElem = function() {return Ydom.get('blogEntries');},
    formDivElem = function() {return Ydom.get('addForm');},
    formToggleDivElem = function() {return Ydom.get('addAnEntry');},
    formTitleElem = function() {return Ydom.get('addFormTitle');},
    formEntryElem = function() {return Ydom.get('addFormEntry');},
    formTimeElem = function() {return Ydom.get('addFormTime');},

    formYearElem = function() {return Ydom.get('year');},
    formMonthElem = function() {return Ydom.get('month');},
    formDateElem = function() {return Ydom.get('date');},
    formHourElem = function() {return Ydom.get('hour');},
    formMinuteElem = function() {return Ydom.get('minute');},
    
    formEditElem = function(pre, id) {return Ydom.get(pre+'_'+id);},
    
    inpEntry = function() {return formEntryElem().value;}, // TODO: escape quotes!
    inpTitle = function() {return formTitleElem().value;}, // TODO: escape quotes!
    inpCategory = function() {return chooseCategory();},
    inpTime = function() {return formTimeElem().value;},
    inpYear = function() {return formYearElem().value;},
    inpMonth = function() {return formMonthElem().value;},
    inpDate = function() {return formDateElem().value;},
    inpHour = function() {return formHourElem().value;},
    inpMinute = function() {return formMinuteElem().value;};
    
    // Success and failure functions for different requests
    var handleFailure = function(o){
        if(o.responseText !== undefined){
            addEntryWPElem().innerHTML = "request failure: " + o.responseText + addEntryWPElem().innerHTML;
        }
    };

    var handleSuccess = function(o) {
        // b/c successful, clear the form
        clearForm();
        // load the entries again into #blogEntries
        indexRequest();
    };

    var loadIndex = function(o){
        toggleForm("close");
        if(o.responseText !== undefined){
            blogEntriesElem().innerHTML = o.responseText;
        }        
    };
    
    /* Callback/Config objects for transactions */
    var allCallback = {
        method: "GET",
        success: loadIndex,
        failure: handleFailure
    };

    var callback = {
        method:"POST",
        success: handleSuccess,
        failure: handleFailure
    };

    //Handler to make XHR request for showing recent entries
    var indexRequest = function(){
        var request = AjaxR('../blog/index', allCallback);
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
    
    var toggleForm = function(cmd) {
        // save off the current values of the input boxes
        var currTitleVal = formTitleElem().value || 'title';
        var currEntryVal = formEntryElem().value || 'entry';
        
        if(cmd==="close") {
            formDivElem().style.display = "";
            formToggleDivElem().innerHTML = "Add an Entry";
        } else {
            formDivElem().style.display = (formDivElem().style.display=='block')?'':'block';
            formToggleDivElem().innerHTML = (formDivElem().style.display=='block')?'Close':'Add an Entry';
        }
        
        if (formDivElem().style.display=='') {
            formTitleElem().value = currTitleVal;
            formEntryElem().value = currEntryVal;
        }
        updateTimeToNow(); //update the time to now anytime the form is toggled
    };
    
    var clearForm = function() {
        formTitleElem().value = 'title';
        formEntryElem().value = 'entry';
        updateTimeToNow();
        changeTime();
    };
    
    var doubleDigitString = function(digitString) {
        if (digitString.length == 1) {
            return "0" + digitString;
        } else return digitString;
    };
    
    var updateTimeToNow = function() {
        var now = new Date();
        var month = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        formYearElem().value = now.getFullYear();
        formMonthElem().value = month[now.getMonth()];
        formDateElem().value = doubleDigitString(now.getDate());
        formHourElem().value = doubleDigitString(now.getHours());
        formMinuteElem().value = doubleDigitString(now.getMinutes());
    };
    
    var changeTime = function() {
        formTimeElem().value = inpYear() + '.' + inpMonth() + '.' + inpDate() + ' ' + inpHour() + ':' + inpMinute();
    };
    
    var makeEditableTitle = function(editButton, id) {
        // change behavior of the entryEditButton for title
        editButton.setAttribute('id', "saveTitle_" + id);
        editButton.innerHTML = "Save";
        
        // change behavior of the entryTitle h2 element
        var titleEl = formEditElem("entryTitle", id);
        titleEl.innerHTML = '<input type="text" value="'+titleEl.innerHTML+'" />';
        //el.onclick = null; //not needed b/c it didn't have an event
    };
    
    var makeUneditableTitle = function(saveButton, id) {
        // change behavior of the entryTitle h2 element
        var titleEl = formEditElem("entryTitle", id);
        var childEl = titleEl.childNodes[0];
        titleEl.innerHTML = childEl.value;
        
        // change behavior of the entryEditButton for title
        saveButton.setAttribute('id', "editTitle_" + id);
        saveButton.innerHTML = "Edit";
    };
    
    var makeEditableEntry = function(editButton, id) {
        // change behavior of the entryEditButton for title
        editButton.setAttribute('id', "saveEntry_" + id);
        editButton.innerHTML = "Save";
        
        // change behavior of the entryEntry div element
        var entryEl = formEditElem("entryEntry", id);
        entryEl.innerHTML = '<textarea>'+entryEl.innerHTML+'</textarea>';
        //el.onclick = null; //not needed b/c it didn't have an event
    };
    
    var makeUneditableEntry = function(saveButton, id) {
        // change behavior of the entryEntry div element
        var entryEl = formEditElem("entryEntry", id);
        var childEl = entryEl.childNodes[0];
        entryEl.innerHTML = childEl.value;
        
        // change behavior of the entryEditButton for title
        saveButton.setAttribute('id', "editEntry_" + id);
        saveButton.innerHTML = "Edit";
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
        case "addFormChangeTime":
            changeTime();
            break;
        case "editTitle":
            makeEditableTitle(e.target, id);
            break;
        case "saveTitle":
            makeUneditableTitle(e.target, id);
            break;
        case "editEntry":
            makeEditableEntry(e.target, id);
            break;
        case "saveEntry":
            makeUneditableEntry(e.target, id);
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
        },
        makeUneditableTitle: makeUneditableTitle
    };

}();