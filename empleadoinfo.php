<?php

// Global variable for table object
$empleado = NULL;

//
// Table class for empleado
//
class cempleado extends cTable {
	var $empleado_id;
	var $codigo;
	var $cui;
	var $nombre;
	var $apellido;
	var $direccion;
	var $departamento_origen_id;
	var $municipio_id;
	var $telefono_residencia;
	var $telefono_celular;
	var $fecha_nacimiento;
	var $nacionalidad;
	var $estado_civil;
	var $sexo;
	var $igss;
	var $nit;
	var $licencia_conducir;
	var $area_id;
	var $departmento_id;
	var $seccion_id;
	var $puesto_id;
	var $observaciones;
	var $tipo_sangre_id;
	var $estado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'empleado';
		$this->TableName = 'empleado';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`empleado`";
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

		// empleado_id
		$this->empleado_id = new cField('empleado', 'empleado', 'x_empleado_id', 'empleado_id', '`empleado_id`', '`empleado_id`', 3, -1, FALSE, '`empleado_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->empleado_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['empleado_id'] = &$this->empleado_id;

		// codigo
		$this->codigo = new cField('empleado', 'empleado', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// cui
		$this->cui = new cField('empleado', 'empleado', 'x_cui', 'cui', '`cui`', '`cui`', 200, -1, FALSE, '`cui`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['cui'] = &$this->cui;

		// nombre
		$this->nombre = new cField('empleado', 'empleado', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// apellido
		$this->apellido = new cField('empleado', 'empleado', 'x_apellido', 'apellido', '`apellido`', '`apellido`', 200, -1, FALSE, '`apellido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['apellido'] = &$this->apellido;

		// direccion
		$this->direccion = new cField('empleado', 'empleado', 'x_direccion', 'direccion', '`direccion`', '`direccion`', 200, -1, FALSE, '`direccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['direccion'] = &$this->direccion;

		// departamento_origen_id
		$this->departamento_origen_id = new cField('empleado', 'empleado', 'x_departamento_origen_id', 'departamento_origen_id', '`departamento_origen_id`', '`departamento_origen_id`', 3, -1, FALSE, '`departamento_origen_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->departamento_origen_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['departamento_origen_id'] = &$this->departamento_origen_id;

		// municipio_id
		$this->municipio_id = new cField('empleado', 'empleado', 'x_municipio_id', 'municipio_id', '`municipio_id`', '`municipio_id`', 3, -1, FALSE, '`municipio_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->municipio_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['municipio_id'] = &$this->municipio_id;

		// telefono_residencia
		$this->telefono_residencia = new cField('empleado', 'empleado', 'x_telefono_residencia', 'telefono_residencia', '`telefono_residencia`', '`telefono_residencia`', 200, -1, FALSE, '`telefono_residencia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['telefono_residencia'] = &$this->telefono_residencia;

		// telefono_celular
		$this->telefono_celular = new cField('empleado', 'empleado', 'x_telefono_celular', 'telefono_celular', '`telefono_celular`', '`telefono_celular`', 200, -1, FALSE, '`telefono_celular`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['telefono_celular'] = &$this->telefono_celular;

		// fecha_nacimiento
		$this->fecha_nacimiento = new cField('empleado', 'empleado', 'x_fecha_nacimiento', 'fecha_nacimiento', '`fecha_nacimiento`', 'DATE_FORMAT(`fecha_nacimiento`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_nacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_nacimiento'] = &$this->fecha_nacimiento;

		// nacionalidad
		$this->nacionalidad = new cField('empleado', 'empleado', 'x_nacionalidad', 'nacionalidad', '`nacionalidad`', '`nacionalidad`', 3, -1, FALSE, '`nacionalidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->nacionalidad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nacionalidad'] = &$this->nacionalidad;

		// estado_civil
		$this->estado_civil = new cField('empleado', 'empleado', 'x_estado_civil', 'estado_civil', '`estado_civil`', '`estado_civil`', 3, -1, FALSE, '`estado_civil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->estado_civil->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estado_civil'] = &$this->estado_civil;

		// sexo
		$this->sexo = new cField('empleado', 'empleado', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 3, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sexo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sexo'] = &$this->sexo;

		// igss
		$this->igss = new cField('empleado', 'empleado', 'x_igss', 'igss', '`igss`', '`igss`', 200, -1, FALSE, '`igss`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['igss'] = &$this->igss;

		// nit
		$this->nit = new cField('empleado', 'empleado', 'x_nit', 'nit', '`nit`', '`nit`', 200, -1, FALSE, '`nit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['nit'] = &$this->nit;

		// licencia_conducir
		$this->licencia_conducir = new cField('empleado', 'empleado', 'x_licencia_conducir', 'licencia_conducir', '`licencia_conducir`', '`licencia_conducir`', 200, -1, FALSE, '`licencia_conducir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['licencia_conducir'] = &$this->licencia_conducir;

		// area_id
		$this->area_id = new cField('empleado', 'empleado', 'x_area_id', 'area_id', '`area_id`', '`area_id`', 3, -1, FALSE, '`area_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->area_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['area_id'] = &$this->area_id;

		// departmento_id
		$this->departmento_id = new cField('empleado', 'empleado', 'x_departmento_id', 'departmento_id', '`departmento_id`', '`departmento_id`', 3, -1, FALSE, '`departmento_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->departmento_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['departmento_id'] = &$this->departmento_id;

		// seccion_id
		$this->seccion_id = new cField('empleado', 'empleado', 'x_seccion_id', 'seccion_id', '`seccion_id`', '`seccion_id`', 3, -1, FALSE, '`seccion_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->seccion_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['seccion_id'] = &$this->seccion_id;

		// puesto_id
		$this->puesto_id = new cField('empleado', 'empleado', 'x_puesto_id', 'puesto_id', '`puesto_id`', '`puesto_id`', 3, -1, FALSE, '`puesto_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->puesto_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['puesto_id'] = &$this->puesto_id;

		// observaciones
		$this->observaciones = new cField('empleado', 'empleado', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 201, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['observaciones'] = &$this->observaciones;

		// tipo_sangre_id
		$this->tipo_sangre_id = new cField('empleado', 'empleado', 'x_tipo_sangre_id', 'tipo_sangre_id', '`tipo_sangre_id`', '`tipo_sangre_id`', 3, -1, FALSE, '`tipo_sangre_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipo_sangre_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tipo_sangre_id'] = &$this->tipo_sangre_id;

		// estado
		$this->estado = new cField('empleado', 'empleado', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['estado'] = &$this->estado;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`empleado`";
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
			if (array_key_exists('empleado_id', $rs))
				ew_AddFilter($where, ew_QuotedName('empleado_id', $this->DBID) . '=' . ew_QuotedValue($rs['empleado_id'], $this->empleado_id->FldDataType, $this->DBID));
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
		return "`empleado_id` = @empleado_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->empleado_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@empleado_id@", ew_AdjustSql($this->empleado_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "empleadolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "empleadolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("empleadoview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("empleadoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "empleadoadd.php?" . $this->UrlParm($parm);
		else
			$url = "empleadoadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("empleadoedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("empleadoadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("empleadodelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "empleado_id:" . ew_VarToJson($this->empleado_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->empleado_id->CurrentValue)) {
			$sUrl .= "empleado_id=" . urlencode($this->empleado_id->CurrentValue);
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
			if ($isPost && isset($_POST["empleado_id"]))
				$arKeys[] = ew_StripSlashes($_POST["empleado_id"]);
			elseif (isset($_GET["empleado_id"]))
				$arKeys[] = ew_StripSlashes($_GET["empleado_id"]);
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
			$this->empleado_id->CurrentValue = $key;
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
		$this->empleado_id->setDbValue($rs->fields('empleado_id'));
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->cui->setDbValue($rs->fields('cui'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->apellido->setDbValue($rs->fields('apellido'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->departamento_origen_id->setDbValue($rs->fields('departamento_origen_id'));
		$this->municipio_id->setDbValue($rs->fields('municipio_id'));
		$this->telefono_residencia->setDbValue($rs->fields('telefono_residencia'));
		$this->telefono_celular->setDbValue($rs->fields('telefono_celular'));
		$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
		$this->nacionalidad->setDbValue($rs->fields('nacionalidad'));
		$this->estado_civil->setDbValue($rs->fields('estado_civil'));
		$this->sexo->setDbValue($rs->fields('sexo'));
		$this->igss->setDbValue($rs->fields('igss'));
		$this->nit->setDbValue($rs->fields('nit'));
		$this->licencia_conducir->setDbValue($rs->fields('licencia_conducir'));
		$this->area_id->setDbValue($rs->fields('area_id'));
		$this->departmento_id->setDbValue($rs->fields('departmento_id'));
		$this->seccion_id->setDbValue($rs->fields('seccion_id'));
		$this->puesto_id->setDbValue($rs->fields('puesto_id'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->tipo_sangre_id->setDbValue($rs->fields('tipo_sangre_id'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// empleado_id

		$this->empleado_id->CellCssStyle = "white-space: nowrap;";

		// codigo
		// cui
		// nombre
		// apellido
		// direccion
		// departamento_origen_id
		// municipio_id
		// telefono_residencia
		// telefono_celular
		// fecha_nacimiento
		// nacionalidad
		// estado_civil
		// sexo
		// igss
		// nit
		// licencia_conducir
		// area_id
		// departmento_id
		// seccion_id
		// puesto_id
		// observaciones
		// tipo_sangre_id
		// estado
		// empleado_id

		$this->empleado_id->ViewValue = $this->empleado_id->CurrentValue;
		$this->empleado_id->ViewCustomAttributes = "";

		// codigo
		$this->codigo->ViewValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

		// cui
		$this->cui->ViewValue = $this->cui->CurrentValue;
		$this->cui->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// apellido
		$this->apellido->ViewValue = $this->apellido->CurrentValue;
		$this->apellido->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// departamento_origen_id
		if (strval($this->departamento_origen_id->CurrentValue) <> "") {
			$sFilterWrk = "`departamento_origen_id`" . ew_SearchString("=", $this->departamento_origen_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `departamento_origen_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento_origen`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->departamento_origen_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->departamento_origen_id->ViewValue = $this->departamento_origen_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->departamento_origen_id->ViewValue = $this->departamento_origen_id->CurrentValue;
			}
		} else {
			$this->departamento_origen_id->ViewValue = NULL;
		}
		$this->departamento_origen_id->ViewCustomAttributes = "";

		// municipio_id
		if (strval($this->municipio_id->CurrentValue) <> "") {
			$sFilterWrk = "`municipio_id`" . ew_SearchString("=", $this->municipio_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `municipio_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->municipio_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->municipio_id->ViewValue = $this->municipio_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->municipio_id->ViewValue = $this->municipio_id->CurrentValue;
			}
		} else {
			$this->municipio_id->ViewValue = NULL;
		}
		$this->municipio_id->ViewCustomAttributes = "";

		// telefono_residencia
		$this->telefono_residencia->ViewValue = $this->telefono_residencia->CurrentValue;
		$this->telefono_residencia->ViewCustomAttributes = "";

		// telefono_celular
		$this->telefono_celular->ViewValue = $this->telefono_celular->CurrentValue;
		$this->telefono_celular->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 7);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// nacionalidad
		if (strval($this->nacionalidad->CurrentValue) <> "") {
			$sFilterWrk = "`nacionalidad_id`" . ew_SearchString("=", $this->nacionalidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `nacionalidad_id`, `nacionalidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `nacionalidad`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->nacionalidad, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->nacionalidad->ViewValue = $this->nacionalidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->nacionalidad->ViewValue = $this->nacionalidad->CurrentValue;
			}
		} else {
			$this->nacionalidad->ViewValue = NULL;
		}
		$this->nacionalidad->ViewCustomAttributes = "";

		// estado_civil
		$this->estado_civil->ViewValue = $this->estado_civil->CurrentValue;
		if (strval($this->estado_civil->CurrentValue) <> "") {
			$sFilterWrk = "`estado_civil_id`" . ew_SearchString("=", $this->estado_civil->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `estado_civil_id`, `estado_civil` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_civil`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->estado_civil, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->estado_civil->ViewValue = $this->estado_civil->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->estado_civil->ViewValue = $this->estado_civil->CurrentValue;
			}
		} else {
			$this->estado_civil->ViewValue = NULL;
		}
		$this->estado_civil->ViewCustomAttributes = "";

		// sexo
		$this->sexo->ViewValue = $this->sexo->CurrentValue;
		if (strval($this->sexo->CurrentValue) <> "") {
			$sFilterWrk = "`sexo_id`" . ew_SearchString("=", $this->sexo->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sexo_id`, `sexo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sexo`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sexo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sexo->ViewValue = $this->sexo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sexo->ViewValue = $this->sexo->CurrentValue;
			}
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// igss
		$this->igss->ViewValue = $this->igss->CurrentValue;
		$this->igss->ViewCustomAttributes = "";

		// nit
		$this->nit->ViewValue = $this->nit->CurrentValue;
		$this->nit->ViewCustomAttributes = "";

		// licencia_conducir
		$this->licencia_conducir->ViewValue = $this->licencia_conducir->CurrentValue;
		$this->licencia_conducir->ViewCustomAttributes = "";

		// area_id
		$this->area_id->ViewValue = $this->area_id->CurrentValue;
		if (strval($this->area_id->CurrentValue) <> "") {
			$sFilterWrk = "`area_id`" . ew_SearchString("=", $this->area_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `area_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `area`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->area_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->area_id->ViewValue = $this->area_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->area_id->ViewValue = $this->area_id->CurrentValue;
			}
		} else {
			$this->area_id->ViewValue = NULL;
		}
		$this->area_id->ViewCustomAttributes = "";

		// departmento_id
		if (strval($this->departmento_id->CurrentValue) <> "") {
			$sFilterWrk = "`departamento_id`" . ew_SearchString("=", $this->departmento_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `departamento_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->departmento_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->departmento_id->ViewValue = $this->departmento_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->departmento_id->ViewValue = $this->departmento_id->CurrentValue;
			}
		} else {
			$this->departmento_id->ViewValue = NULL;
		}
		$this->departmento_id->ViewCustomAttributes = "";

		// seccion_id
		if (strval($this->seccion_id->CurrentValue) <> "") {
			$sFilterWrk = "`seccion_id`" . ew_SearchString("=", $this->seccion_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `seccion_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `seccion`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->seccion_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->seccion_id->ViewValue = $this->seccion_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->seccion_id->ViewValue = $this->seccion_id->CurrentValue;
			}
		} else {
			$this->seccion_id->ViewValue = NULL;
		}
		$this->seccion_id->ViewCustomAttributes = "";

		// puesto_id
		if (strval($this->puesto_id->CurrentValue) <> "") {
			$sFilterWrk = "`puesto_id`" . ew_SearchString("=", $this->puesto_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `puesto_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `puesto`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->puesto_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->puesto_id->ViewValue = $this->puesto_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->puesto_id->ViewValue = $this->puesto_id->CurrentValue;
			}
		} else {
			$this->puesto_id->ViewValue = NULL;
		}
		$this->puesto_id->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// tipo_sangre_id
		if (strval($this->tipo_sangre_id->CurrentValue) <> "") {
			$sFilterWrk = "`tipo_sangre_id`" . ew_SearchString("=", $this->tipo_sangre_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `tipo_sangre_id`, `tipo_sangre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_sangre`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipo_sangre_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipo_sangre_id->ViewValue = $this->tipo_sangre_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipo_sangre_id->ViewValue = $this->tipo_sangre_id->CurrentValue;
			}
		} else {
			$this->tipo_sangre_id->ViewValue = NULL;
		}
		$this->tipo_sangre_id->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// empleado_id
		$this->empleado_id->LinkCustomAttributes = "";
		$this->empleado_id->HrefValue = "";
		$this->empleado_id->TooltipValue = "";

		// codigo
		$this->codigo->LinkCustomAttributes = "";
		$this->codigo->HrefValue = "";
		$this->codigo->TooltipValue = "";

		// cui
		$this->cui->LinkCustomAttributes = "";
		$this->cui->HrefValue = "";
		$this->cui->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// apellido
		$this->apellido->LinkCustomAttributes = "";
		$this->apellido->HrefValue = "";
		$this->apellido->TooltipValue = "";

		// direccion
		$this->direccion->LinkCustomAttributes = "";
		$this->direccion->HrefValue = "";
		$this->direccion->TooltipValue = "";

		// departamento_origen_id
		$this->departamento_origen_id->LinkCustomAttributes = "";
		$this->departamento_origen_id->HrefValue = "";
		$this->departamento_origen_id->TooltipValue = "";

		// municipio_id
		$this->municipio_id->LinkCustomAttributes = "";
		$this->municipio_id->HrefValue = "";
		$this->municipio_id->TooltipValue = "";

		// telefono_residencia
		$this->telefono_residencia->LinkCustomAttributes = "";
		$this->telefono_residencia->HrefValue = "";
		$this->telefono_residencia->TooltipValue = "";

		// telefono_celular
		$this->telefono_celular->LinkCustomAttributes = "";
		$this->telefono_celular->HrefValue = "";
		$this->telefono_celular->TooltipValue = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->LinkCustomAttributes = "";
		$this->fecha_nacimiento->HrefValue = "";
		$this->fecha_nacimiento->TooltipValue = "";

		// nacionalidad
		$this->nacionalidad->LinkCustomAttributes = "";
		$this->nacionalidad->HrefValue = "";
		$this->nacionalidad->TooltipValue = "";

		// estado_civil
		$this->estado_civil->LinkCustomAttributes = "";
		$this->estado_civil->HrefValue = "";
		$this->estado_civil->TooltipValue = "";

		// sexo
		$this->sexo->LinkCustomAttributes = "";
		$this->sexo->HrefValue = "";
		$this->sexo->TooltipValue = "";

		// igss
		$this->igss->LinkCustomAttributes = "";
		$this->igss->HrefValue = "";
		$this->igss->TooltipValue = "";

		// nit
		$this->nit->LinkCustomAttributes = "";
		$this->nit->HrefValue = "";
		$this->nit->TooltipValue = "";

		// licencia_conducir
		$this->licencia_conducir->LinkCustomAttributes = "";
		$this->licencia_conducir->HrefValue = "";
		$this->licencia_conducir->TooltipValue = "";

		// area_id
		$this->area_id->LinkCustomAttributes = "";
		$this->area_id->HrefValue = "";
		$this->area_id->TooltipValue = "";

		// departmento_id
		$this->departmento_id->LinkCustomAttributes = "";
		$this->departmento_id->HrefValue = "";
		$this->departmento_id->TooltipValue = "";

		// seccion_id
		$this->seccion_id->LinkCustomAttributes = "";
		$this->seccion_id->HrefValue = "";
		$this->seccion_id->TooltipValue = "";

		// puesto_id
		$this->puesto_id->LinkCustomAttributes = "";
		$this->puesto_id->HrefValue = "";
		$this->puesto_id->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// tipo_sangre_id
		$this->tipo_sangre_id->LinkCustomAttributes = "";
		$this->tipo_sangre_id->HrefValue = "";
		$this->tipo_sangre_id->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// empleado_id
		$this->empleado_id->EditAttrs["class"] = "form-control";
		$this->empleado_id->EditCustomAttributes = "";
		$this->empleado_id->EditValue = $this->empleado_id->CurrentValue;
		$this->empleado_id->ViewCustomAttributes = "";

		// codigo
		$this->codigo->EditAttrs["class"] = "form-control";
		$this->codigo->EditCustomAttributes = "";
		$this->codigo->EditValue = $this->codigo->CurrentValue;
		$this->codigo->PlaceHolder = ew_RemoveHtml($this->codigo->FldCaption());

		// cui
		$this->cui->EditAttrs["class"] = "form-control";
		$this->cui->EditCustomAttributes = "";
		$this->cui->EditValue = $this->cui->CurrentValue;
		$this->cui->PlaceHolder = ew_RemoveHtml($this->cui->FldCaption());

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = $this->nombre->CurrentValue;
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

		// apellido
		$this->apellido->EditAttrs["class"] = "form-control";
		$this->apellido->EditCustomAttributes = "";
		$this->apellido->EditValue = $this->apellido->CurrentValue;
		$this->apellido->PlaceHolder = ew_RemoveHtml($this->apellido->FldCaption());

		// direccion
		$this->direccion->EditAttrs["class"] = "form-control";
		$this->direccion->EditCustomAttributes = "";
		$this->direccion->EditValue = $this->direccion->CurrentValue;
		$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

		// departamento_origen_id
		$this->departamento_origen_id->EditAttrs["class"] = "form-control";
		$this->departamento_origen_id->EditCustomAttributes = "";

		// municipio_id
		$this->municipio_id->EditAttrs["class"] = "form-control";
		$this->municipio_id->EditCustomAttributes = "";

		// telefono_residencia
		$this->telefono_residencia->EditAttrs["class"] = "form-control";
		$this->telefono_residencia->EditCustomAttributes = "";
		$this->telefono_residencia->EditValue = $this->telefono_residencia->CurrentValue;
		$this->telefono_residencia->PlaceHolder = ew_RemoveHtml($this->telefono_residencia->FldCaption());

		// telefono_celular
		$this->telefono_celular->EditAttrs["class"] = "form-control";
		$this->telefono_celular->EditCustomAttributes = "";
		$this->telefono_celular->EditValue = $this->telefono_celular->CurrentValue;
		$this->telefono_celular->PlaceHolder = ew_RemoveHtml($this->telefono_celular->FldCaption());

		// fecha_nacimiento
		$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
		$this->fecha_nacimiento->EditCustomAttributes = "";
		$this->fecha_nacimiento->EditValue = ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

		// nacionalidad
		$this->nacionalidad->EditAttrs["class"] = "form-control";
		$this->nacionalidad->EditCustomAttributes = "";

		// estado_civil
		$this->estado_civil->EditAttrs["class"] = "form-control";
		$this->estado_civil->EditCustomAttributes = "";
		$this->estado_civil->EditValue = $this->estado_civil->CurrentValue;
		$this->estado_civil->PlaceHolder = ew_RemoveHtml($this->estado_civil->FldCaption());

		// sexo
		$this->sexo->EditAttrs["class"] = "form-control";
		$this->sexo->EditCustomAttributes = "";
		$this->sexo->EditValue = $this->sexo->CurrentValue;
		$this->sexo->PlaceHolder = ew_RemoveHtml($this->sexo->FldCaption());

		// igss
		$this->igss->EditAttrs["class"] = "form-control";
		$this->igss->EditCustomAttributes = "";
		$this->igss->EditValue = $this->igss->CurrentValue;
		$this->igss->PlaceHolder = ew_RemoveHtml($this->igss->FldCaption());

		// nit
		$this->nit->EditAttrs["class"] = "form-control";
		$this->nit->EditCustomAttributes = "";
		$this->nit->EditValue = $this->nit->CurrentValue;
		$this->nit->PlaceHolder = ew_RemoveHtml($this->nit->FldCaption());

		// licencia_conducir
		$this->licencia_conducir->EditAttrs["class"] = "form-control";
		$this->licencia_conducir->EditCustomAttributes = "";
		$this->licencia_conducir->EditValue = $this->licencia_conducir->CurrentValue;
		$this->licencia_conducir->PlaceHolder = ew_RemoveHtml($this->licencia_conducir->FldCaption());

		// area_id
		$this->area_id->EditAttrs["class"] = "form-control";
		$this->area_id->EditCustomAttributes = "";
		$this->area_id->EditValue = $this->area_id->CurrentValue;
		$this->area_id->PlaceHolder = ew_RemoveHtml($this->area_id->FldCaption());

		// departmento_id
		$this->departmento_id->EditAttrs["class"] = "form-control";
		$this->departmento_id->EditCustomAttributes = "";

		// seccion_id
		$this->seccion_id->EditAttrs["class"] = "form-control";
		$this->seccion_id->EditCustomAttributes = "";

		// puesto_id
		$this->puesto_id->EditAttrs["class"] = "form-control";
		$this->puesto_id->EditCustomAttributes = "";

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// tipo_sangre_id
		$this->tipo_sangre_id->EditAttrs["class"] = "form-control";
		$this->tipo_sangre_id->EditCustomAttributes = "";

		// estado
		$this->estado->EditAttrs["class"] = "form-control";
		$this->estado->EditCustomAttributes = "";
		$this->estado->EditValue = $this->estado->CurrentValue;
		$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

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
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->cui->Exportable) $Doc->ExportCaption($this->cui);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->apellido->Exportable) $Doc->ExportCaption($this->apellido);
					if ($this->direccion->Exportable) $Doc->ExportCaption($this->direccion);
					if ($this->departamento_origen_id->Exportable) $Doc->ExportCaption($this->departamento_origen_id);
					if ($this->municipio_id->Exportable) $Doc->ExportCaption($this->municipio_id);
					if ($this->telefono_residencia->Exportable) $Doc->ExportCaption($this->telefono_residencia);
					if ($this->telefono_celular->Exportable) $Doc->ExportCaption($this->telefono_celular);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->nacionalidad->Exportable) $Doc->ExportCaption($this->nacionalidad);
					if ($this->estado_civil->Exportable) $Doc->ExportCaption($this->estado_civil);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->igss->Exportable) $Doc->ExportCaption($this->igss);
					if ($this->nit->Exportable) $Doc->ExportCaption($this->nit);
					if ($this->licencia_conducir->Exportable) $Doc->ExportCaption($this->licencia_conducir);
					if ($this->area_id->Exportable) $Doc->ExportCaption($this->area_id);
					if ($this->departmento_id->Exportable) $Doc->ExportCaption($this->departmento_id);
					if ($this->seccion_id->Exportable) $Doc->ExportCaption($this->seccion_id);
					if ($this->puesto_id->Exportable) $Doc->ExportCaption($this->puesto_id);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->tipo_sangre_id->Exportable) $Doc->ExportCaption($this->tipo_sangre_id);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				} else {
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->cui->Exportable) $Doc->ExportCaption($this->cui);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->apellido->Exportable) $Doc->ExportCaption($this->apellido);
					if ($this->direccion->Exportable) $Doc->ExportCaption($this->direccion);
					if ($this->departamento_origen_id->Exportable) $Doc->ExportCaption($this->departamento_origen_id);
					if ($this->municipio_id->Exportable) $Doc->ExportCaption($this->municipio_id);
					if ($this->telefono_residencia->Exportable) $Doc->ExportCaption($this->telefono_residencia);
					if ($this->telefono_celular->Exportable) $Doc->ExportCaption($this->telefono_celular);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->nacionalidad->Exportable) $Doc->ExportCaption($this->nacionalidad);
					if ($this->estado_civil->Exportable) $Doc->ExportCaption($this->estado_civil);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->igss->Exportable) $Doc->ExportCaption($this->igss);
					if ($this->nit->Exportable) $Doc->ExportCaption($this->nit);
					if ($this->licencia_conducir->Exportable) $Doc->ExportCaption($this->licencia_conducir);
					if ($this->area_id->Exportable) $Doc->ExportCaption($this->area_id);
					if ($this->departmento_id->Exportable) $Doc->ExportCaption($this->departmento_id);
					if ($this->seccion_id->Exportable) $Doc->ExportCaption($this->seccion_id);
					if ($this->puesto_id->Exportable) $Doc->ExportCaption($this->puesto_id);
					if ($this->tipo_sangre_id->Exportable) $Doc->ExportCaption($this->tipo_sangre_id);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
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
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->cui->Exportable) $Doc->ExportField($this->cui);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->apellido->Exportable) $Doc->ExportField($this->apellido);
						if ($this->direccion->Exportable) $Doc->ExportField($this->direccion);
						if ($this->departamento_origen_id->Exportable) $Doc->ExportField($this->departamento_origen_id);
						if ($this->municipio_id->Exportable) $Doc->ExportField($this->municipio_id);
						if ($this->telefono_residencia->Exportable) $Doc->ExportField($this->telefono_residencia);
						if ($this->telefono_celular->Exportable) $Doc->ExportField($this->telefono_celular);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->nacionalidad->Exportable) $Doc->ExportField($this->nacionalidad);
						if ($this->estado_civil->Exportable) $Doc->ExportField($this->estado_civil);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->igss->Exportable) $Doc->ExportField($this->igss);
						if ($this->nit->Exportable) $Doc->ExportField($this->nit);
						if ($this->licencia_conducir->Exportable) $Doc->ExportField($this->licencia_conducir);
						if ($this->area_id->Exportable) $Doc->ExportField($this->area_id);
						if ($this->departmento_id->Exportable) $Doc->ExportField($this->departmento_id);
						if ($this->seccion_id->Exportable) $Doc->ExportField($this->seccion_id);
						if ($this->puesto_id->Exportable) $Doc->ExportField($this->puesto_id);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->tipo_sangre_id->Exportable) $Doc->ExportField($this->tipo_sangre_id);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					} else {
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->cui->Exportable) $Doc->ExportField($this->cui);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->apellido->Exportable) $Doc->ExportField($this->apellido);
						if ($this->direccion->Exportable) $Doc->ExportField($this->direccion);
						if ($this->departamento_origen_id->Exportable) $Doc->ExportField($this->departamento_origen_id);
						if ($this->municipio_id->Exportable) $Doc->ExportField($this->municipio_id);
						if ($this->telefono_residencia->Exportable) $Doc->ExportField($this->telefono_residencia);
						if ($this->telefono_celular->Exportable) $Doc->ExportField($this->telefono_celular);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->nacionalidad->Exportable) $Doc->ExportField($this->nacionalidad);
						if ($this->estado_civil->Exportable) $Doc->ExportField($this->estado_civil);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->igss->Exportable) $Doc->ExportField($this->igss);
						if ($this->nit->Exportable) $Doc->ExportField($this->nit);
						if ($this->licencia_conducir->Exportable) $Doc->ExportField($this->licencia_conducir);
						if ($this->area_id->Exportable) $Doc->ExportField($this->area_id);
						if ($this->departmento_id->Exportable) $Doc->ExportField($this->departmento_id);
						if ($this->seccion_id->Exportable) $Doc->ExportField($this->seccion_id);
						if ($this->puesto_id->Exportable) $Doc->ExportField($this->puesto_id);
						if ($this->tipo_sangre_id->Exportable) $Doc->ExportField($this->tipo_sangre_id);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
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
