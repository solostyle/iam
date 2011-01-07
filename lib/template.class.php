<?php
class Template {
	
	protected $variables = array();
	protected $_controller;
	protected $_action;
	
	function __construct($controller,$action) {
		$this->_controller = $controller;
		$this->_action = $action;
	}

	/** Set Variables **/

	function set($name,$value) {
		$this->variables[$name] = $value;
	}

	/** Display Template 
        Displays the template by including the header, the view, and the footer.
        if doNotRenderHeader is set, only the view is displayed.
        This is useful for handling AJAX requests.
    **/
    function render($doNotRenderHeader = false) {
		
		$html = new HTML;
		extract($this->variables);
		
		if ($doNotRenderHeader == false) {
			if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
				include (ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
			} else {
				include (ROOT . DS . 'app' . DS . 'views' . DS . 'header.php');
			}
		}

		if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php')) {
			include (ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');
		} else {
            // if it's not in the given controller, look for it in the shared elements folder
            // although I don't think this is how I will be using this
            include (ROOT . DS . 'app' . DS . 'views' . DS . 'elements' . DS . $this->_action . '.php');
        }
		// Also do not render footer
		if ($doNotRenderHeader == false) {
			if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
				include (ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
			} else {
				include (ROOT . DS . 'app' . DS . 'views' . DS . 'footer.php');
			}
		}
    }

}