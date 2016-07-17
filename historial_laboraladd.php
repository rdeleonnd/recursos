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

$historial_laboral_add = NULL; // Initialize page object first

class chistorial_laboral_add extends chistorial_laboral {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'historial_laboral';

	// Page object name
	var $PageObjName = 'historial_laboral_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Create form object
		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["historial_laboral_id"] != "") {
				$this->historial_laboral_id->setQueryStringValue($_GET["historial_laboral_id"]);
				$this->setKey("historial_laboral_id", $this->historial_laboral_id->CurrentValue); // Set up key
			} else {
				$this->setKey("historial_laboral_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("historial_laborallist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "historial_laborallist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "historial_laboralview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->institucion->CurrentValue = NULL;
		$this->institucion->OldValue = $this->institucion->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->telefono->CurrentValue = NULL;
		$this->telefono->OldValue = $this->telefono->CurrentValue;
		$this->puesto->CurrentValue = NULL;
		$this->puesto->OldValue = $this->puesto->CurrentValue;
		$this->atribuciones->CurrentValue = NULL;
		$this->atribuciones->OldValue = $this->atribuciones->CurrentValue;
		$this->jefe->CurrentValue = NULL;
		$this->jefe->OldValue = $this->jefe->CurrentValue;
		$this->fecha_ingreso->CurrentValue = NULL;
		$this->fecha_ingreso->OldValue = $this->fecha_ingreso->CurrentValue;
		$this->fecha_egreso->CurrentValue = NULL;
		$this->fecha_egreso->OldValue = $this->fecha_egreso->CurrentValue;
		$this->sueldo_inicial->CurrentValue = NULL;
		$this->sueldo_inicial->OldValue = $this->sueldo_inicial->CurrentValue;
		$this->sueldo_final->CurrentValue = NULL;
		$this->sueldo_final->OldValue = $this->sueldo_final->CurrentValue;
		$this->motivo_retiro->CurrentValue = NULL;
		$this->motivo_retiro->OldValue = $this->motivo_retiro->CurrentValue;
		$this->empleado_id->CurrentValue = NULL;
		$this->empleado_id->OldValue = $this->empleado_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->institucion->FldIsDetailKey) {
			$this->institucion->setFormValue($objForm->GetValue("x_institucion"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->puesto->FldIsDetailKey) {
			$this->puesto->setFormValue($objForm->GetValue("x_puesto"));
		}
		if (!$this->atribuciones->FldIsDetailKey) {
			$this->atribuciones->setFormValue($objForm->GetValue("x_atribuciones"));
		}
		if (!$this->jefe->FldIsDetailKey) {
			$this->jefe->setFormValue($objForm->GetValue("x_jefe"));
		}
		if (!$this->fecha_ingreso->FldIsDetailKey) {
			$this->fecha_ingreso->setFormValue($objForm->GetValue("x_fecha_ingreso"));
			$this->fecha_ingreso->CurrentValue = ew_UnFormatDateTime($this->fecha_ingreso->CurrentValue, 7);
		}
		if (!$this->fecha_egreso->FldIsDetailKey) {
			$this->fecha_egreso->setFormValue($objForm->GetValue("x_fecha_egreso"));
			$this->fecha_egreso->CurrentValue = ew_UnFormatDateTime($this->fecha_egreso->CurrentValue, 7);
		}
		if (!$this->sueldo_inicial->FldIsDetailKey) {
			$this->sueldo_inicial->setFormValue($objForm->GetValue("x_sueldo_inicial"));
		}
		if (!$this->sueldo_final->FldIsDetailKey) {
			$this->sueldo_final->setFormValue($objForm->GetValue("x_sueldo_final"));
		}
		if (!$this->motivo_retiro->FldIsDetailKey) {
			$this->motivo_retiro->setFormValue($objForm->GetValue("x_motivo_retiro"));
		}
		if (!$this->empleado_id->FldIsDetailKey) {
			$this->empleado_id->setFormValue($objForm->GetValue("x_empleado_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->institucion->CurrentValue = $this->institucion->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->puesto->CurrentValue = $this->puesto->FormValue;
		$this->atribuciones->CurrentValue = $this->atribuciones->FormValue;
		$this->jefe->CurrentValue = $this->jefe->FormValue;
		$this->fecha_ingreso->CurrentValue = $this->fecha_ingreso->FormValue;
		$this->fecha_ingreso->CurrentValue = ew_UnFormatDateTime($this->fecha_ingreso->CurrentValue, 7);
		$this->fecha_egreso->CurrentValue = $this->fecha_egreso->FormValue;
		$this->fecha_egreso->CurrentValue = ew_UnFormatDateTime($this->fecha_egreso->CurrentValue, 7);
		$this->sueldo_inicial->CurrentValue = $this->sueldo_inicial->FormValue;
		$this->sueldo_final->CurrentValue = $this->sueldo_final->FormValue;
		$this->motivo_retiro->CurrentValue = $this->motivo_retiro->FormValue;
		$this->empleado_id->CurrentValue = $this->empleado_id->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("historial_laboral_id")) <> "")
			$this->historial_laboral_id->CurrentValue = $this->getKey("historial_laboral_id"); // historial_laboral_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// institucion
			$this->institucion->EditAttrs["class"] = "form-control";
			$this->institucion->EditCustomAttributes = "";
			$this->institucion->EditValue = ew_HtmlEncode($this->institucion->CurrentValue);
			$this->institucion->PlaceHolder = ew_RemoveHtml($this->institucion->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// puesto
			$this->puesto->EditAttrs["class"] = "form-control";
			$this->puesto->EditCustomAttributes = "";
			$this->puesto->EditValue = ew_HtmlEncode($this->puesto->CurrentValue);
			$this->puesto->PlaceHolder = ew_RemoveHtml($this->puesto->FldCaption());

			// atribuciones
			$this->atribuciones->EditAttrs["class"] = "form-control";
			$this->atribuciones->EditCustomAttributes = "";
			$this->atribuciones->EditValue = ew_HtmlEncode($this->atribuciones->CurrentValue);
			$this->atribuciones->PlaceHolder = ew_RemoveHtml($this->atribuciones->FldCaption());

			// jefe
			$this->jefe->EditAttrs["class"] = "form-control";
			$this->jefe->EditCustomAttributes = "";
			$this->jefe->EditValue = ew_HtmlEncode($this->jefe->CurrentValue);
			$this->jefe->PlaceHolder = ew_RemoveHtml($this->jefe->FldCaption());

			// fecha_ingreso
			$this->fecha_ingreso->EditAttrs["class"] = "form-control";
			$this->fecha_ingreso->EditCustomAttributes = "";
			$this->fecha_ingreso->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_ingreso->CurrentValue, 7));
			$this->fecha_ingreso->PlaceHolder = ew_RemoveHtml($this->fecha_ingreso->FldCaption());

			// fecha_egreso
			$this->fecha_egreso->EditAttrs["class"] = "form-control";
			$this->fecha_egreso->EditCustomAttributes = "";
			$this->fecha_egreso->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_egreso->CurrentValue, 7));
			$this->fecha_egreso->PlaceHolder = ew_RemoveHtml($this->fecha_egreso->FldCaption());

			// sueldo_inicial
			$this->sueldo_inicial->EditAttrs["class"] = "form-control";
			$this->sueldo_inicial->EditCustomAttributes = "";
			$this->sueldo_inicial->EditValue = ew_HtmlEncode($this->sueldo_inicial->CurrentValue);
			$this->sueldo_inicial->PlaceHolder = ew_RemoveHtml($this->sueldo_inicial->FldCaption());
			if (strval($this->sueldo_inicial->EditValue) <> "" && is_numeric($this->sueldo_inicial->EditValue)) $this->sueldo_inicial->EditValue = ew_FormatNumber($this->sueldo_inicial->EditValue, -2, -2, -2, -2);

			// sueldo_final
			$this->sueldo_final->EditAttrs["class"] = "form-control";
			$this->sueldo_final->EditCustomAttributes = "";
			$this->sueldo_final->EditValue = ew_HtmlEncode($this->sueldo_final->CurrentValue);
			$this->sueldo_final->PlaceHolder = ew_RemoveHtml($this->sueldo_final->FldCaption());
			if (strval($this->sueldo_final->EditValue) <> "" && is_numeric($this->sueldo_final->EditValue)) $this->sueldo_final->EditValue = ew_FormatNumber($this->sueldo_final->EditValue, -2, -2, -2, -2);

			// motivo_retiro
			$this->motivo_retiro->EditAttrs["class"] = "form-control";
			$this->motivo_retiro->EditCustomAttributes = "";
			$this->motivo_retiro->EditValue = ew_HtmlEncode($this->motivo_retiro->CurrentValue);
			$this->motivo_retiro->PlaceHolder = ew_RemoveHtml($this->motivo_retiro->FldCaption());

			// empleado_id
			$this->empleado_id->EditAttrs["class"] = "form-control";
			$this->empleado_id->EditCustomAttributes = "";
			if (trim(strval($this->empleado_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`empleado_id`" . ew_SearchString("=", $this->empleado_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `empleado_id`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empleado`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->empleado_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->empleado_id->EditValue = $arwrk;

			// Add refer script
			// institucion

			$this->institucion->LinkCustomAttributes = "";
			$this->institucion->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";

			// puesto
			$this->puesto->LinkCustomAttributes = "";
			$this->puesto->HrefValue = "";

			// atribuciones
			$this->atribuciones->LinkCustomAttributes = "";
			$this->atribuciones->HrefValue = "";

			// jefe
			$this->jefe->LinkCustomAttributes = "";
			$this->jefe->HrefValue = "";

			// fecha_ingreso
			$this->fecha_ingreso->LinkCustomAttributes = "";
			$this->fecha_ingreso->HrefValue = "";

			// fecha_egreso
			$this->fecha_egreso->LinkCustomAttributes = "";
			$this->fecha_egreso->HrefValue = "";

			// sueldo_inicial
			$this->sueldo_inicial->LinkCustomAttributes = "";
			$this->sueldo_inicial->HrefValue = "";

			// sueldo_final
			$this->sueldo_final->LinkCustomAttributes = "";
			$this->sueldo_final->HrefValue = "";

			// motivo_retiro
			$this->motivo_retiro->LinkCustomAttributes = "";
			$this->motivo_retiro->HrefValue = "";

			// empleado_id
			$this->empleado_id->LinkCustomAttributes = "";
			$this->empleado_id->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->institucion->FldIsDetailKey && !is_null($this->institucion->FormValue) && $this->institucion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->institucion->FldCaption(), $this->institucion->ReqErrMsg));
		}
		if (!$this->direccion->FldIsDetailKey && !is_null($this->direccion->FormValue) && $this->direccion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->direccion->FldCaption(), $this->direccion->ReqErrMsg));
		}
		if (!$this->telefono->FldIsDetailKey && !is_null($this->telefono->FormValue) && $this->telefono->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->telefono->FldCaption(), $this->telefono->ReqErrMsg));
		}
		if (!$this->puesto->FldIsDetailKey && !is_null($this->puesto->FormValue) && $this->puesto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->puesto->FldCaption(), $this->puesto->ReqErrMsg));
		}
		if (!$this->atribuciones->FldIsDetailKey && !is_null($this->atribuciones->FormValue) && $this->atribuciones->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->atribuciones->FldCaption(), $this->atribuciones->ReqErrMsg));
		}
		if (!$this->jefe->FldIsDetailKey && !is_null($this->jefe->FormValue) && $this->jefe->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jefe->FldCaption(), $this->jefe->ReqErrMsg));
		}
		if (!$this->fecha_ingreso->FldIsDetailKey && !is_null($this->fecha_ingreso->FormValue) && $this->fecha_ingreso->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha_ingreso->FldCaption(), $this->fecha_ingreso->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha_ingreso->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_ingreso->FldErrMsg());
		}
		if (!$this->fecha_egreso->FldIsDetailKey && !is_null($this->fecha_egreso->FormValue) && $this->fecha_egreso->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha_egreso->FldCaption(), $this->fecha_egreso->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha_egreso->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_egreso->FldErrMsg());
		}
		if (!$this->sueldo_inicial->FldIsDetailKey && !is_null($this->sueldo_inicial->FormValue) && $this->sueldo_inicial->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sueldo_inicial->FldCaption(), $this->sueldo_inicial->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->sueldo_inicial->FormValue)) {
			ew_AddMessage($gsFormError, $this->sueldo_inicial->FldErrMsg());
		}
		if (!$this->sueldo_final->FldIsDetailKey && !is_null($this->sueldo_final->FormValue) && $this->sueldo_final->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sueldo_final->FldCaption(), $this->sueldo_final->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->sueldo_final->FormValue)) {
			ew_AddMessage($gsFormError, $this->sueldo_final->FldErrMsg());
		}
		if (!$this->motivo_retiro->FldIsDetailKey && !is_null($this->motivo_retiro->FormValue) && $this->motivo_retiro->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->motivo_retiro->FldCaption(), $this->motivo_retiro->ReqErrMsg));
		}
		if (!$this->empleado_id->FldIsDetailKey && !is_null($this->empleado_id->FormValue) && $this->empleado_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->empleado_id->FldCaption(), $this->empleado_id->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// institucion
		$this->institucion->SetDbValueDef($rsnew, $this->institucion->CurrentValue, "", FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, "", FALSE);

		// telefono
		$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, "", FALSE);

		// puesto
		$this->puesto->SetDbValueDef($rsnew, $this->puesto->CurrentValue, "", FALSE);

		// atribuciones
		$this->atribuciones->SetDbValueDef($rsnew, $this->atribuciones->CurrentValue, "", FALSE);

		// jefe
		$this->jefe->SetDbValueDef($rsnew, $this->jefe->CurrentValue, "", FALSE);

		// fecha_ingreso
		$this->fecha_ingreso->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_ingreso->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// fecha_egreso
		$this->fecha_egreso->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_egreso->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// sueldo_inicial
		$this->sueldo_inicial->SetDbValueDef($rsnew, $this->sueldo_inicial->CurrentValue, 0, FALSE);

		// sueldo_final
		$this->sueldo_final->SetDbValueDef($rsnew, $this->sueldo_final->CurrentValue, 0, FALSE);

		// motivo_retiro
		$this->motivo_retiro->SetDbValueDef($rsnew, $this->motivo_retiro->CurrentValue, "", FALSE);

		// empleado_id
		$this->empleado_id->SetDbValueDef($rsnew, $this->empleado_id->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->historial_laboral_id->setDbValue($conn->Insert_ID());
				$rsnew['historial_laboral_id'] = $this->historial_laboral_id->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("historial_laborallist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($historial_laboral_add)) $historial_laboral_add = new chistorial_laboral_add();

// Page init
$historial_laboral_add->Page_Init();

// Page main
$historial_laboral_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$historial_laboral_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fhistorial_laboraladd = new ew_Form("fhistorial_laboraladd", "add");

// Validate form
fhistorial_laboraladd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_institucion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->institucion->FldCaption(), $historial_laboral->institucion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->direccion->FldCaption(), $historial_laboral->direccion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telefono");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->telefono->FldCaption(), $historial_laboral->telefono->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_puesto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->puesto->FldCaption(), $historial_laboral->puesto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_atribuciones");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->atribuciones->FldCaption(), $historial_laboral->atribuciones->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jefe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->jefe->FldCaption(), $historial_laboral->jefe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_ingreso");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->fecha_ingreso->FldCaption(), $historial_laboral->fecha_ingreso->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_ingreso");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($historial_laboral->fecha_ingreso->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_egreso");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->fecha_egreso->FldCaption(), $historial_laboral->fecha_egreso->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_egreso");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($historial_laboral->fecha_egreso->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sueldo_inicial");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->sueldo_inicial->FldCaption(), $historial_laboral->sueldo_inicial->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sueldo_inicial");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($historial_laboral->sueldo_inicial->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sueldo_final");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->sueldo_final->FldCaption(), $historial_laboral->sueldo_final->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sueldo_final");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($historial_laboral->sueldo_final->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_motivo_retiro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->motivo_retiro->FldCaption(), $historial_laboral->motivo_retiro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_empleado_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $historial_laboral->empleado_id->FldCaption(), $historial_laboral->empleado_id->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fhistorial_laboraladd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhistorial_laboraladd.ValidateRequired = true;
<?php } else { ?>
fhistorial_laboraladd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhistorial_laboraladd.Lists["x_empleado_id"] = {"LinkField":"x_empleado_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $historial_laboral_add->ShowPageHeader(); ?>
<?php
$historial_laboral_add->ShowMessage();
?>
<form name="fhistorial_laboraladd" id="fhistorial_laboraladd" class="<?php echo $historial_laboral_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($historial_laboral_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $historial_laboral_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="historial_laboral">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($historial_laboral->institucion->Visible) { // institucion ?>
	<div id="r_institucion" class="form-group">
		<label id="elh_historial_laboral_institucion" for="x_institucion" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->institucion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->institucion->CellAttributes() ?>>
<span id="el_historial_laboral_institucion">
<input type="text" data-table="historial_laboral" data-field="x_institucion" name="x_institucion" id="x_institucion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($historial_laboral->institucion->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->institucion->EditValue ?>"<?php echo $historial_laboral->institucion->EditAttributes() ?>>
</span>
<?php echo $historial_laboral->institucion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_historial_laboral_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->direccion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->direccion->CellAttributes() ?>>
<span id="el_historial_laboral_direccion">
<input type="text" data-table="historial_laboral" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($historial_laboral->direccion->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->direccion->EditValue ?>"<?php echo $historial_laboral->direccion->EditAttributes() ?>>
</span>
<?php echo $historial_laboral->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->telefono->Visible) { // telefono ?>
	<div id="r_telefono" class="form-group">
		<label id="elh_historial_laboral_telefono" for="x_telefono" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->telefono->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->telefono->CellAttributes() ?>>
<span id="el_historial_laboral_telefono">
<input type="text" data-table="historial_laboral" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="9" placeholder="<?php echo ew_HtmlEncode($historial_laboral->telefono->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->telefono->EditValue ?>"<?php echo $historial_laboral->telefono->EditAttributes() ?>>
</span>
<?php echo $historial_laboral->telefono->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->puesto->Visible) { // puesto ?>
	<div id="r_puesto" class="form-group">
		<label id="elh_historial_laboral_puesto" for="x_puesto" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->puesto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->puesto->CellAttributes() ?>>
<span id="el_historial_laboral_puesto">
<input type="text" data-table="historial_laboral" data-field="x_puesto" name="x_puesto" id="x_puesto" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($historial_laboral->puesto->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->puesto->EditValue ?>"<?php echo $historial_laboral->puesto->EditAttributes() ?>>
</span>
<?php echo $historial_laboral->puesto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->atribuciones->Visible) { // atribuciones ?>
	<div id="r_atribuciones" class="form-group">
		<label id="elh_historial_laboral_atribuciones" for="x_atribuciones" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->atribuciones->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->atribuciones->CellAttributes() ?>>
<span id="el_historial_laboral_atribuciones">
<input type="text" data-table="historial_laboral" data-field="x_atribuciones" name="x_atribuciones" id="x_atribuciones" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($historial_laboral->atribuciones->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->atribuciones->EditValue ?>"<?php echo $historial_laboral->atribuciones->EditAttributes() ?>>
</span>
<?php echo $historial_laboral->atribuciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->jefe->Visible) { // jefe ?>
	<div id="r_jefe" class="form-group">
		<label id="elh_historial_laboral_jefe" for="x_jefe" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->jefe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->jefe->CellAttributes() ?>>
<span id="el_historial_laboral_jefe">
<input type="text" data-table="historial_laboral" data-field="x_jefe" name="x_jefe" id="x_jefe" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($historial_laboral->jefe->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->jefe->EditValue ?>"<?php echo $historial_laboral->jefe->EditAttributes() ?>>
</span>
<?php echo $historial_laboral->jefe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->fecha_ingreso->Visible) { // fecha_ingreso ?>
	<div id="r_fecha_ingreso" class="form-group">
		<label id="elh_historial_laboral_fecha_ingreso" for="x_fecha_ingreso" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->fecha_ingreso->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->fecha_ingreso->CellAttributes() ?>>
<span id="el_historial_laboral_fecha_ingreso">
<input type="text" data-table="historial_laboral" data-field="x_fecha_ingreso" data-format="7" name="x_fecha_ingreso" id="x_fecha_ingreso" placeholder="<?php echo ew_HtmlEncode($historial_laboral->fecha_ingreso->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->fecha_ingreso->EditValue ?>"<?php echo $historial_laboral->fecha_ingreso->EditAttributes() ?>>
<?php if (!$historial_laboral->fecha_ingreso->ReadOnly && !$historial_laboral->fecha_ingreso->Disabled && !isset($historial_laboral->fecha_ingreso->EditAttrs["readonly"]) && !isset($historial_laboral->fecha_ingreso->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fhistorial_laboraladd", "x_fecha_ingreso", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $historial_laboral->fecha_ingreso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->fecha_egreso->Visible) { // fecha_egreso ?>
	<div id="r_fecha_egreso" class="form-group">
		<label id="elh_historial_laboral_fecha_egreso" for="x_fecha_egreso" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->fecha_egreso->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->fecha_egreso->CellAttributes() ?>>
<span id="el_historial_laboral_fecha_egreso">
<input type="text" data-table="historial_laboral" data-field="x_fecha_egreso" data-format="7" name="x_fecha_egreso" id="x_fecha_egreso" placeholder="<?php echo ew_HtmlEncode($historial_laboral->fecha_egreso->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->fecha_egreso->EditValue ?>"<?php echo $historial_laboral->fecha_egreso->EditAttributes() ?>>
<?php if (!$historial_laboral->fecha_egreso->ReadOnly && !$historial_laboral->fecha_egreso->Disabled && !isset($historial_laboral->fecha_egreso->EditAttrs["readonly"]) && !isset($historial_laboral->fecha_egreso->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fhistorial_laboraladd", "x_fecha_egreso", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $historial_laboral->fecha_egreso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->sueldo_inicial->Visible) { // sueldo_inicial ?>
	<div id="r_sueldo_inicial" class="form-group">
		<label id="elh_historial_laboral_sueldo_inicial" for="x_sueldo_inicial" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->sueldo_inicial->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->sueldo_inicial->CellAttributes() ?>>
<span id="el_historial_laboral_sueldo_inicial">
<input type="text" data-table="historial_laboral" data-field="x_sueldo_inicial" name="x_sueldo_inicial" id="x_sueldo_inicial" size="30" placeholder="<?php echo ew_HtmlEncode($historial_laboral->sueldo_inicial->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->sueldo_inicial->EditValue ?>"<?php echo $historial_laboral->sueldo_inicial->EditAttributes() ?>>
</span>
<?php echo $historial_laboral->sueldo_inicial->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->sueldo_final->Visible) { // sueldo_final ?>
	<div id="r_sueldo_final" class="form-group">
		<label id="elh_historial_laboral_sueldo_final" for="x_sueldo_final" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->sueldo_final->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->sueldo_final->CellAttributes() ?>>
<span id="el_historial_laboral_sueldo_final">
<input type="text" data-table="historial_laboral" data-field="x_sueldo_final" name="x_sueldo_final" id="x_sueldo_final" size="30" placeholder="<?php echo ew_HtmlEncode($historial_laboral->sueldo_final->getPlaceHolder()) ?>" value="<?php echo $historial_laboral->sueldo_final->EditValue ?>"<?php echo $historial_laboral->sueldo_final->EditAttributes() ?>>
</span>
<?php echo $historial_laboral->sueldo_final->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->motivo_retiro->Visible) { // motivo_retiro ?>
	<div id="r_motivo_retiro" class="form-group">
		<label id="elh_historial_laboral_motivo_retiro" for="x_motivo_retiro" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->motivo_retiro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->motivo_retiro->CellAttributes() ?>>
<span id="el_historial_laboral_motivo_retiro">
<textarea data-table="historial_laboral" data-field="x_motivo_retiro" name="x_motivo_retiro" id="x_motivo_retiro" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($historial_laboral->motivo_retiro->getPlaceHolder()) ?>"<?php echo $historial_laboral->motivo_retiro->EditAttributes() ?>><?php echo $historial_laboral->motivo_retiro->EditValue ?></textarea>
</span>
<?php echo $historial_laboral->motivo_retiro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($historial_laboral->empleado_id->Visible) { // empleado_id ?>
	<div id="r_empleado_id" class="form-group">
		<label id="elh_historial_laboral_empleado_id" for="x_empleado_id" class="col-sm-2 control-label ewLabel"><?php echo $historial_laboral->empleado_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $historial_laboral->empleado_id->CellAttributes() ?>>
<span id="el_historial_laboral_empleado_id">
<select data-table="historial_laboral" data-field="x_empleado_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($historial_laboral->empleado_id->DisplayValueSeparator) ? json_encode($historial_laboral->empleado_id->DisplayValueSeparator) : $historial_laboral->empleado_id->DisplayValueSeparator) ?>" id="x_empleado_id" name="x_empleado_id"<?php echo $historial_laboral->empleado_id->EditAttributes() ?>>
<?php
if (is_array($historial_laboral->empleado_id->EditValue)) {
	$arwrk = $historial_laboral->empleado_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($historial_laboral->empleado_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $historial_laboral->empleado_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($historial_laboral->empleado_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($historial_laboral->empleado_id->CurrentValue) ?>" selected><?php echo $historial_laboral->empleado_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `empleado_id`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
$sWhereWrk = "";
$historial_laboral->empleado_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$historial_laboral->empleado_id->LookupFilters += array("f0" => "`empleado_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$historial_laboral->Lookup_Selecting($historial_laboral->empleado_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $historial_laboral->empleado_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_empleado_id" id="s_x_empleado_id" value="<?php echo $historial_laboral->empleado_id->LookupFilterQuery() ?>">
</span>
<?php echo $historial_laboral->empleado_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $historial_laboral_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fhistorial_laboraladd.Init();
</script>
<?php
$historial_laboral_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$historial_laboral_add->Page_Terminate();
?>
