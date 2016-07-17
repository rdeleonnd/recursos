<?php

// Global variable for table object
$informacion_familiar = NULL;

//
// Table class for informacion_familiar
//
class cinformacion_familiar extends cTable {
	var $informacion_familiar_id;
	var $nombre;
	var $fecha_nacimiento;
	var $afinidad_id;
	var $telefono;
	var $lugar_trabajo;
	var $empleado_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'informacion_familiar';
		$this->TableName = 'informacion_familiar';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`informacion_familiar`";
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

		// informacion_familiar_id
		$this->informacion_familiar_id = new cField('informacion_familiar', 'informacion_familiar', 'x_informacion_familiar_id', 'informacion_familiar_id', '`informacion_familiar_id`', '`informacion_familiar_id`', 3, -1, FALSE, '`informacion_familiar_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->informacion_familiar_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['informacion_familiar_id'] = &$this->informacion_familiar_id;

		// nombre
		$this->nombre = new cField('informacion_familiar', 'informacion_familiar', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// fecha_nacimiento
		$this->fecha_nacimiento = new cField('informacion_familiar', 'informacion_familiar', 'x_fecha_nacimiento', 'fecha_nacimiento', '`fecha_nacimiento`', 'DATE_FORMAT(`fecha_nacimiento`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_nacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_nacimiento'] = &$this->fecha_nacimiento;

		// afinidad_id
		$this->afinidad_id = new cField('informacion_familiar', 'informacion_familiar', 'x_afinidad_id', 'afinidad_id', '`afinidad_id`', '`afinidad_id`', 3, -1, FALSE, '`afinidad_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->afinidad_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['afinidad_id'] = &$this->afinidad_id;

		// telefono
		$this->telefono = new cField('informacion_familiar', 'informacion_familiar', 'x_telefono', 'telefono', '`telefono`', '`telefono`', 200, -1, FALSE, '`telefono`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['telefono'] = &$this->telefono;

		// lugar_trabajo
		$this->lugar_trabajo = new cField('informacion_familiar', 'informacion_familiar', 'x_lugar_trabajo', 'lugar_trabajo', '`lugar_trabajo`', '`lugar_trabajo`', 200, -1, FALSE, '`lugar_trabajo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['lugar_trabajo'] = &$this->lugar_trabajo;

		// empleado_id
		$this->empleado_id = new cField('informacion_familiar', 'informacion_familiar', 'x_empleado_id', 'empleado_id', '`empleado_id`', '`empleado_id`', 3, -1, FALSE, '`empleado_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->empleado_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['empleado_id'] = &$this->empleado_id;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`informacion_familiar`";
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
			if (array_key_exists('informacion_familiar_id', $rs))
				ew_AddFilter($where, ew_QuotedName('informacion_familiar_id', $this->DBID) . '=' . ew_QuotedValue($rs['informacion_familiar_id'], $this->informacion_familiar_id->FldDataType, $this->DBID));
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
		return "`informacion_familiar_id` = @informacion_familiar_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->informacion_familiar_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@informacion_familiar_id@", ew_AdjustSql($this->informacion_familiar_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "informacion_familiarlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "informacion_familiarlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("informacion_familiarview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("informacion_familiarview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "informacion_familiaradd.php?" . $this->UrlParm($parm);
		else
			$url = "informacion_familiaradd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("informacion_familiaredit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("informacion_familiaradd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("informacion_familiardelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "informacion_familiar_id:" . ew_VarToJson($this->informacion_familiar_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->informacion_familiar_id->CurrentValue)) {
			$sUrl .= "informacion_familiar_id=" . urlencode($this->informacion_familiar_id->CurrentValue);
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
			if ($isPost && isset($_POST["informacion_familiar_id"]))
				$arKeys[] = ew_StripSlashes($_POST["informacion_familiar_id"]);
			elseif (isset($_GET["informacion_familiar_id"]))
				$arKeys[] = ew_StripSlashes($_GET["informacion_familiar_id"]);
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
			$this->informacion_familiar_id->CurrentValue = $key;
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
		$this->informacion_familiar_id->setDbValue($rs->fields('informacion_familiar_id'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
		$this->afinidad_id->setDbValue($rs->fields('afinidad_id'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->lugar_trabajo->setDbValue($rs->fields('lugar_trabajo'));
		$this->empleado_id->setDbValue($rs->fields('empleado_id'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// informacion_familiar_id

		$this->informacion_familiar_id->CellCssStyle = "white-space: nowrap;";

		// nombre
		// fecha_nacimiento
		// afinidad_id
		// telefono
		// lugar_trabajo
		// empleado_id
		// informacion_familiar_id

		$this->informacion_familiar_id->ViewValue = $this->informacion_familiar_id->CurrentValue;
		$this->informacion_familiar_id->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 7);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// afinidad_id
		if (strval($this->afinidad_id->CurrentValue) <> "") {
			$sFilterWrk = "`afinidad_id`" . ew_SearchString("=", $this->afinidad_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `afinidad_id`, `afinidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `afinidad`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->afinidad_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->afinidad_id->ViewValue = $this->afinidad_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->afinidad_id->ViewValue = $this->afinidad_id->CurrentValue;
			}
		} else {
			$this->afinidad_id->ViewValue = NULL;
		}
		$this->afinidad_id->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// lugar_trabajo
		$this->lugar_trabajo->ViewValue = $this->lugar_trabajo->CurrentValue;
		$this->lugar_trabajo->ViewCustomAttributes = "";

		// empleado_id
		$this->empleado_id->ViewValue = $this->empleado_id->CurrentValue;
		$this->empleado_id->ViewCustomAttributes = "";

		// informacion_familiar_id
		$this->informacion_familiar_id->LinkCustomAttributes = "";
		$this->informacion_familiar_id->HrefValue = "";
		$this->informacion_familiar_id->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->LinkCustomAttributes = "";
		$this->fecha_nacimiento->HrefValue = "";
		$this->fecha_nacimiento->TooltipValue = "";

		// afinidad_id
		$this->afinidad_id->LinkCustomAttributes = "";
		$this->afinidad_id->HrefValue = "";
		$this->afinidad_id->TooltipValue = "";

		// telefono
		$this->telefono->LinkCustomAttributes = "";
		$this->telefono->HrefValue = "";
		$this->telefono->TooltipValue = "";

		// lugar_trabajo
		$this->lugar_trabajo->LinkCustomAttributes = "";
		$this->lugar_trabajo->HrefValue = "";
		$this->lugar_trabajo->TooltipValue = "";

		// empleado_id
		$this->empleado_id->LinkCustomAttributes = "";
		$this->empleado_id->HrefValue = "";
		$this->empleado_id->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// informacion_familiar_id
		$this->informacion_familiar_id->EditAttrs["class"] = "form-control";
		$this->informacion_familiar_id->EditCustomAttributes = "";
		$this->informacion_familiar_id->EditValue = $this->informacion_familiar_id->CurrentValue;
		$this->informacion_familiar_id->ViewCustomAttributes = "";

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = $this->nombre->CurrentValue;
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

		// fecha_nacimiento
		$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
		$this->fecha_nacimiento->EditCustomAttributes = "";
		$this->fecha_nacimiento->EditValue = ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

		// afinidad_id
		$this->afinidad_id->EditAttrs["class"] = "form-control";
		$this->afinidad_id->EditCustomAttributes = "";

		// telefono
		$this->telefono->EditAttrs["class"] = "form-control";
		$this->telefono->EditCustomAttributes = "";
		$this->telefono->EditValue = $this->telefono->CurrentValue;
		$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

		// lugar_trabajo
		$this->lugar_trabajo->EditAttrs["class"] = "form-control";
		$this->lugar_trabajo->EditCustomAttributes = "";
		$this->lugar_trabajo->EditValue = $this->lugar_trabajo->CurrentValue;
		$this->lugar_trabajo->PlaceHolder = ew_RemoveHtml($this->lugar_trabajo->FldCaption());

		// empleado_id
		$this->empleado_id->EditAttrs["class"] = "form-control";
		$this->empleado_id->EditCustomAttributes = "";
		$this->empleado_id->EditValue = $this->empleado_id->CurrentValue;
		$this->empleado_id->PlaceHolder = ew_RemoveHtml($this->empleado_id->FldCaption());

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
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->afinidad_id->Exportable) $Doc->ExportCaption($this->afinidad_id);
					if ($this->telefono->Exportable) $Doc->ExportCaption($this->telefono);
					if ($this->lugar_trabajo->Exportable) $Doc->ExportCaption($this->lugar_trabajo);
					if ($this->empleado_id->Exportable) $Doc->ExportCaption($this->empleado_id);
				} else {
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->afinidad_id->Exportable) $Doc->ExportCaption($this->afinidad_id);
					if ($this->telefono->Exportable) $Doc->ExportCaption($this->telefono);
					if ($this->lugar_trabajo->Exportable) $Doc->ExportCaption($this->lugar_trabajo);
					if ($this->empleado_id->Exportable) $Doc->ExportCaption($this->empleado_id);
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
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->afinidad_id->Exportable) $Doc->ExportField($this->afinidad_id);
						if ($this->telefono->Exportable) $Doc->ExportField($this->telefono);
						if ($this->lugar_trabajo->Exportable) $Doc->ExportField($this->lugar_trabajo);
						if ($this->empleado_id->Exportable) $Doc->ExportField($this->empleado_id);
					} else {
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->afinidad_id->Exportable) $Doc->ExportField($this->afinidad_id);
						if ($this->telefono->Exportable) $Doc->ExportField($this->telefono);
						if ($this->lugar_trabajo->Exportable) $Doc->ExportField($this->lugar_trabajo);
						if ($this->empleado_id->Exportable) $Doc->ExportField($this->empleado_id);
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
