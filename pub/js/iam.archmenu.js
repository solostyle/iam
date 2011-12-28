this.Iam.Archmenu = this.Iam.Archmenu || function() {

	/* Elements
	* LISTING <ul>
	* housing all years: "archlev1, archmenu_list_years" ID: "archmenu"
	* housing all months in a year: "archlev2, archmenu_list_months" ID: "archmenu_y_2011">
	* housing all titles in a month: "archlev3, archmenu_list_titles" ID: "archmenu_y_2011_m_01">
	* TOGGLING <span>
	* year: "archmenu_ty" ID: "archmenu_ty_2011"
	* month: "archmenu_tm" ID: "archmenu_ty_2011_tm_01"
	*/
	
	/* Objects
	* Iam.Objects.ArchNavMenu is set when first page loads
	* if it is null, call /menu
	*/

	var leftWPElem = function() {return Ydom.get('left');}, // #left houses #archmenuWP
	archmenuWPElem = function() {return Ydom.get('archmenuWP');};

	// Success and failure functions for different requests
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			leftWPElem().innerHTML = "request failure: " + o.responseText + leftWPElem().innerHTML;
		}
	};

	var insertMenu = function(o) {
		if(o.responseText !== undefined){
			leftWPElem().innerHTML = o.responseText;
		}
	};

	var storeMenu = function(o) {
		if(o.responseText !== undefined){
			Iam.Objects.ArchMenu = JSON.parse(o.responseText);
		}
	};
	
	var indexCallback ={
		method:"GET",
		success: insertMenu,
		failure: handleFailure
	};
	var menuCallback ={
		method:"GET",
		success: storeMenu,
		failure: handleFailure
	};
	
	//Handler to make XHR request for just showing all entries
	var indexRequest = function(isAjaxR){
      if (isAjaxR) AjaxR(Iam.RootDir()+Iam.Ds()+'archmenu/index/1', indexCallback);
      else AjaxR(Iam.RootDir()+Iam.Ds()+'archmenu/index/0', indexCallback);
	};
  
	var menuRequest = function(isAjaxR){
      if (isAjaxR) AjaxR(Iam.RootDir()+Iam.Ds()+'archmenu/menu/1', menuCallback);
      else AjaxR(Iam.RootDir()+Iam.Ds()+'archmenu/menu/0', menuCallback);
	};

	// Saves the view of the menu so that it can load it this way next time
	var saveMenuState = function(id, yr, mo) {
		// rules:
		// > show anything the user wanted to show
		// > show the current url's submenu
		//		2011/10
		//			> expand 2011
		//		2011/10/04/entry
		//			> expand 2011 and 10
		//		2011/
		//			> don't expand anything unless user did
		// > hide everything else

		if (!Iam.Objects.ArchMenu) {
			menuRequest(true);
			saveMenuState(id, yr, mo);
		} else {
			// look up the id in the array
			// if it's not found, do nothing
			// if found, get the class of the element
			// if it has a hidden class, change the display of the id to hide
			var menu = Ydom.get(id);
			if (mo) {
				Iam.Objects.ArchMenu[yr][mo]['display'] = Ydom.hasClass(menu, 'hidden') ? 'hide' : 'show';
			} else {
				Iam.Objects.ArchMenu[yr]['display'] = Ydom.hasClass(menu, 'hidden') ? 'hide' : 'show';
			}
		}
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
		cmd = (targetId)?targetId.split('_')[1]:null,
		year = (targetId)?targetId.split('_')[2]:null,
		cmd = (targetId && targetId.split('_')[3]) ? targetId.split('_')[3] : cmd,
		month = (targetId)?targetId.split('_')[4]:null,
		menuId = "";
		
		switch (cmd) {
		case "ty": // toggle year menu
			menuId = 'archmenu_y_'+year;
			toggleMenu(menuId, targetId);
			saveMenuState(menuId, year, month);
			break;
		case "tm": // toggle month menu
			menuId = 'archmenu_y_'+year+'_m_'+month;
			toggleMenu(menuId, targetId);
			saveMenuState(menuId, year, month);
			break;
		default:
			break;
		}
	};
	
	return {
		
		Load: function(){
			// initial load
			// currently header.php loads this
			//indexRequest(true);
			
			// store menu as js objectj
			// TODO: Only run this if anything has been added/deleted/modified
			menuRequest(true);

			// set event handler for clicks in the web part
			Listen("click", handleClick, 'archmenuWP');
		}
	};

}();