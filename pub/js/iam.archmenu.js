this.Iam.Archmenu = this.Iam.Archmenu || function() {

	// Elements
	var leftWPElem = function() {return Ydom.get('left');}; // #left houses #archmenuWP

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
  

	// Toggles the view of menus
	var toggleYearMenu = function(id) {
		toggleMenu(id);
	};
	
	var toggleMonthMenu = function(id) {
		toggleMenu(id);
	};
  
	var toggleMenu = function(id) {
		var list = Ydom.get(id);
		if (Ydom.hasClass(list, 'hidden')) {
			Ydom.removeClass(list, 'hidden'); 
		} else {
			Ydom.addClass(list, 'hidden');
		}
	};
  
	// Handles Clicks in the web part
	var handleClick = function(e) {
		var targetId = e.target.getAttribute('id'),
		/* LISTING
		// housing all years: "archlev1, archmenu_list_years" ID: "archmenu"
		// housing all months in a year: "archlev2, archmenu_list_months" ID: "archmenu_y_2011">
		// housing all titles in a month: "archlev3, archmenu_list_titles" ID: "archmenu_m_01">
		// TOGGLING
		// year: "archmenu_ty" ID: "archmenu_ty_2011"
		// month: "archmenu_tm" ID: "archmenu_tm_01"
		*/
		command = (targetId)?targetId.split('_', 3)[1]:null,
		id = (targetId)?targetId.split('_', 3)[2]:null;
		
		switch (command) {
		case "ty": // toggle year menu
			toggleYearMenu('archmenu_y_'+id);
			//addArchlinkRequest();
			break;
		case "tm": // toggle month menu
			toggleMonthMenu('archmenu_m_'+id);
			//deleteArchlinkRequest(id);
			break;
		default:
			break;
		}
	};

	return {
		
		Load: function(){
			// initial load
			//indexRequest(true);

			// set event handler for clicks in the web part
			Listen("click", handleClick, 'archmenuWP');
		}
	};

}();