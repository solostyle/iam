this.Iam.Archmenu = this.Iam.Archmenu || function() {

	/* Elements
	* LISTING <ul>
	* housing all years: "archlev1, archmenu_list_years" ID: "archmenu"
	* housing all months in a year: "archlev2, archmenu_list_months" ID: "archmenu_y_2011">
	* housing all titles in a month: "archlev3, archmenu_list_titles" ID: "archmenu_m_01">
	* TOGGLING <span>
	* year: "archmenu_ty" ID: "archmenu_ty_2011"
	* month: "archmenu_tm" ID: "archmenu_tm_01"
	*/
	var leftWPElem = function() {return Ydom.get('left');}, // #left houses #archmenuWP
	archmenuWPElem = function() {return Ydom.get('archmenuWP');};

	// Success and failure functions for different requests
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			leftWPElem().innerHTML = "request failure: " + o.responseText + leftWPElem().innerHTML;
		}
	};

	var handleSuccess = function(o) {
		if(o.responseText !== undefined){
			leftWPElem().innerHTML = o.responseText;
		}
	};

	var allCallback ={
		method:"GET",
		success: handleSuccess,
		failure: handleFailure
	};

	//Handler to make XHR request for just showing all entries
	var indexRequest = function(isAjaxR){
      if (isAjaxR) AjaxR('../archmenu/index/1', allCallback);
      else AjaxR('../archmenu/index/0', allCallback);
	};
  

	// Toggles the view of menus and their buttons
	var toggleMenu = function(menuId, buttonId) {
		var menu = Ydom.get(menuId),
		button = Ydom.get(buttonId);
		
		// SHOW
		if (Ydom.hasClass(menu, 'hidden')) {
			Ydom.removeClass(menu, 'hidden');
			button.innerHTML = "--";
		} else {
		// HIDE
			Ydom.addClass(menu, 'hidden');
			button.innerHTML = "+";
		}
	};
  
	// Handles Clicks in the web part
	var handleClick = function(e) {
		var targetId = e.target.getAttribute('id'),
		command = (targetId)?targetId.split('_', 3)[1]:null,
		id = (targetId)?targetId.split('_', 3)[2]:null;
		
		switch (command) {
		case "ty": // toggle year menu
			toggleMenu('archmenu_y_'+id, targetId);
			break;
		case "tm": // toggle month menu
			toggleMenu('archmenu_m_'+id, targetId);
			break;
		default:
			break;
		}
	};
	
	// Expands this month, collapses the rest
	var initCollapseMonths = function(arr) {
		var date = new Date(),
		currentMonth = date.getMonth()+1;
		currentMonth = currentMonth.toString();
		currentMonth = (currentMonth < 10)? '0'+currentMonth : currentMonth;
		
		for(var i=0, len=arr.length; i < len; i++){
			if (arr[i].getAttribute('id').split('_', 3)[2] == currentMonth) {
				// expand or keep expanded
				Ydom.removeClass(arr[i], 'hidden');
			} else {
				Ydom.addClass(arr[i], 'hidden');
			}
		}
		
		Ydom.get('archmenu_tm_'+currentMonth).innerHTML = "--";
	};

	// Expands this year, collapses the rest
	var initCollapseYears = function(arr) {
		var date = new Date(),
		currentYear = date.getFullYear().toString(),
		toggleButton = Ydom.get('archmenu_ty_'+currentYear);
		
		for(var i=0, len=arr.length; i < len; i++){
			if (arr[i].getAttribute('id').split('_', 3)[2] == currentYear) {
				// expand or keep expanded
				Ydom.removeClass(arr[i], 'hidden');
			} else {
				Ydom.addClass(arr[i], 'hidden');
			}
		}
		
		Ydom.get('archmenu_ty_'+currentYear).innerHTML = "--";
	};

	// Initialize how the menu looks, expanded/collapsed
	var initMenuView = function() {
		// expand this month, collapse the rest
		// collapse other years
		var arrayOfTitles = Ydom.getElementsByClassName("archlev3", "ul", archmenuWPElem()),
		arrayOfMonths = Ydom.getElementsByClassName("archlev2", "ul", archmenuWPElem());
		initCollapseMonths(arrayOfTitles);
		initCollapseYears(arrayOfMonths);
	};
	
	return {
		
		Load: function(){
			// initial load
			//indexRequest(true);
			
			// set up collapsed/expanded
			initMenuView();

			// set event handler for clicks in the web part
			Listen("click", handleClick, 'archmenuWP');
		}
	};

}();