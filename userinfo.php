<?php

// Global variable for table object
$user = NULL;

//
// Table class for user
//
class cuser extends cTable {
	var $user_id;
	var $user_name;
	var $name;
	var $surname;
	var $password;
	var $userlevel_id;
	var $email_address;
	var $description;
	var $state;
	var $session;
	var $enterprise_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'user';
		$this->TableName = 'user';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`user`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// user_id
		$this->user_id = new cField('user', 'user', 'x_user_id', 'user_id', '`user_id`', '`user_id`', 3, -1, FALSE, '`user_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->user_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['user_id'] = &$this->user_id;

		// user_name
		$this->user_name = new cField('user', 'user', 'x_user_name', 'user_name', '`user_name`', '`user_name`', 200, -1, FALSE, '`user_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['user_name'] = &$this->user_name;

		// name
		$this->name = new cField('user', 'user', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['name'] = &$this->name;

		// surname
		$this->surname = new cField('user', 'user', 'x_surname', 'surname', '`surname`', '`surname`', 200, -1, FALSE, '`surname`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['surname'] = &$this->surname;

		// password
		$this->password = new cField('user', 'user', 'x_password', 'password', '`password`', '`password`', 200, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['password'] = &$this->password;

		// userlevel_id
		$this->userlevel_id = new cField('user', 'user', 'x_userlevel_id', 'userlevel_id', '`userlevel_id`', '`userlevel_id`', 3, -1, FALSE, '`userlevel_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->userlevel_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['userlevel_id'] = &$this->userlevel_id;

		// email_address
		$this->email_address = new cField('user', 'user', 'x_email_address', 'email_address', '`email_address`', '`email_address`', 200, -1, FALSE, '`email_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['email_address'] = &$this->email_address;

		// description
		$this->description = new cField('user', 'user', 'x_description', 'description', '`description`', '`description`', 200, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['description'] = &$this->description;

		// state
		$this->state = new cField('user', 'user', 'x_state', 'state', '`state`', '`state`', 202, -1, FALSE, '`state`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->state->OptionCount = 2;
		$this->fields['state'] = &$this->state;

		// session
		$this->session = new cField('user', 'user', 'x_session', 'session', '`session`', '`session`', 201, -1, FALSE, '`session`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['session'] = &$this->session;

		// enterprise_id
		$this->enterprise_id = new cField('user', 'user', 'x_enterprise_id', 'enterprise_id', '`enterprise_id`', '`enterprise_id`', 3, -1, FALSE, '`enterprise_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->enterprise_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['enterprise_id'] = &$this->enterprise_id;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`user`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'password')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'password') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('user_id', $rs))
				ew_AddFilter($where, ew_QuotedName('user_id', $this->DBID) . '=' . ew_QuotedValue($rs['user_id'], $this->user_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`user_id` = @user_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->user_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@user_id@", ew_AdjustSql($this->user_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "userlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "userlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("userview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("userview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "useradd.php?" . $this->UrlParm($parm);
		else
			$url = "useradd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("useredit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("useradd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("userdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "user_id:" . ew_VarToJson($this->user_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->user_id->CurrentValue)) {
			$sUrl .= "user_id=" . urlencode($this->user_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["user_id"]))
				$arKeys[] = ew_StripSlashes($_POST["user_id"]);
			elseif (isset($_GET["user_id"]))
				$arKeys[] = ew_StripSlashes($_GET["user_id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->user_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->user_name->setDbValue($rs->fields('user_name'));
		$this->name->setDbValue($rs->fields('name'));
		$this->surname->setDbValue($rs->fields('surname'));
		$this->password->setDbValue($rs->fields('password'));
		$this->userlevel_id->setDbValue($rs->fields('userlevel_id'));
		$this->email_address->setDbValue($rs->fields('email_address'));
		$this->description->setDbValue($rs->fields('description'));
		$this->state->setDbValue($rs->fields('state'));
		$this->session->setDbValue($rs->fields('session'));
		$this->enterprise_id->setDbValue($rs->fields('enterprise_id'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// user_id
		// user_name
		// name
		// surname
		// password
		// userlevel_id
		// email_address
		// description
		// state
		// session
		// enterprise_id
		// user_id

		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

		// user_name
		$this->user_name->ViewValue = $this->user_name->CurrentValue;
		$this->user_name->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// surname
		$this->surname->ViewValue = $this->surname->CurrentValue;
		$this->surname->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// userlevel_id
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->userlevel_id->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->userlevel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevel`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->userlevel_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->userlevel_id->ViewValue = $this->userlevel_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->userlevel_id->ViewValue = $this->userlevel_id->CurrentValue;
			}
		} else {
			$this->userlevel_id->ViewValue = NULL;
		}
		} else {
			$this->userlevel_id->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->userlevel_id->ViewCustomAttributes = "";

		// email_address
		$this->email_address->ViewValue = $this->email_address->CurrentValue;
		$this->email_address->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// state
		if (strval($this->state->CurrentValue) <> "") {
			$this->state->ViewValue = $this->state->OptionCaption($this->state->CurrentValue);
		} else {
			$this->state->ViewValue = NULL;
		}
		$this->state->ViewCustomAttributes = "";

		// session
		$this->session->ViewValue = $this->session->CurrentValue;
		$this->session->ViewCustomAttributes = "";

		// enterprise_id
		$this->enterprise_id->ViewValue = $this->enterprise_id->CurrentValue;
		$this->enterprise_id->ViewCustomAttributes = "";

		// user_id
		$this->user_id->LinkCustomAttributes = "";
		$this->user_id->HrefValue = "";
		$this->user_id->TooltipValue = "";

		// user_name
		$this->user_name->LinkCustomAttributes = "";
		$this->user_name->HrefValue = "";
		$this->user_name->TooltipValue = "";

		// name
		$this->name->LinkCustomAttributes = "";
		$this->name->HrefValue = "";
		$this->name->TooltipValue = "";

		// surname
		$this->surname->LinkCustomAttributes = "";
		$this->surname->HrefValue = "";
		$this->surname->TooltipValue = "";

		// password
		$this->password->LinkCustomAttributes = "";
		$this->password->HrefValue = "";
		$this->password->TooltipValue = "";

		// userlevel_id
		$this->userlevel_id->LinkCustomAttributes = "";
		$this->userlevel_id->HrefValue = "";
		$this->userlevel_id->TooltipValue = "";

		// email_address
		$this->email_address->LinkCustomAttributes = "";
		$this->email_address->HrefValue = "";
		$this->email_address->TooltipValue = "";

		// description
		$this->description->LinkCustomAttributes = "";
		$this->description->HrefValue = "";
		$this->description->TooltipValue = "";

		// state
		$this->state->LinkCustomAttributes = "";
		$this->state->HrefValue = "";
		$this->state->TooltipValue = "";

		// session
		$this->session->LinkCustomAttributes = "";
		$this->session->HrefValue = "";
		$this->session->TooltipValue = "";

		// enterprise_id
		$this->enterprise_id->LinkCustomAttributes = "";
		$this->enterprise_id->HrefValue = "";
		$this->enterprise_id->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// user_id
		$this->user_id->EditAttrs["class"] = "form-control";
		$this->user_id->EditCustomAttributes = "";
		$this->user_id->EditValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

		// user_name
		$this->user_name->EditAttrs["class"] = "form-control";
		$this->user_name->EditCustomAttributes = "";
		$this->user_name->EditValue = $this->user_name->CurrentValue;
		$this->user_name->PlaceHolder = ew_RemoveHtml($this->user_name->FldCaption());

		// name
		$this->name->EditAttrs["class"] = "form-control";
		$this->name->EditCustomAttributes = "";
		$this->name->EditValue = $this->name->CurrentValue;
		$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

		// surname
		$this->surname->EditAttrs["class"] = "form-control";
		$this->surname->EditCustomAttributes = "";
		$this->surname->EditValue = $this->surname->CurrentValue;
		$this->surname->PlaceHolder = ew_RemoveHtml($this->surname->FldCaption());

		// password
		$this->password->EditAttrs["class"] = "form-control ewPasswordStrength";
		$this->password->EditCustomAttributes = "";
		$this->password->EditValue = $this->password->CurrentValue;
		$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

		// userlevel_id
		$this->userlevel_id->EditAttrs["class"] = "form-control";
		$this->userlevel_id->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->userlevel_id->EditValue = $Language->Phrase("PasswordMask");
		} else {
		}

		// email_address
		$this->email_address->EditAttrs["class"] = "form-control";
		$this->email_address->EditCustomAttributes = "";
		$this->email_address->EditValue = $this->email_address->CurrentValue;
		$this->email_address->PlaceHolder = ew_RemoveHtml($this->email_address->FldCaption());

		// description
		$this->description->EditAttrs["class"] = "form-control";
		$this->description->EditCustomAttributes = "";
		$this->description->EditValue = $this->description->CurrentValue;
		$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

		// state
		$this->state->EditCustomAttributes = "";
		$this->state->EditValue = $this->state->Options(FALSE);

		// session
		$this->session->EditAttrs["class"] = "form-control";
		$this->session->EditCustomAttributes = "";
		$this->session->EditValue = $this->session->CurrentValue;
		$this->session->PlaceHolder = ew_RemoveHtml($this->session->FldCaption());

		// enterprise_id
		$this->enterprise_id->EditAttrs["class"] = "form-control";
		$this->enterprise_id->EditCustomAttributes = "";
		$this->enterprise_id->EditValue = $this->enterprise_id->CurrentValue;
		$this->enterprise_id->PlaceHolder = ew_RemoveHtml($this->enterprise_id->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
					if ($this->user_name->Exportable) $Doc->ExportCaption($this->user_name);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->surname->Exportable) $Doc->ExportCaption($this->surname);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->userlevel_id->Exportable) $Doc->ExportCaption($this->userlevel_id);
					if ($this->email_address->Exportable) $Doc->ExportCaption($this->email_address);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
					if ($this->state->Exportable) $Doc->ExportCaption($this->state);
					if ($this->session->Exportable) $Doc->ExportCaption($this->session);
					if ($this->enterprise_id->Exportable) $Doc->ExportCaption($this->enterprise_id);
				} else {
					if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
					if ($this->user_name->Exportable) $Doc->ExportCaption($this->user_name);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->surname->Exportable) $Doc->ExportCaption($this->surname);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->userlevel_id->Exportable) $Doc->ExportCaption($this->userlevel_id);
					if ($this->email_address->Exportable) $Doc->ExportCaption($this->email_address);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
					if ($this->state->Exportable) $Doc->ExportCaption($this->state);
					if ($this->enterprise_id->Exportable) $Doc->ExportCaption($this->enterprise_id);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
						if ($this->user_name->Exportable) $Doc->ExportField($this->user_name);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->surname->Exportable) $Doc->ExportField($this->surname);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->userlevel_id->Exportable) $Doc->ExportField($this->userlevel_id);
						if ($this->email_address->Exportable) $Doc->ExportField($this->email_address);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
						if ($this->state->Exportable) $Doc->ExportField($this->state);
						if ($this->session->Exportable) $Doc->ExportField($this->session);
						if ($this->enterprise_id->Exportable) $Doc->ExportField($this->enterprise_id);
					} else {
						if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
						if ($this->user_name->Exportable) $Doc->ExportField($this->user_name);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->surname->Exportable) $Doc->ExportField($this->surname);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->userlevel_id->Exportable) $Doc->ExportField($this->userlevel_id);
						if ($this->email_address->Exportable) $Doc->ExportField($this->email_address);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
						if ($this->state->Exportable) $Doc->ExportField($this->state);
						if ($this->enterprise_id->Exportable) $Doc->ExportField($this->enterprise_id);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
