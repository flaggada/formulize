<?php
###############################################################################
##     Formulize - ad hoc form creation and reporting module for XOOPS       ##
##                    Copyright (c) 2010 Freeform Solutions                  ##
###############################################################################
##  This program is free software; you can redistribute it and/or modify     ##
##  it under the terms of the GNU General Public License as published by     ##
##  the Free Software Foundation; either version 2 of the License, or        ##
##  (at your option) any later version.                                      ##
##                                                                           ##
##  You may not change or alter any portion of this comment or credits       ##
##  of supporting developers from this source code or any supporting         ##
##  source code which is considered copyrighted (c) material of the          ##
##  original comment or credit authors.                                      ##
##                                                                           ##
##  This program is distributed in the hope that it will be useful,          ##
##  but WITHOUT ANY WARRANTY; without even the implied warranty of           ##
##  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            ##
##  GNU General Public License for more details.                             ##
##                                                                           ##
##  You should have received a copy of the GNU General Public License        ##
##  along with this program; if not, write to the Free Software              ##
##  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA ##
###############################################################################
##  Author of this file: Freeform Solutions                                  ##
##  URL: http://www.freeformsolutions.ca/formulize                           ##
##  Project: Formulize                                                       ##
###############################################################################

// this file handles saving of submissions from the screen_multipage_pages page of the new admin UI

// if we aren't coming from what appears to be save.php, then return nothing
if(!isset($processedValues)) {
  return;
}


//print_r($_POST);
//print_r($processedValues);


$aid = intval($_POST['aid']);
$sid = $_POST['formulize_admin_key'];
$op = $_POST['formulize_admin_op'];
$index = $_POST['formulize_admin_index'];

$screens = $processedValues['screens'];


$screen_handler = xoops_getmodulehandler('multiPageScreen', 'formulize');
$screen = $screen_handler->get($sid);
// CHECK IF THE FORM IS LOCKED DOWN AND SCOOT IF SO
$form_handler = xoops_getmodulehandler('forms', 'formulize');
$formObject = $form_handler->get($screen->getVar('fid'));
if($formObject->getVar('lockedform')) {
  return;
}
// check if the user has permission to edit the form
if(!$gperm_handler->checkRight("edit_form", $screen->getVar('fid'), $groups, $mid)) {
  return;
}
// conditions...
// pagecons1 is the yes/no for conditions -- stored as: conditions[1]['pagecons']
// page1elements, page1ops, page1terms are arrays with all the condition details -- stored as: conditions[1]['details']['elements'][0..n], etc

$pages = array();
$pagetitles = array();
//$conditions = array();

foreach($screens as $k=>$v) {
	if(substr($k, 0, 10) == "pagetitle_") {
		$page_number = substr($k, 10);
		$pagetitles[$page_number] = $v;
		
		// grab any conditions for this page too
		// first delete any that we need to
		$conditionsDeleteParts = explode("_", $_POST['conditionsdelete']);
		$filter_key = 'pagefilter_'.$page_number;
		if($_POST['conditionsdelete'] != "" AND $conditionsDeleteParts[1] == $page_number) { // key 1 will be the page number where the X was clicked
		  // go through the passed filter settings starting from the one we need to remove, and shunt the rest down one space
		  // need to do this in a loop, because unsetting and key-sorting will maintain the key associations of the remaining high values above the one that was deleted
		  $originalCount = count($_POST[$filter_key.'_elements']);
		  for($i=$conditionsDeleteParts[2];$i<$originalCount;$i++) { // 2 is the X that was clicked for this page
		    if($i>$conditionsDeleteParts[2]) {
		      $_POST[$filter_key."_elements"][$i-1] = $_POST[$filter_key."_elements"][$i];
		      $_POST[$filter_key."_ops"][$i-1] = $_POST[$filter_key."_ops"][$i];
		      $_POST[$filter_key."_terms"][$i-1] = $_POST[$filter_key."_terms"][$i];
		      $_POST[$filter_key."_types"][$i-1] = $_POST[$filter_key."_types"][$i];
		    }
		    if($i==$conditionsDeleteParts[2] OR $i+1 == $originalCount) {
		      // first time through or last time through, unset things
		      unset($_POST[$filter_key."_elements"][$i]);
		      unset($_POST[$filter_key."_ops"][$i]);
		      unset($_POST[$filter_key."_terms"][$i]);
		      unset($_POST[$filter_key."_types"][$i]);
			  }
			}	
		}
		// now package everything up into the conditions we need
		if(count($_POST['pagefilter_'.$page_number.'_terms']) > 0) {
			foreach($_POST['pagefilter_'.$page_number.'_elements'] as $key=>$value) {
				$conditions[$page_number][0][$key] = $value;
			}
			foreach($_POST['pagefilter_'.$page_number.'_ops'] as $key=>$value) {
				$conditions[$page_number][1][$key] = $value;
			}
			foreach($_POST['pagefilter_'.$page_number.'_terms'] as $key=>$value) {
				$conditions[$page_number][2][$key] = $value;
			}
			foreach($_POST['pagefilter_'.$page_number.'_types'] as $key=>$value) {
				$conditions[$page_number][3][$key] = $value;
			}
		}
		// grab newly submitted ones
		if($_POST['new_pagefilter_'.$page_number.'_term']) {
			$conditions[$page_number][0][] = $_POST['new_pagefilter_'.$page_number.'_element'];
			$conditions[$page_number][1][] = $_POST['new_pagefilter_'.$page_number.'_op'];
			$conditions[$page_number][2][] = $_POST['new_pagefilter_'.$page_number.'_term'];
			$conditions[$page_number][3][] = "all";
		}
		if($_POST['new_pagefilter_'.$page_number.'_oom_term']) {
			$conditions[$page_number][0][] = $_POST['new_pagefilter_'.$page_number.'_oom_element'];
			$conditions[$page_number][1][] = $_POST['new_pagefilter_'.$page_number.'_oom_op'];
			$conditions[$page_number][2][] = $_POST['new_pagefilter_'.$page_number.'_oom_term'];
			$conditions[$page_number][3][] = "oom";
		}
		if(!isset($conditions[$page_number])) {
			$conditions[$page_number] = array();
		}

  }elseif(substr($k, 0, 4) == "page") { // page must come last since those letters are common to the beginning of everything
		$pages[substr($k, 4)] = unserialize($v); // arrays will have been serialized when they were put into processedValues
	} 
}

// handle the deletion of any conditions
if($_POST['conditionsdelete']) {
	
}


// get the new order of the elements...
$newOrder = explode("drawer-4[]=", str_replace("&", "", $_POST['pageorder']));
unset($newOrder[0]);
// newOrder will have keys corresponding to the new order, and values corresponding to the old order
// need to add in conditions handling here too
$newpages = array();
$newpagetitles = array();
$newconditions = array();
$pagesHaveBeenReordered = false;
foreach($pagetitles as $oldOrderNumber=>$values) {
	$newOrderNumber = array_search($oldOrderNumber,$newOrder);
	$newOrderNumberKey = $newOrderNumber-1;
	$newpages[$newOrderNumberKey] = $pages[$oldOrderNumber];
	$newpagetitles[$newOrderNumberKey] = $pagetitles[$oldOrderNumber];
	$newconditions[$newOrderNumberKey] = $conditions[$oldOrderNumber];
	if(($newOrderNumber - 1) != $oldOrderNumber) {
		$pagesHaveBeenReordered = true;
		$_POST['reload_multipage_pages'] = 1;
	}
}

if($pagesHaveBeenReordered) {
	$pages = $newpages;
	$pagetitles = $newpagetitles;
	$conditions = $newconditions;
}


// handle "deleting" conditions...
/*foreach($conditions as $pagenum=>$datapiece) {
   if(isset($datapiece['pagecons']) AND $datapiece['pagecons'] == "none") {
        $conditions[$pagenum]['details']['elements'] = array();
        $conditions[$pagenum]['details']['ops'] = array();
        $conditions[$pagenum]['details']['terms'] = array();
    }
}*/


// alter the information based on a user add or delete
switch ($op) {
	case "addpage":
    $pages[]=array();
    $pagetitles[]='New page';
    $conditions[]=array();
		break;
	case "delpage":
    array_splice($pages, $index, 1);
    array_splice($pagetitles, $index, 1);
    array_splice($conditions, $index, 1);
		break;
}


//print_r($pages);
//print_r($pagetitles);
//print_r($conditions);


$screen->setVar('pages',serialize($pages));
$screen->setVar('pagetitles',serialize($pagetitles));
$screen->setVar('conditions',serialize($conditions));


if(!$screen_handler->insert($screen)) {
  print "Error: could not save the screen properly: ".mysql_error();
}


// reload the page if the state has changed
if($op == "addpage" OR $op=="delpage" OR $_POST['reload_multipage_pages']) {
    print "/* eval */ reloadWithScrollPosition();";
}
?>