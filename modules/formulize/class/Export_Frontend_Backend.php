<h3 style="font-family:verdana;color:rgb(0,100,100)">Export Application Utility</h3>
<p style="font-family:arial;color:rgb(100,100,0);font-size:medium;">
    This utility will automatically export all basic tables of the selected application.</br>
    However, you need to a make decision with regard to the following <u>dynamic forms</u>, </br>
    forms selected will be exported with data and other names we will only export empty forms</br>
	Furthermore, you need also to select the group lists. 
</p>
<table class="formTable">
<body>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
<?php
include  'PDO_Conn.php';//Include the Connection File
//Pass Application Id from Formulize
$appid =$_GET['aid'];
// Check if Application Exist
$row = sqlQuery("SELECT count(appid) as appid FROM ".Prefix."_formulize_applications WHERE appid = :id;","appid","$appid");
$i = 0;
if ($row[0]> 0)	
{
	// Get Form id's to export
	$row = sqlQuery("SELECT fid FROM ".Prefix."_formulize_application_form_link WHERE appid = :id;","fid","$appid");
	$formsid = "(".(implode(",",$row)).")";
	session_start();
	$_SESSION['fid'] = $formsid;
	//echo $formsid;
	$rowset = sqlQuery("select desc_form,form_handle from ".Prefix."_formulize_id where id_form in $formsid order by desc_form");
	$rowset1= sqlQuery("select gl_id,gl_name from ".Prefix."_group_lists order by gl_name");
	if (!empty($rowset))
		{
			echo "<tr><td><label>Select Forms</label></td>";
		if (!empty($rowset1)) {echo "<td><label>Select Groups</label></td>";}
		echo "</tr>";
		echo "<td><select multiple=\"multiple\" name=FormIDS[] size=12>";
		foreach ($rowset as $key){echo "<option value=$key[form_handle]>$key[desc_form]</option>";}
	    echo "</select><br></td>";
	    if (!empty($rowset1)){
		echo "<td><select multiple=\"multiple\" name=GroupID[] size=12>";
	    foreach ($rowset1 as $key){echo "<option value=$key[gl_id]>$key[gl_name]</option>";}
	    echo "</select><br></td></tr>";}
		}
}
else
{
	exit("Error [0] : Invalid Application Id Contact the Administrator ...No Export File is Created ... Exiting");
}
?>	
<tr>
<td><input type='submit' name='formSubmit' value='Export' id='export'></td>
<td><input type="button" id='download' value="Download" onclick='myfunction()' /></td>
</tr>
</tbody>
</table>
<!-- Progress bar holder -->
<div id="progress" style="width:500px;border:3px solid #ccc;"></div>
<!-- Progress information -->
<div id="information" style="width"></div>
<script>
 function hide(button){document.getElementById(button).hidden=true;}
 function unhide(button){document.getElementById(button).hidden=false;}
 function myfunction()
 { var tmp =<?php echo $appid?>;
   // link need to be updated
   url ='download.php?aid='+tmp;
   window.open(url);
 }
</script>
</form>
</body>

<?php
$text ="";
echo "<script language='javascript'> hide('download');</script>";
if (isset($_POST['formSubmit'])) 
{
	global $i;
	echo "<script language='javascript'> hide('download');</script>";
	export();
	//output($text);
	echo "<script language='javascript'> hide('export');</script>";
	progress(100,$i);
	echo "<script language='javascript'> unhide('download');</script>";
}
Function output($output)
{
	///echo $output;
	$file_name ="Application -".$_GET['aid'];
	$f = fopen($file_name, "w"); 
	fwrite($f,$output);
	fclose($f);
} 
// Function sqlQuery(SQL Statement,Column Name, Bind Variable)
Function sqlQuery($sql,$colname = NULL,$bindvar = NULL)
{
	global $i;
	$conn=new Connection ();
	$Query=$conn->connect()->prepare($sql) ;
	if (!empty($bindvar)){
	$Query->bindValue(":id",$bindvar,PDO::PARAM_STR);}
	$Query->execute();
	$returnrow=Array();
	if (is_null($colname)){$returnrow=Array(Array());}
	array_pop($returnrow);
	while ($row=$Query->fetch(\PDO::FETCH_ASSOC))
	{ 
		if (is_null($colname))	{array_push($returnrow,$row);} 
		else{array_push($returnrow,$row[$colname]);}
	}
    // Calculate the percent progress
    $percent = intval($i);
    progress($percent,$i);
    $i++;
	return $returnrow;
}

//progress bar
Function progress($pcnt,$tran)
{
	// Javascript for updating the progress bar and information
	$tran = $tran."Transactions Processed";
	if ($pcnt == 100) { $tran = "Process completed";}
     echo '<script language="javascript">
    document.getElementById("progress").innerHTML="<div style=\"width:'.$pcnt.'%;background-color:#f00;\">&nbsp;</div>";
    document.getElementById("information").innerHTML="'.$tran.'";
	</script>';
	echo str_repeat(' ',1024);
	// Send output to browser immediately
	//ob_flush();
	 sleep(.03);
}

// Function chk_integrity($tables) Checks that all tables exist, otherwise cause the system to exit
Function chk_integrity($tables,$prefix)
{
	$flag = true;
	foreach ($tables as $table)
	{
		$row = explode("/",$table);
		$num = sqlQuery("SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME= '$prefix$row[2]';","cnt");
		if ($num[0]==0) 
	    {
			echo "Table : $row[2] (Error Table does not exist)</br>";
			$flag= false;
		}
	}
	
	return $flag;
}
function export () {
	///echo "Here";
	global $appid; 
	global $MODID;
	global $formsid;
	global $text;
	session_start();
	$appid=$_GET['aid'];//Put Real App ID 
	$formsid=$_SESSION['fid'];
	///session_destroy();
	$MODID=MOD_ID;//MOD ID 
	$formhandels = "()";
	$formselect = $_POST['FormIDS'];
	$groupselect="('".(implode("','",$_POST['GroupID']))."')";
	$formhandels = "('".(implode("','",$formselect))."')";
	$flid = Array(); // framework id array to export
	$exclude = Array(); 
	$linksid ="()"; 	// No forms
	$screenid = "()"; 	// No Screens as there are no forms
	$elementsid ="()"; 	// No Elements
	$savedviews ="()"; 	// No Saved Views
	$gperms ="()"; 		// No Group Permissions
	$totaltrans=5;      // Totals used in Progress Bar for future use
	// Query basic variables
	if ($formsid !== "()")
	{	//Links
		$row = sqlQuery("SELECT fl_id FROM ".Prefix."_formulize_framework_links Where fl_form1_id IN $formsid or fl_form2_id IN $formsid;","fl_id");
		$linksid = "(".(implode(",",$row)).")";
		//Screens
		$row = sqlQuery("SELECT sid FROM ".Prefix."_formulize_screen_form Where formid in $formsid;","sid");
		$screenid = "(".(implode(",",$row)).")";
		//elements
		$row = sqlQuery("SELECT ele_id FROM ".Prefix."_formulize Where id_form in $formsid","ele_id");
		$elementsid = "(".(implode(",",$row)).")";
		//views
		$row = sqlQuery("SELECT sv_id FROM ".Prefix."_formulize_saved_views WHERE sv_mainform in $formsid or sv_formframe in $formsid;","sv_id");
		$savedviews = "(".(implode(",",$row)).")";
		//group permissions
		$row = sqlQuery("SELECT gperm_id FROM ".Prefix."_group_permission WHERE gperm_itemid in $formsid and gperm_modid = $MODID;","gperm_id");
		$gperms = "(".(implode(",",$row)).")";

}

	// tables to export in the format table_name/where criteria field/ criteria condition
	// 0-No Padding/0-Single Insert 1-Multi Row Insert/Table Name/Operator/Where Field/Where Condition
	$tables=array ("0/0/_formulize_applications/appid/=/$appid","0/0/_formulize_id/id_form/in/$formsid","0/0/_formulize_application_form_link/appid/=/$appid",
	"0/0/_formulize/id_form/in/$formsid", "0/0/_formulize_frameworks/frame_id/in/$linksid","0/0/_formulize_framework_links/fl_id/in/$linksid",
	"0/0/_groups","0/0/_group_lists/gl_id/in/$groupselect","0/0/_formulize_advanced_calculations/fid/in/$formsid","0/0/_formulize_group_filters/fid/in/$formsid",
	"0/0/_formulize_groupscope_settings/fid/in/$formsid","0/0/_formulize_screen/fid/in/$formsid","0/0/_formulize_other/ele_id/in/$elementsid",
	"0/0/_formulize_notification_conditions/not_cons_fid/in/$formsid","0/0/_formulize_entry_owner_groups/fid/in/$formsid",
	"0/0/_formulize_screen_listofentries/sid/in/$screenid","0/0/_formulize_screen_multipage/sid/in/$screenid",
	"0/0/_formulize_screen_form/formid/in/$formsid","0/0/_formulize_saved_views/sv_id/in/$savedviews",
	"0/0/_group_permission/gperm_id/in/$gperms");

	//Add Dynamic Tables based on captured ele_handle in formulize_id table (User Selected to incorporate data)
	$row = sqlQuery("select CONCAT('Create table^',form_handle,'/1/_formulize_',form_handle) as form_handle
	from ".Prefix."_formulize_id where form_handle is not NULL and id_form in $formsid and form_handle in $formhandels","form_handle");
	
	$tables = array_merge($tables,$row);
	
	//Add Dynamic Tables based on captured ele_handle in formulize_id table (empty tables)
	$row = sqlQuery("select CONCAT('Create table^',form_handle,'/2/_formulize_',form_handle,'/1/=/2') as form_handle
	from ".Prefix."_formulize_id where form_handle is not NULL and id_form in $formsid and form_handle not in $formhandels","form_handle");
	
	$tables = array_merge($tables,$row);
	$lines = Array();
	$prefix= "".Prefix."";
	if (!chk_integrity($tables,$prefix)) {return false;}
	
	foreach ($tables as $t)
	{
		$table = explode ('/',$t);
		$criteria = "Where $table[3] $table[4] $table[5]";
		
		if (is_null($table[3])) {$criteria ="";}
		if ($table[5] !== "()")
		{
			$lines=array_merge($lines,expTable($prefix,$table[0],$table[1],$table[2],$exclude,$criteria));
		///echo "Here";
		}
	}

	$text =implode("\n",$lines);
	output($text);
	return true;
}

// Function expTable(padding,insert style,table name, excluded columns array, criteria for selection, post selection fields character replacement array)
Function expTable($Prefix,$padText,$insStyle,$Table_Name,$Exclude_Columns = Null,$Criteria = Null)
{///echo "here";
	// Replace all (;) with (&) for the following fields 
	$replace=Array("not_cons_con","gl_name","fltr_grps","steptitles","steps","ele_filtersettings","ele_value","fltr_grptitles","filter",
	"pages","pagetitles","conditions","ele_uitext","ele_delim");
	// Pad all with (^) e.g 1 = ^1^ 
	$padding=Array("ele_handle","form_handle","not_cons_uid");
	$inslist = Array();
	// Table Column Names
	$row = sqlQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$Prefix$Table_Name';","COLUMN_NAME");
	////echo "h2";
	$row = array_diff($row,$Exclude_Columns);
	$keys=$row;
	$values = "`".implode("`,`",$row)."`";
	$Tablename="Prefix".$Table_Name;
	$insStatment = "INSERT INTO $Tablename (`".implode("`,`",$row)."`) VALUES ("; //Standard Insert Statement
	// Format Special Insert statement (Formulize_1,_2 ..etc)
	////echo 'd';
	if ($padText !=="0") {$insStatment = "$padText," .implode(",", array_slice($row,5))."^INSERT INTO Table_Name VALUES ";}
	///echo $Criteria;
	///echo "Select $values from $Prefix$Table_Name $Criteria;";
	if ($Table_Name=='_formulize_applications' || $Table_Name=='_formulize_application_form_link'){
	$Criteria=explode('=',$Criteria);
	$rowset = sqlQuery("Select $values from $Prefix$Table_Name $Criteria[0]= :id;",null,$Criteria[1]);}
	else{
	 $rowset = sqlQuery("Select $values from $Prefix$Table_Name $Criteria;");}
///	print_r($rowset);
	///echo 'e';
	if ($insStyle =="1") {$insrow=$insStatment;}
	///echo "here";
	// Export only table structure
	if ($insStyle =="2")
      {
		$insStatment = "$padText," .implode(",", array_slice($row,5))."^;";
		array_push($inslist,$insStatment);
		return $inslist;
	  }
	  // Create the insert statements for the required table
	foreach($rowset as $rowset)
	{		
		if ($insStyle =="0") {$insrow=$insStatment;}
		if ($insStyle =="1") {$insrow .="(";}
		$k=0;
		foreach($rowset as $row)
		{			
			$field=$keys[$k];
			$onefield=$row;
			// apply changes to the required fields
			if (is_null($onefield)){$onefield="NULL";}
			$onefield=str_replace("'","\'",$onefield);
			///if ($field=='not_cons_con'){echo "Yes";}
			///$onefield=str_replace(array("\n","\r"), "", $onefield);
			if (in_array($field,$replace)){$onefield=str_replace(";","&",$onefield);}
			if (in_array($field,$padding)){$onefield="^".$onefield."^";}
			if ($onefield !="NULL"){$onefield ="'".$onefield."'";}
			$insrow .="$onefield,";
			$k++;
		}
		if ($insStyle =="0")
		{
			$insrow= substr($insrow,0,strlen($insrow)-1).");";
			array_push($inslist,$insrow);
		}
		if ($insStyle =="1") { $insrow= substr($insrow,0,strlen($insrow)-1)."),";}
	}
	if ($insStyle =="1")
	{
		$insrow= substr($insrow,0,strlen($insrow)-1).";";
		array_push($inslist,$insrow);
	}
	///print_r($inslist);
	return $inslist;
}
?>