<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

require_once XOOPS_ROOT_PATH.'/kernel/object.php';
require_once XOOPS_ROOT_PATH.'/modules/formulize/class/screen.php';
include_once XOOPS_ROOT_PATH.'/modules/formulize/include/functions.php';

class formulizeGraphScreen extends formulizeScreen {

	function formulizeGraphScreen() {
		$this->formulizeScreen();
		$this->initvar("width", XOBJ_DTYPE_INT);
		$this->initvar("height", XOBJ_DTYPE_INT);
		$this->initvar("orientation", XOBJ_DTYPE_TXTBOX, NULL, false, 255);
		$this->initvar("bgr", XOBJ_DTYPE_INT);
		$this->initvar("bgg", XOBJ_DTYPE_INT);
		$this->initvar("bgb", XOBJ_DTYPE_INT);
		$this->initvar("barr", XOBJ_DTYPE_INT);
		$this->initvar("barg", XOBJ_DTYPE_INT);
		$this->initvar("barb", XOBJ_DTYPE_INT);
		$this->initvar("ops", XOBJ_DTYPE_TXTBOX, NULL, false, 255);
		$this->initvar("labelelem", XOBJ_DTYPE_INT);
		$this->initvar("dataelem", XOBJ_DTYPE_INT);
	}
}

class formulizeGraphScreenHandler extends formulizeScreenHandler {
	var $db;
	function formulizeGraphScreenHandler(&$db) {
		$this->db =& $db;
	}
	function &getInstance(&$db) {
		static $instance;
		if (!isset($instance)) {
			$instance = new formulizeGraphScreenHandler($db);
		}
		return $instance;
	}
	function &create() {
		return new formulizeGraphScreen();
	}


	function insert($screen) {
		// sid is being used as a flag to update or not
		$update = ($screen->getVar('sid') == 0) ? false : true;
		// insert or update and get the actual sid
		if(!$sid = parent::insert($screen)) { // write the basic info to the db, handle cleaning vars and all that jazz.  Object passed by reference, so updates will have affected it in the other method.
			return false;
		}
		$screen->assignVar('sid', $sid);
		// standard flags used by xoopsobject class
	    $screen->setVar('dohtml', 0);
	    $screen->setVar('doxcode', 0);
	    $screen->setVar('dosmiley', 0);
	    $screen->setVar('doimage', 0);
	    $screen->setVar('dobr', 0);
		// note: conditions is not written to the DB yet, since we're not gathering that info from the UI	
		if (!$update) {
            $sql = sprintf("INSERT INTO %s (sid, width, height, orientation, bgr, bgg, bgb, barr, barg, barb, ops, labelelem, dataelem) VALUES (%u, %u, %u, %s, %u, %u, %u, %u, %u, %u, %s, %u, %u)", $this->db->prefix('formulize_screen_graph'), $screen->getVar('sid'), $screen->getVar('width'), $screen->getVar('height'), $this->db->quoteString($screen->getVar('orientation')), $screen->getVar('bgr'), $screen->getVar('bgg'), $screen->getVar('bgb'), $screen->getVar('barr'), $screen->getVar('barg'), $screen->getVar('barb'), $screen->getVar('ops'));
        } else {
            $sql = sprintf("UPDATE %s SET width = %u, height = %u, orientation = %s, bgr = %u, bgg = %u, bgb = %u, barr = %u, barg = %u, barb = %u, ops = %s, labelelem = %u, dataelem = %u WHERE sid = %u", $this->db->prefix('formulize_screen_graph'), $screen->getVar('width'), $screen->getVar('height'), $this->db->quoteString($screen->getVar('orientation')), $screen->getVar('bgr'), $screen->getVar('bgg'), $screen->getVar('bgb'), $screen->getVar('barr'), $screen->getVar('barg'), $screen->getVar('barb'), $this->db->quoteString($screen->getVar('ops')), $screen->getVar('labelelem'), $screen->getVar('dataelem'), $screen->getVar('sid'));
        }
        $result = $this->db->query($sql);
        if (!$result) {
            print "Error: could not save the screen properly: ".mysql_error()." for query: $sql";
            return false;
        }
        return $sid;
	}

	// 	THIS METHOD MIGHT BE MOVED UP A LEVEL TO THE PARENT CLASS
	function get($sid) {
		$sid = intval($sid);
		if ($sid > 0) {
			$sql = 'SELECT * FROM '.$this->db->prefix('formulize_screen').' AS t1, '. $this->db->prefix('formulize_screen_graph').' AS t2 WHERE t1.sid='.$sid.' AND t1.sid=t2.sid';
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$screen = new formulizeGraphScreen();
				$screen->assignVars($this->db->fetchArray($result));
				return $screen;
			}
		}
		return false;

	}

	// THIS METHOD HANDLES ALL THE LOGIC ABOUT HOW TO ACTUALLY DISPLAY THIS TYPE OF SCREEN
	// $screen is a screen object
	function render($screen, $entry, $settings = "") { // $settings is used internally to pass list of entries settings back and forth to editing screens
		$bgc = [
			"R" => $screen->getVar('bgr'),
			"G" => $screen->getVar('bgg'),
			"B" => $screen->getVar('bgb')
		];
		$barc = [
			"R" => $screen->getVar('barr'),
			"G" => $screen->getVar('barg'),
			"B" => $screen->getVar('barb')
		];
		$options = [
			"width" => $screen->getVar('width'),
			"height" => $screen->getVar('height'),
			"orientation" => $screen->getVar('orientation'),
			"backgroundcolor" => $bgc,
			"barcolor" => $barc
		];
		include_once XOOPS_ROOT_PATH."/modules/formulize/include/graphdisplay.php";
		displayGraph('Bar', $screen->getVar('fid'), $screen->getVar('frid'), $screen->getVar('labelelem'), $screen->getVar('dataelem'), $screen->getVar('ops'), $options);
	}
}
?>