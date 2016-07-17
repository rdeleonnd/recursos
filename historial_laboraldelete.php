<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "historial_laboralinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$historial_laboral_delete = NULL; // Initialize page object first

class chistorial_laboral_delete extends chistorial_laboral {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'historial_laboral';

	// Page object name
	var $PageObjName = 'historial_laboral_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (historial_laboral)
		if (!isset($GLOBALS["historial_laboral"]) || get_class($GLOBALS["historial_laboral"]) == "chistorial_laboral") {
			$GLOBALS["historial_laboral"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["historial_laboral"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'historial_laboral', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (user)
		if (!isset($UserTable)) {
			$UserTable = new cuser();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("historial_laborallist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Update last accessed time
		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $historial_laboral;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($historial_laboral);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("historial_laborallist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in historial_laboral class, historial_laboralinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("historial_laborallist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->historial_laboral_id->setDbValue($rs->fields('historial_laboral_id'));
		$this->institucion->setDbValue($rs->fields('institucion'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->puesto->setDbValue($rs->fields('puesto'));
		$this->atribuciones->setDbValue($rs->fields('atribuciones'));
		$this->jefe->setDbValue($rs->fields('jefe'));
		$this->fecha_ingreso->setDbValue($rs->fields('fecha_ingreso'));
		$this->fecha_egreso->setDbValue($rs->fields('fecha_egreso'));
		$this->sueldo_inicial->setDbValue($rs->fields('sueldo_inicial'));
		$this->sueldo_final->setDbValue($rs->fields('sueldo_final'));
		$this->motivo_retiro->setDbValue($rs->fields('motivo_retiro'));
		$this->empleado_id->setDbValue($rs->fields('empleado_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->historial_laboral_id->DbValue = $row['historial_laboral_id'];
		$this->institucion->DbValue = $row['institucion'];
		$this->direccion->DbValue = $row['direccion'];
		$this->telefono->DbValue = $row['telefono'];
		$this->puesto->DbValue = $row['puesto'];
		$this->atribuciones->DbValue = $row['atribuciones'];
		$this->jefe->DbValue = $row['jefe'];
		$this->fecha_ingreso->DbValue = $row['fecha_ingreso'];
		$this->fecha_egreso->DbValue = $row['fecha_egreso'];
		$this->sueldo_inicial->DbValue = $row['sueldo_inicial'];
		$this->sueldo_final->DbValue = $row['sueldo_final'];
		$this->motivo_retiro->DbValue = $row['motivo_retiro'];
		$this->empleado_id->DbValue = $row['empleado_id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->sueldo_inicial->FormValue == $this->sueldo_inicial->CurrentValue && is_numeric(ew_StrToFloat($this->sueldo_inicial->CurrentValue)))
			$this->sueldo_inicial->CurrentValue = ew_StrToFloat($this->sueldo_inicial->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sueldo_final->FormValue == $this->sueldo_final->CurrentValue && is_numeric(ew_StrToFloat($this->sueldo_final->CurrentValue)))
			$this->sueldo_final->CurrentValue = ew_StrToFloat($this->sueldo_final->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// historial_laboral_id

		$this->historial_laboral_id->CellCssStyle = "white-space: nowrap;";

		// institucion
		// direccion
		// telefono
		// puesto
		// atribuciones
		// jefe
		// fecha_ingreso
		// fecha_egreso
		// sueldo_inicial
		// sueldo_final
		// motivo_retiro
		// empleado_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// institucion
		$this->institucion->ViewValue = $this->institucion->CurrentValue;
		$this->institucion->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// puesto
		$this->puesto->ViewValue = $this->puesto->CurrentValue;
		$this->puesto->ViewCustomAttributes = "";

		// atribuciones
		$this->atribuciones->ViewValue = $this->atribuciones->CurrentValue;
		$this->atribuciones->ViewCustomAttributes = "";

		// jefe
		$this->jefe->ViewValue = $this->jefe->CurrentValue;
		$this->jefe->ViewCustomAttributes = "";

		// fecha_ingreso
		$this->fecha_ingreso->ViewValue = $this->fecha_ingreso->CurrentValue;
		$this->fecha_ingreso->ViewValue = ew_FormatDateTime($this->fecha_ingreso->ViewValue, 7);
		$this->fecha_ingreso->ViewCustomAttributes = "";

		// fecha_egreso
		$this->fecha_egreso->ViewValue = $this->fecha_egreso->CurrentValue;
		$this->fecha_egreso->ViewValue = ew_FormatDateTime($this->fecha_egreso->ViewValue, 7);
		$this->fecha_egreso->ViewCustomAttributes = "";

		// sueldo_inicial
		$this->sueldo_inicial->ViewValue = $this->sueldo_inicial->CurrentValue;
		$this->sueldo_inicial->ViewValue = ew_FormatCurrency($this->sueldo_inicial->ViewValue, 2, -2, -2, -2);
		$this->sueldo_inicial->ViewCustomAttributes = "";

		// sueldo_final
		$this->sueldo_final->ViewValue = $this->sueldo_final->CurrentValue;
		$this->sueldo_final->ViewValue = ew_FormatCurrency($this->sueldo_final->ViewValue, 2, -2, -2, -2);
		$this->sueldo_final->ViewCustomAttributes = "";

		// motivo_retiro
		$this->motivo_retiro->ViewValue = $this->motivo_retiro->CurrentValue;
		$this->motivo_retiro->ViewCustomAttributes = "";

		// empleado_id
		if (strval($this->empleado_id->CurrentValue) <> "") {
			$sFilterWrk = "`empleado_id`" . ew_SearchString("=", $this->empleado_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `empleado_id`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->empleado_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->empleado_id->ViewValue = $this->empleado_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->empleado_id->ViewValue = $this->empleado_id->CurrentValue;
			}
		} else {
			$this->empleado_id->ViewValue = NULL;
		}
		$this->empleado_id->ViewCustomAttributes = "";

			// institucion
			$this->institucion->LinkCustomAttributes = "";
			$this->institucion->HrefValue = "";
			$this->institucion->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// puesto
			$this->puesto->LinkCustomAttributes = "";
			$this->puesto->HrefValue = "";
			$this->puesto->TooltipValue = "";

			// atribuciones
			$this->atribuciones->LinkCustomAttributes = "";
			$this->atribuciones->HrefValue = "";
			$this->atribuciones->TooltipValue = "";

			// jefe
			$this->jefe->LinkCustomAttributes = "";
			$this->jefe->HrefValue = "";
			$this->jefe->TooltipValue = "";

			// fecha_ingreso
			$this->fecha_ingreso->LinkCustomAttributes = "";
			$this->fecha_ingreso->HrefValue = "";
			$this->fecha_ingreso->TooltipValue = "";

			// fecha_egreso
			$this->fecha_egreso->LinkCustomAttributes = "";
			$this->fecha_egreso->HrefValue = "";
			$this->fecha_egreso->TooltipValue = "";

			// sueldo_inicial
			$this->sueldo_inicial->LinkCustomAttributes = "";
			$this->sueldo_inicial->HrefValue = "";
			$this->sueldo_inicial->TooltipValue = "";

			// sueldo_final
			$this->sueldo_final->LinkCustomAttributes = "";
			$this->sueldo_final->HrefValue = "";
			$this->sueldo_final->TooltipValue = "";

			// motivo_retiro
			$this->motivo_retiro->LinkCustomAttributes = "";
			$this->motivo_retiro->HrefValue = "";
			$this->motivo_retiro->TooltipValue = "";

			// empleado_id
			$this->empleado_id->LinkCustomAttributes = "";
			$this->empleado_id->HrefValue = "";
			$this->empleado_id->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['historial_laboral_id'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("historial_laborallist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($historial_laboral_delete)) $historial_laboral_delete = new chistorial_laboral_delete();

// Page init
$historial_laboral_delete->Page_Init();

// Page main
$historial_laboral_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$historial_laboral_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fhistorial_laboraldelete = new ew_Form("fhistorial_laboraldelete", "delete");

// Form_CustomValidate event
fhistorial_laboraldelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhistorial_laboraldelete.ValidateRequired = true;
<?php } else { ?>
fhistorial_laboraldelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhistorial_laboraldelete.Lists["x_empleado_id"] = {"LinkField":"x_empleado_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $historial_laboral_delete->ShowPageHeader(); ?>
<?php
$historial_laboral_delete->ShowMessage();
?>
<form name="fhistorial_laboraldelete" id="fhistorial_laboraldelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($historial_laboral_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $historial_laboral_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="historial_laboral">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($historial_laboral_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $historial_laboral->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($historial_laboral->institucion->Visible) { // institucion ?>
		<th><span id="elh_historial_laboral_institucion" class="historial_laboral_institucion"><?php echo $historial_laboral->institucion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->direccion->Visible) { // direccion ?>
		<th><span id="elh_historial_laboral_direccion" class="historial_laboral_direccion"><?php echo $historial_laboral->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->telefono->Visible) { // telefono ?>
		<th><span id="elh_historial_laboral_telefono" class="historial_laboral_telefono"><?php echo $historial_laboral->telefono->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->puesto->Visible) { // puesto ?>
		<th><span id="elh_historial_laboral_puesto" class="historial_laboral_puesto"><?php echo $historial_laboral->puesto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->atribuciones->Visible) { // atribuciones ?>
		<th><span id="elh_historial_laboral_atribuciones" class="historial_laboral_atribuciones"><?php echo $historial_laboral->atribuciones->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->jefe->Visible) { // jefe ?>
		<th><span id="elh_historial_laboral_jefe" class="historial_laboral_jefe"><?php echo $historial_laboral->jefe->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->fecha_ingreso->Visible) { // fecha_ingreso ?>
		<th><span id="elh_historial_laboral_fecha_ingreso" class="historial_laboral_fecha_ingreso"><?php echo $historial_laboral->fecha_ingreso->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->fecha_egreso->Visible) { // fecha_egreso ?>
		<th><span id="elh_historial_laboral_fecha_egreso" class="historial_laboral_fecha_egreso"><?php echo $historial_laboral->fecha_egreso->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->sueldo_inicial->Visible) { // sueldo_inicial ?>
		<th><span id="elh_historial_laboral_sueldo_inicial" class="historial_laboral_sueldo_inicial"><?php echo $historial_laboral->sueldo_inicial->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->sueldo_final->Visible) { // sueldo_final ?>
		<th><span id="elh_historial_laboral_sueldo_final" class="historial_laboral_sueldo_final"><?php echo $historial_laboral->sueldo_final->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->motivo_retiro->Visible) { // motivo_retiro ?>
		<th><span id="elh_historial_laboral_motivo_retiro" class="historial_laboral_motivo_retiro"><?php echo $historial_laboral->motivo_retiro->FldCaption() ?></span></th>
<?php } ?>
<?php if ($historial_laboral->empleado_id->Visible) { // empleado_id ?>
		<th><span id="elh_historial_laboral_empleado_id" class="historial_laboral_empleado_id"><?php echo $historial_laboral->empleado_id->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$historial_laboral_delete->RecCnt = 0;
$i = 0;
while (!$historial_laboral_delete->Recordset->EOF) {
	$historial_laboral_delete->RecCnt++;
	$historial_laboral_delete->RowCnt++;

	// Set row properties
	$historial_laboral->ResetAttrs();
	$historial_laboral->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$historial_laboral_delete->LoadRowValues($historial_laboral_delete->Recordset);

	// Render row
	$historial_laboral_delete->RenderRow();
?>
	<tr<?php echo $historial_laboral->RowAttributes() ?>>
<?php if ($historial_laboral->institucion->Visible) { // institucion ?>
		<td<?php echo $historial_laboral->institucion->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_institucion" class="historial_laboral_institucion">
<span<?php echo $historial_laboral->institucion->ViewAttributes() ?>>
<?php echo $historial_laboral->institucion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->direccion->Visible) { // direccion ?>
		<td<?php echo $historial_laboral->direccion->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_direccion" class="historial_laboral_direccion">
<span<?php echo $historial_laboral->direccion->ViewAttributes() ?>>
<?php echo $historial_laboral->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->telefono->Visible) { // telefono ?>
		<td<?php echo $historial_laboral->telefono->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_telefono" class="historial_laboral_telefono">
<span<?php echo $historial_laboral->telefono->ViewAttributes() ?>>
<?php echo $historial_laboral->telefono->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->puesto->Visible) { // puesto ?>
		<td<?php echo $historial_laboral->puesto->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_puesto" class="historial_laboral_puesto">
<span<?php echo $historial_laboral->puesto->ViewAttributes() ?>>
<?php echo $historial_laboral->puesto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->atribuciones->Visible) { // atribuciones ?>
		<td<?php echo $historial_laboral->atribuciones->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_atribuciones" class="historial_laboral_atribuciones">
<span<?php echo $historial_laboral->atribuciones->ViewAttributes() ?>>
<?php echo $historial_laboral->atribuciones->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->jefe->Visible) { // jefe ?>
		<td<?php echo $historial_laboral->jefe->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_jefe" class="historial_laboral_jefe">
<span<?php echo $historial_laboral->jefe->ViewAttributes() ?>>
<?php echo $historial_laboral->jefe->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->fecha_ingreso->Visible) { // fecha_ingreso ?>
		<td<?php echo $historial_laboral->fecha_ingreso->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_fecha_ingreso" class="historial_laboral_fecha_ingreso">
<span<?php echo $historial_laboral->fecha_ingreso->ViewAttributes() ?>>
<?php echo $historial_laboral->fecha_ingreso->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->fecha_egreso->Visible) { // fecha_egreso ?>
		<td<?php echo $historial_laboral->fecha_egreso->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_fecha_egreso" class="historial_laboral_fecha_egreso">
<span<?php echo $historial_laboral->fecha_egreso->ViewAttributes() ?>>
<?php echo $historial_laboral->fecha_egreso->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->sueldo_inicial->Visible) { // sueldo_inicial ?>
		<td<?php echo $historial_laboral->sueldo_inicial->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_sueldo_inicial" class="historial_laboral_sueldo_inicial">
<span<?php echo $historial_laboral->sueldo_inicial->ViewAttributes() ?>>
<?php echo $historial_laboral->sueldo_inicial->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->sueldo_final->Visible) { // sueldo_final ?>
		<td<?php echo $historial_laboral->sueldo_final->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_sueldo_final" class="historial_laboral_sueldo_final">
<span<?php echo $historial_laboral->sueldo_final->ViewAttributes() ?>>
<?php echo $historial_laboral->sueldo_final->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->motivo_retiro->Visible) { // motivo_retiro ?>
		<td<?php echo $historial_laboral->motivo_retiro->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_motivo_retiro" class="historial_laboral_motivo_retiro">
<span<?php echo $historial_laboral->motivo_retiro->ViewAttributes() ?>>
<?php echo $historial_laboral->motivo_retiro->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($historial_laboral->empleado_id->Visible) { // empleado_id ?>
		<td<?php echo $historial_laboral->empleado_id->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_delete->RowCnt ?>_historial_laboral_empleado_id" class="historial_laboral_empleado_id">
<span<?php echo $historial_laboral->empleado_id->ViewAttributes() ?>>
<?php echo $historial_laboral->empleado_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$historial_laboral_delete->Recordset->MoveNext();
}
$historial_laboral_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $historial_laboral_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fhistorial_laboraldelete.Init();
</script>
<?php
$historial_laboral_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$historial_laboral_delete->Page_Terminate();
?>
