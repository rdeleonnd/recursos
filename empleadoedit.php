<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "empleadoinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$empleado_edit = NULL; // Initialize page object first

class cempleado_edit extends cempleado {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'empleado';

	// Page object name
	var $PageObjName = 'empleado_edit';

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

		// Table object (empleado)
		if (!isset($GLOBALS["empleado"]) || get_class($GLOBALS["empleado"]) == "cempleado") {
			$GLOBALS["empleado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empleado"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empleado', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("empleadolist.php"));
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
		global $EW_EXPORT, $empleado;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($empleado);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["empleado_id"] <> "") {
			$this->empleado_id->setQueryStringValue($_GET["empleado_id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->empleado_id->CurrentValue == "")
			$this->Page_Terminate("empleadolist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("empleadolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "empleadolist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->codigo->FldIsDetailKey) {
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
		}
		if (!$this->cui->FldIsDetailKey) {
			$this->cui->setFormValue($objForm->GetValue("x_cui"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->apellido->FldIsDetailKey) {
			$this->apellido->setFormValue($objForm->GetValue("x_apellido"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->departamento_origen_id->FldIsDetailKey) {
			$this->departamento_origen_id->setFormValue($objForm->GetValue("x_departamento_origen_id"));
		}
		if (!$this->municipio_id->FldIsDetailKey) {
			$this->municipio_id->setFormValue($objForm->GetValue("x_municipio_id"));
		}
		if (!$this->telefono_residencia->FldIsDetailKey) {
			$this->telefono_residencia->setFormValue($objForm->GetValue("x_telefono_residencia"));
		}
		if (!$this->telefono_celular->FldIsDetailKey) {
			$this->telefono_celular->setFormValue($objForm->GetValue("x_telefono_celular"));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey) {
			$this->fecha_nacimiento->setFormValue($objForm->GetValue("x_fecha_nacimiento"));
			$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		}
		if (!$this->nacionalidad->FldIsDetailKey) {
			$this->nacionalidad->setFormValue($objForm->GetValue("x_nacionalidad"));
		}
		if (!$this->estado_civil->FldIsDetailKey) {
			$this->estado_civil->setFormValue($objForm->GetValue("x_estado_civil"));
		}
		if (!$this->sexo->FldIsDetailKey) {
			$this->sexo->setFormValue($objForm->GetValue("x_sexo"));
		}
		if (!$this->igss->FldIsDetailKey) {
			$this->igss->setFormValue($objForm->GetValue("x_igss"));
		}
		if (!$this->nit->FldIsDetailKey) {
			$this->nit->setFormValue($objForm->GetValue("x_nit"));
		}
		if (!$this->licencia_conducir->FldIsDetailKey) {
			$this->licencia_conducir->setFormValue($objForm->GetValue("x_licencia_conducir"));
		}
		if (!$this->area_id->FldIsDetailKey) {
			$this->area_id->setFormValue($objForm->GetValue("x_area_id"));
		}
		if (!$this->departmento_id->FldIsDetailKey) {
			$this->departmento_id->setFormValue($objForm->GetValue("x_departmento_id"));
		}
		if (!$this->seccion_id->FldIsDetailKey) {
			$this->seccion_id->setFormValue($objForm->GetValue("x_seccion_id"));
		}
		if (!$this->puesto_id->FldIsDetailKey) {
			$this->puesto_id->setFormValue($objForm->GetValue("x_puesto_id"));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
		if (!$this->tipo_sangre_id->FldIsDetailKey) {
			$this->tipo_sangre_id->setFormValue($objForm->GetValue("x_tipo_sangre_id"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->empleado_id->FldIsDetailKey)
			$this->empleado_id->setFormValue($objForm->GetValue("x_empleado_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->empleado_id->CurrentValue = $this->empleado_id->FormValue;
		$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->cui->CurrentValue = $this->cui->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->apellido->CurrentValue = $this->apellido->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->departamento_origen_id->CurrentValue = $this->departamento_origen_id->FormValue;
		$this->municipio_id->CurrentValue = $this->municipio_id->FormValue;
		$this->telefono_residencia->CurrentValue = $this->telefono_residencia->FormValue;
		$this->telefono_celular->CurrentValue = $this->telefono_celular->FormValue;
		$this->fecha_nacimiento->CurrentValue = $this->fecha_nacimiento->FormValue;
		$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		$this->nacionalidad->CurrentValue = $this->nacionalidad->FormValue;
		$this->estado_civil->CurrentValue = $this->estado_civil->FormValue;
		$this->sexo->CurrentValue = $this->sexo->FormValue;
		$this->igss->CurrentValue = $this->igss->FormValue;
		$this->nit->CurrentValue = $this->nit->FormValue;
		$this->licencia_conducir->CurrentValue = $this->licencia_conducir->FormValue;
		$this->area_id->CurrentValue = $this->area_id->FormValue;
		$this->departmento_id->CurrentValue = $this->departmento_id->FormValue;
		$this->seccion_id->CurrentValue = $this->seccion_id->FormValue;
		$this->puesto_id->CurrentValue = $this->puesto_id->FormValue;
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
		$this->tipo_sangre_id->CurrentValue = $this->tipo_sangre_id->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->empleado_id->DbValue = $row['empleado_id'];
		$this->codigo->DbValue = $row['codigo'];
		$this->cui->DbValue = $row['cui'];
		$this->nombre->DbValue = $row['nombre'];
		$this->apellido->DbValue = $row['apellido'];
		$this->direccion->DbValue = $row['direccion'];
		$this->departamento_origen_id->DbValue = $row['departamento_origen_id'];
		$this->municipio_id->DbValue = $row['municipio_id'];
		$this->telefono_residencia->DbValue = $row['telefono_residencia'];
		$this->telefono_celular->DbValue = $row['telefono_celular'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->nacionalidad->DbValue = $row['nacionalidad'];
		$this->estado_civil->DbValue = $row['estado_civil'];
		$this->sexo->DbValue = $row['sexo'];
		$this->igss->DbValue = $row['igss'];
		$this->nit->DbValue = $row['nit'];
		$this->licencia_conducir->DbValue = $row['licencia_conducir'];
		$this->area_id->DbValue = $row['area_id'];
		$this->departmento_id->DbValue = $row['departmento_id'];
		$this->seccion_id->DbValue = $row['seccion_id'];
		$this->puesto_id->DbValue = $row['puesto_id'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->tipo_sangre_id->DbValue = $row['tipo_sangre_id'];
		$this->estado->DbValue = $row['estado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// empleado_id
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// codigo
			$this->codigo->EditAttrs["class"] = "form-control";
			$this->codigo->EditCustomAttributes = "";
			$this->codigo->EditValue = ew_HtmlEncode($this->codigo->CurrentValue);
			$this->codigo->PlaceHolder = ew_RemoveHtml($this->codigo->FldCaption());

			// cui
			$this->cui->EditAttrs["class"] = "form-control";
			$this->cui->EditCustomAttributes = "";
			$this->cui->EditValue = ew_HtmlEncode($this->cui->CurrentValue);
			$this->cui->PlaceHolder = ew_RemoveHtml($this->cui->FldCaption());

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// apellido
			$this->apellido->EditAttrs["class"] = "form-control";
			$this->apellido->EditCustomAttributes = "";
			$this->apellido->EditValue = ew_HtmlEncode($this->apellido->CurrentValue);
			$this->apellido->PlaceHolder = ew_RemoveHtml($this->apellido->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// departamento_origen_id
			$this->departamento_origen_id->EditAttrs["class"] = "form-control";
			$this->departamento_origen_id->EditCustomAttributes = "";
			if (trim(strval($this->departamento_origen_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`departamento_origen_id`" . ew_SearchString("=", $this->departamento_origen_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `departamento_origen_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamento_origen`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->departamento_origen_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->departamento_origen_id->EditValue = $arwrk;

			// municipio_id
			$this->municipio_id->EditAttrs["class"] = "form-control";
			$this->municipio_id->EditCustomAttributes = "";
			if (trim(strval($this->municipio_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`municipio_id`" . ew_SearchString("=", $this->municipio_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `municipio_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `municipio`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->municipio_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->municipio_id->EditValue = $arwrk;

			// telefono_residencia
			$this->telefono_residencia->EditAttrs["class"] = "form-control";
			$this->telefono_residencia->EditCustomAttributes = "";
			$this->telefono_residencia->EditValue = ew_HtmlEncode($this->telefono_residencia->CurrentValue);
			$this->telefono_residencia->PlaceHolder = ew_RemoveHtml($this->telefono_residencia->FldCaption());

			// telefono_celular
			$this->telefono_celular->EditAttrs["class"] = "form-control";
			$this->telefono_celular->EditCustomAttributes = "";
			$this->telefono_celular->EditValue = ew_HtmlEncode($this->telefono_celular->CurrentValue);
			$this->telefono_celular->PlaceHolder = ew_RemoveHtml($this->telefono_celular->FldCaption());

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 7));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// nacionalidad
			$this->nacionalidad->EditAttrs["class"] = "form-control";
			$this->nacionalidad->EditCustomAttributes = "";
			if (trim(strval($this->nacionalidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`nacionalidad_id`" . ew_SearchString("=", $this->nacionalidad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `nacionalidad_id`, `nacionalidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `nacionalidad`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->nacionalidad, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nacionalidad->EditValue = $arwrk;

			// estado_civil
			$this->estado_civil->EditAttrs["class"] = "form-control";
			$this->estado_civil->EditCustomAttributes = "";
			$this->estado_civil->EditValue = ew_HtmlEncode($this->estado_civil->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->estado_civil->EditValue = $this->estado_civil->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->estado_civil->EditValue = ew_HtmlEncode($this->estado_civil->CurrentValue);
				}
			} else {
				$this->estado_civil->EditValue = NULL;
			}
			$this->estado_civil->PlaceHolder = ew_RemoveHtml($this->estado_civil->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$this->sexo->EditValue = ew_HtmlEncode($this->sexo->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->sexo->EditValue = $this->sexo->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->sexo->EditValue = ew_HtmlEncode($this->sexo->CurrentValue);
				}
			} else {
				$this->sexo->EditValue = NULL;
			}
			$this->sexo->PlaceHolder = ew_RemoveHtml($this->sexo->FldCaption());

			// igss
			$this->igss->EditAttrs["class"] = "form-control";
			$this->igss->EditCustomAttributes = "";
			$this->igss->EditValue = ew_HtmlEncode($this->igss->CurrentValue);
			$this->igss->PlaceHolder = ew_RemoveHtml($this->igss->FldCaption());

			// nit
			$this->nit->EditAttrs["class"] = "form-control";
			$this->nit->EditCustomAttributes = "";
			$this->nit->EditValue = ew_HtmlEncode($this->nit->CurrentValue);
			$this->nit->PlaceHolder = ew_RemoveHtml($this->nit->FldCaption());

			// licencia_conducir
			$this->licencia_conducir->EditAttrs["class"] = "form-control";
			$this->licencia_conducir->EditCustomAttributes = "";
			$this->licencia_conducir->EditValue = ew_HtmlEncode($this->licencia_conducir->CurrentValue);
			$this->licencia_conducir->PlaceHolder = ew_RemoveHtml($this->licencia_conducir->FldCaption());

			// area_id
			$this->area_id->EditAttrs["class"] = "form-control";
			$this->area_id->EditCustomAttributes = "";
			$this->area_id->EditValue = ew_HtmlEncode($this->area_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->area_id->EditValue = $this->area_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->area_id->EditValue = ew_HtmlEncode($this->area_id->CurrentValue);
				}
			} else {
				$this->area_id->EditValue = NULL;
			}
			$this->area_id->PlaceHolder = ew_RemoveHtml($this->area_id->FldCaption());

			// departmento_id
			$this->departmento_id->EditAttrs["class"] = "form-control";
			$this->departmento_id->EditCustomAttributes = "";
			if (trim(strval($this->departmento_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`departamento_id`" . ew_SearchString("=", $this->departmento_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `departamento_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `departamento_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamento`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->departmento_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->departmento_id->EditValue = $arwrk;

			// seccion_id
			$this->seccion_id->EditAttrs["class"] = "form-control";
			$this->seccion_id->EditCustomAttributes = "";
			if (trim(strval($this->seccion_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`seccion_id`" . ew_SearchString("=", $this->seccion_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `seccion_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `seccion_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `seccion`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->seccion_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->seccion_id->EditValue = $arwrk;

			// puesto_id
			$this->puesto_id->EditAttrs["class"] = "form-control";
			$this->puesto_id->EditCustomAttributes = "";
			if (trim(strval($this->puesto_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`puesto_id`" . ew_SearchString("=", $this->puesto_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `puesto_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `puesto_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `puesto`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->puesto_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->puesto_id->EditValue = $arwrk;

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// tipo_sangre_id
			$this->tipo_sangre_id->EditAttrs["class"] = "form-control";
			$this->tipo_sangre_id->EditCustomAttributes = "";
			if (trim(strval($this->tipo_sangre_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`tipo_sangre_id`" . ew_SearchString("=", $this->tipo_sangre_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `tipo_sangre_id`, `tipo_sangre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_sangre`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tipo_sangre_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tipo_sangre_id->EditValue = $arwrk;

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = ew_HtmlEncode($this->estado->CurrentValue);
			$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

			// Edit refer script
			// codigo

			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";

			// cui
			$this->cui->LinkCustomAttributes = "";
			$this->cui->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// apellido
			$this->apellido->LinkCustomAttributes = "";
			$this->apellido->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// departamento_origen_id
			$this->departamento_origen_id->LinkCustomAttributes = "";
			$this->departamento_origen_id->HrefValue = "";

			// municipio_id
			$this->municipio_id->LinkCustomAttributes = "";
			$this->municipio_id->HrefValue = "";

			// telefono_residencia
			$this->telefono_residencia->LinkCustomAttributes = "";
			$this->telefono_residencia->HrefValue = "";

			// telefono_celular
			$this->telefono_celular->LinkCustomAttributes = "";
			$this->telefono_celular->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";

			// nacionalidad
			$this->nacionalidad->LinkCustomAttributes = "";
			$this->nacionalidad->HrefValue = "";

			// estado_civil
			$this->estado_civil->LinkCustomAttributes = "";
			$this->estado_civil->HrefValue = "";

			// sexo
			$this->sexo->LinkCustomAttributes = "";
			$this->sexo->HrefValue = "";

			// igss
			$this->igss->LinkCustomAttributes = "";
			$this->igss->HrefValue = "";

			// nit
			$this->nit->LinkCustomAttributes = "";
			$this->nit->HrefValue = "";

			// licencia_conducir
			$this->licencia_conducir->LinkCustomAttributes = "";
			$this->licencia_conducir->HrefValue = "";

			// area_id
			$this->area_id->LinkCustomAttributes = "";
			$this->area_id->HrefValue = "";

			// departmento_id
			$this->departmento_id->LinkCustomAttributes = "";
			$this->departmento_id->HrefValue = "";

			// seccion_id
			$this->seccion_id->LinkCustomAttributes = "";
			$this->seccion_id->HrefValue = "";

			// puesto_id
			$this->puesto_id->LinkCustomAttributes = "";
			$this->puesto_id->HrefValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";

			// tipo_sangre_id
			$this->tipo_sangre_id->LinkCustomAttributes = "";
			$this->tipo_sangre_id->HrefValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
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
		if (!$this->codigo->FldIsDetailKey && !is_null($this->codigo->FormValue) && $this->codigo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codigo->FldCaption(), $this->codigo->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->codigo->FormValue)) {
			ew_AddMessage($gsFormError, $this->codigo->FldErrMsg());
		}
		if (!$this->cui->FldIsDetailKey && !is_null($this->cui->FormValue) && $this->cui->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cui->FldCaption(), $this->cui->ReqErrMsg));
		}
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->apellido->FldIsDetailKey && !is_null($this->apellido->FormValue) && $this->apellido->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->apellido->FldCaption(), $this->apellido->ReqErrMsg));
		}
		if (!$this->direccion->FldIsDetailKey && !is_null($this->direccion->FormValue) && $this->direccion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->direccion->FldCaption(), $this->direccion->ReqErrMsg));
		}
		if (!$this->departamento_origen_id->FldIsDetailKey && !is_null($this->departamento_origen_id->FormValue) && $this->departamento_origen_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->departamento_origen_id->FldCaption(), $this->departamento_origen_id->ReqErrMsg));
		}
		if (!$this->municipio_id->FldIsDetailKey && !is_null($this->municipio_id->FormValue) && $this->municipio_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->municipio_id->FldCaption(), $this->municipio_id->ReqErrMsg));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey && !is_null($this->fecha_nacimiento->FormValue) && $this->fecha_nacimiento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha_nacimiento->FldCaption(), $this->fecha_nacimiento->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha_nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_nacimiento->FldErrMsg());
		}
		if (!$this->nacionalidad->FldIsDetailKey && !is_null($this->nacionalidad->FormValue) && $this->nacionalidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nacionalidad->FldCaption(), $this->nacionalidad->ReqErrMsg));
		}
		if (!$this->estado_civil->FldIsDetailKey && !is_null($this->estado_civil->FormValue) && $this->estado_civil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado_civil->FldCaption(), $this->estado_civil->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->estado_civil->FormValue)) {
			ew_AddMessage($gsFormError, $this->estado_civil->FldErrMsg());
		}
		if (!$this->sexo->FldIsDetailKey && !is_null($this->sexo->FormValue) && $this->sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sexo->FldCaption(), $this->sexo->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->sexo->FormValue)) {
			ew_AddMessage($gsFormError, $this->sexo->FldErrMsg());
		}
		if (!$this->nit->FldIsDetailKey && !is_null($this->nit->FormValue) && $this->nit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nit->FldCaption(), $this->nit->ReqErrMsg));
		}
		if (!$this->area_id->FldIsDetailKey && !is_null($this->area_id->FormValue) && $this->area_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->area_id->FldCaption(), $this->area_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->area_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->area_id->FldErrMsg());
		}
		if (!$this->departmento_id->FldIsDetailKey && !is_null($this->departmento_id->FormValue) && $this->departmento_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->departmento_id->FldCaption(), $this->departmento_id->ReqErrMsg));
		}
		if (!$this->seccion_id->FldIsDetailKey && !is_null($this->seccion_id->FormValue) && $this->seccion_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->seccion_id->FldCaption(), $this->seccion_id->ReqErrMsg));
		}
		if (!$this->puesto_id->FldIsDetailKey && !is_null($this->puesto_id->FormValue) && $this->puesto_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->puesto_id->FldCaption(), $this->puesto_id->ReqErrMsg));
		}
		if (!$this->observaciones->FldIsDetailKey && !is_null($this->observaciones->FormValue) && $this->observaciones->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->observaciones->FldCaption(), $this->observaciones->ReqErrMsg));
		}
		if (!$this->tipo_sangre_id->FldIsDetailKey && !is_null($this->tipo_sangre_id->FormValue) && $this->tipo_sangre_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipo_sangre_id->FldCaption(), $this->tipo_sangre_id->ReqErrMsg));
		}
		if (!$this->estado->FldIsDetailKey && !is_null($this->estado->FormValue) && $this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// codigo
			$this->codigo->SetDbValueDef($rsnew, $this->codigo->CurrentValue, 0, $this->codigo->ReadOnly);

			// cui
			$this->cui->SetDbValueDef($rsnew, $this->cui->CurrentValue, "", $this->cui->ReadOnly);

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", $this->nombre->ReadOnly);

			// apellido
			$this->apellido->SetDbValueDef($rsnew, $this->apellido->CurrentValue, "", $this->apellido->ReadOnly);

			// direccion
			$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, "", $this->direccion->ReadOnly);

			// departamento_origen_id
			$this->departamento_origen_id->SetDbValueDef($rsnew, $this->departamento_origen_id->CurrentValue, 0, $this->departamento_origen_id->ReadOnly);

			// municipio_id
			$this->municipio_id->SetDbValueDef($rsnew, $this->municipio_id->CurrentValue, 0, $this->municipio_id->ReadOnly);

			// telefono_residencia
			$this->telefono_residencia->SetDbValueDef($rsnew, $this->telefono_residencia->CurrentValue, NULL, $this->telefono_residencia->ReadOnly);

			// telefono_celular
			$this->telefono_celular->SetDbValueDef($rsnew, $this->telefono_celular->CurrentValue, NULL, $this->telefono_celular->ReadOnly);

			// fecha_nacimiento
			$this->fecha_nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7), ew_CurrentDate(), $this->fecha_nacimiento->ReadOnly);

			// nacionalidad
			$this->nacionalidad->SetDbValueDef($rsnew, $this->nacionalidad->CurrentValue, 0, $this->nacionalidad->ReadOnly);

			// estado_civil
			$this->estado_civil->SetDbValueDef($rsnew, $this->estado_civil->CurrentValue, 0, $this->estado_civil->ReadOnly);

			// sexo
			$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, 0, $this->sexo->ReadOnly);

			// igss
			$this->igss->SetDbValueDef($rsnew, $this->igss->CurrentValue, NULL, $this->igss->ReadOnly);

			// nit
			$this->nit->SetDbValueDef($rsnew, $this->nit->CurrentValue, "", $this->nit->ReadOnly);

			// licencia_conducir
			$this->licencia_conducir->SetDbValueDef($rsnew, $this->licencia_conducir->CurrentValue, NULL, $this->licencia_conducir->ReadOnly);

			// area_id
			$this->area_id->SetDbValueDef($rsnew, $this->area_id->CurrentValue, 0, $this->area_id->ReadOnly);

			// departmento_id
			$this->departmento_id->SetDbValueDef($rsnew, $this->departmento_id->CurrentValue, 0, $this->departmento_id->ReadOnly);

			// seccion_id
			$this->seccion_id->SetDbValueDef($rsnew, $this->seccion_id->CurrentValue, 0, $this->seccion_id->ReadOnly);

			// puesto_id
			$this->puesto_id->SetDbValueDef($rsnew, $this->puesto_id->CurrentValue, 0, $this->puesto_id->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, "", $this->observaciones->ReadOnly);

			// tipo_sangre_id
			$this->tipo_sangre_id->SetDbValueDef($rsnew, $this->tipo_sangre_id->CurrentValue, 0, $this->tipo_sangre_id->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", $this->estado->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("empleadolist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($empleado_edit)) $empleado_edit = new cempleado_edit();

// Page init
$empleado_edit->Page_Init();

// Page main
$empleado_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empleado_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fempleadoedit = new ew_Form("fempleadoedit", "edit");

// Validate form
fempleadoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codigo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->codigo->FldCaption(), $empleado->codigo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_codigo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empleado->codigo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cui");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->cui->FldCaption(), $empleado->cui->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->nombre->FldCaption(), $empleado->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_apellido");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->apellido->FldCaption(), $empleado->apellido->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->direccion->FldCaption(), $empleado->direccion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_departamento_origen_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->departamento_origen_id->FldCaption(), $empleado->departamento_origen_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_municipio_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->municipio_id->FldCaption(), $empleado->municipio_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->fecha_nacimiento->FldCaption(), $empleado->fecha_nacimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empleado->fecha_nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nacionalidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->nacionalidad->FldCaption(), $empleado->nacionalidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado_civil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->estado_civil->FldCaption(), $empleado->estado_civil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado_civil");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empleado->estado_civil->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->sexo->FldCaption(), $empleado->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empleado->sexo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->nit->FldCaption(), $empleado->nit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_area_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->area_id->FldCaption(), $empleado->area_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_area_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empleado->area_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_departmento_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->departmento_id->FldCaption(), $empleado->departmento_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_seccion_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->seccion_id->FldCaption(), $empleado->seccion_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_puesto_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->puesto_id->FldCaption(), $empleado->puesto_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_observaciones");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->observaciones->FldCaption(), $empleado->observaciones->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipo_sangre_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->tipo_sangre_id->FldCaption(), $empleado->tipo_sangre_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->estado->FldCaption(), $empleado->estado->ReqErrMsg)) ?>");

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
fempleadoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadoedit.ValidateRequired = true;
<?php } else { ?>
fempleadoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempleadoedit.Lists["x_departamento_origen_id"] = {"LinkField":"x_departamento_origen_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoedit.Lists["x_municipio_id"] = {"LinkField":"x_municipio_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoedit.Lists["x_nacionalidad"] = {"LinkField":"x_nacionalidad_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nacionalidad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoedit.Lists["x_estado_civil"] = {"LinkField":"x_estado_civil_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_estado_civil","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoedit.Lists["x_sexo"] = {"LinkField":"x_sexo_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sexo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoedit.Lists["x_area_id"] = {"LinkField":"x_area_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoedit.Lists["x_departmento_id"] = {"LinkField":"x_departamento_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":["x_departmento_id"],"ChildFields":["x_departmento_id"],"FilterFields":["x_departamento_id"],"Options":[],"Template":""};
fempleadoedit.Lists["x_seccion_id"] = {"LinkField":"x_seccion_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":["x_seccion_id"],"ChildFields":["x_seccion_id"],"FilterFields":["x_seccion_id"],"Options":[],"Template":""};
fempleadoedit.Lists["x_puesto_id"] = {"LinkField":"x_puesto_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":["x_puesto_id"],"ChildFields":["x_puesto_id"],"FilterFields":["x_puesto_id"],"Options":[],"Template":""};
fempleadoedit.Lists["x_tipo_sangre_id"] = {"LinkField":"x_tipo_sangre_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_sangre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $empleado_edit->ShowPageHeader(); ?>
<?php
$empleado_edit->ShowMessage();
?>
<form name="fempleadoedit" id="fempleadoedit" class="<?php echo $empleado_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empleado_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empleado_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empleado">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($empleado->codigo->Visible) { // codigo ?>
	<div id="r_codigo" class="form-group">
		<label id="elh_empleado_codigo" for="x_codigo" class="col-sm-2 control-label ewLabel"><?php echo $empleado->codigo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->codigo->CellAttributes() ?>>
<span id="el_empleado_codigo">
<input type="text" data-table="empleado" data-field="x_codigo" name="x_codigo" id="x_codigo" size="30" placeholder="<?php echo ew_HtmlEncode($empleado->codigo->getPlaceHolder()) ?>" value="<?php echo $empleado->codigo->EditValue ?>"<?php echo $empleado->codigo->EditAttributes() ?>>
</span>
<?php echo $empleado->codigo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->cui->Visible) { // cui ?>
	<div id="r_cui" class="form-group">
		<label id="elh_empleado_cui" for="x_cui" class="col-sm-2 control-label ewLabel"><?php echo $empleado->cui->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->cui->CellAttributes() ?>>
<span id="el_empleado_cui">
<input type="text" data-table="empleado" data-field="x_cui" name="x_cui" id="x_cui" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($empleado->cui->getPlaceHolder()) ?>" value="<?php echo $empleado->cui->EditValue ?>"<?php echo $empleado->cui->EditAttributes() ?>>
</span>
<?php echo $empleado->cui->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_empleado_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $empleado->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->nombre->CellAttributes() ?>>
<span id="el_empleado_nombre">
<input type="text" data-table="empleado" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($empleado->nombre->getPlaceHolder()) ?>" value="<?php echo $empleado->nombre->EditValue ?>"<?php echo $empleado->nombre->EditAttributes() ?>>
</span>
<?php echo $empleado->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->apellido->Visible) { // apellido ?>
	<div id="r_apellido" class="form-group">
		<label id="elh_empleado_apellido" for="x_apellido" class="col-sm-2 control-label ewLabel"><?php echo $empleado->apellido->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->apellido->CellAttributes() ?>>
<span id="el_empleado_apellido">
<input type="text" data-table="empleado" data-field="x_apellido" name="x_apellido" id="x_apellido" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($empleado->apellido->getPlaceHolder()) ?>" value="<?php echo $empleado->apellido->EditValue ?>"<?php echo $empleado->apellido->EditAttributes() ?>>
</span>
<?php echo $empleado->apellido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_empleado_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $empleado->direccion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->direccion->CellAttributes() ?>>
<span id="el_empleado_direccion">
<input type="text" data-table="empleado" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($empleado->direccion->getPlaceHolder()) ?>" value="<?php echo $empleado->direccion->EditValue ?>"<?php echo $empleado->direccion->EditAttributes() ?>>
</span>
<?php echo $empleado->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->departamento_origen_id->Visible) { // departamento_origen_id ?>
	<div id="r_departamento_origen_id" class="form-group">
		<label id="elh_empleado_departamento_origen_id" for="x_departamento_origen_id" class="col-sm-2 control-label ewLabel"><?php echo $empleado->departamento_origen_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->departamento_origen_id->CellAttributes() ?>>
<span id="el_empleado_departamento_origen_id">
<select data-table="empleado" data-field="x_departamento_origen_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->departamento_origen_id->DisplayValueSeparator) ? json_encode($empleado->departamento_origen_id->DisplayValueSeparator) : $empleado->departamento_origen_id->DisplayValueSeparator) ?>" id="x_departamento_origen_id" name="x_departamento_origen_id"<?php echo $empleado->departamento_origen_id->EditAttributes() ?>>
<?php
if (is_array($empleado->departamento_origen_id->EditValue)) {
	$arwrk = $empleado->departamento_origen_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($empleado->departamento_origen_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $empleado->departamento_origen_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($empleado->departamento_origen_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($empleado->departamento_origen_id->CurrentValue) ?>" selected><?php echo $empleado->departamento_origen_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `departamento_origen_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento_origen`";
$sWhereWrk = "";
$empleado->departamento_origen_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$empleado->departamento_origen_id->LookupFilters += array("f0" => "`departamento_origen_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$empleado->Lookup_Selecting($empleado->departamento_origen_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $empleado->departamento_origen_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_departamento_origen_id" id="s_x_departamento_origen_id" value="<?php echo $empleado->departamento_origen_id->LookupFilterQuery() ?>">
</span>
<?php echo $empleado->departamento_origen_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->municipio_id->Visible) { // municipio_id ?>
	<div id="r_municipio_id" class="form-group">
		<label id="elh_empleado_municipio_id" for="x_municipio_id" class="col-sm-2 control-label ewLabel"><?php echo $empleado->municipio_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->municipio_id->CellAttributes() ?>>
<span id="el_empleado_municipio_id">
<select data-table="empleado" data-field="x_municipio_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->municipio_id->DisplayValueSeparator) ? json_encode($empleado->municipio_id->DisplayValueSeparator) : $empleado->municipio_id->DisplayValueSeparator) ?>" id="x_municipio_id" name="x_municipio_id"<?php echo $empleado->municipio_id->EditAttributes() ?>>
<?php
if (is_array($empleado->municipio_id->EditValue)) {
	$arwrk = $empleado->municipio_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($empleado->municipio_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $empleado->municipio_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($empleado->municipio_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($empleado->municipio_id->CurrentValue) ?>" selected><?php echo $empleado->municipio_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `municipio_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
$sWhereWrk = "";
$empleado->municipio_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$empleado->municipio_id->LookupFilters += array("f0" => "`municipio_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$empleado->Lookup_Selecting($empleado->municipio_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $empleado->municipio_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_municipio_id" id="s_x_municipio_id" value="<?php echo $empleado->municipio_id->LookupFilterQuery() ?>">
</span>
<?php echo $empleado->municipio_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->telefono_residencia->Visible) { // telefono_residencia ?>
	<div id="r_telefono_residencia" class="form-group">
		<label id="elh_empleado_telefono_residencia" for="x_telefono_residencia" class="col-sm-2 control-label ewLabel"><?php echo $empleado->telefono_residencia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->telefono_residencia->CellAttributes() ?>>
<span id="el_empleado_telefono_residencia">
<input type="text" data-table="empleado" data-field="x_telefono_residencia" name="x_telefono_residencia" id="x_telefono_residencia" size="30" maxlength="9" placeholder="<?php echo ew_HtmlEncode($empleado->telefono_residencia->getPlaceHolder()) ?>" value="<?php echo $empleado->telefono_residencia->EditValue ?>"<?php echo $empleado->telefono_residencia->EditAttributes() ?>>
</span>
<?php echo $empleado->telefono_residencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->telefono_celular->Visible) { // telefono_celular ?>
	<div id="r_telefono_celular" class="form-group">
		<label id="elh_empleado_telefono_celular" for="x_telefono_celular" class="col-sm-2 control-label ewLabel"><?php echo $empleado->telefono_celular->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->telefono_celular->CellAttributes() ?>>
<span id="el_empleado_telefono_celular">
<input type="text" data-table="empleado" data-field="x_telefono_celular" name="x_telefono_celular" id="x_telefono_celular" size="30" maxlength="9" placeholder="<?php echo ew_HtmlEncode($empleado->telefono_celular->getPlaceHolder()) ?>" value="<?php echo $empleado->telefono_celular->EditValue ?>"<?php echo $empleado->telefono_celular->EditAttributes() ?>>
</span>
<?php echo $empleado->telefono_celular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<div id="r_fecha_nacimiento" class="form-group">
		<label id="elh_empleado_fecha_nacimiento" for="x_fecha_nacimiento" class="col-sm-2 control-label ewLabel"><?php echo $empleado->fecha_nacimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->fecha_nacimiento->CellAttributes() ?>>
<span id="el_empleado_fecha_nacimiento">
<input type="text" data-table="empleado" data-field="x_fecha_nacimiento" data-format="7" name="x_fecha_nacimiento" id="x_fecha_nacimiento" placeholder="<?php echo ew_HtmlEncode($empleado->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $empleado->fecha_nacimiento->EditValue ?>"<?php echo $empleado->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$empleado->fecha_nacimiento->ReadOnly && !$empleado->fecha_nacimiento->Disabled && !isset($empleado->fecha_nacimiento->EditAttrs["readonly"]) && !isset($empleado->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fempleadoedit", "x_fecha_nacimiento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $empleado->fecha_nacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->nacionalidad->Visible) { // nacionalidad ?>
	<div id="r_nacionalidad" class="form-group">
		<label id="elh_empleado_nacionalidad" for="x_nacionalidad" class="col-sm-2 control-label ewLabel"><?php echo $empleado->nacionalidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->nacionalidad->CellAttributes() ?>>
<span id="el_empleado_nacionalidad">
<select data-table="empleado" data-field="x_nacionalidad" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->nacionalidad->DisplayValueSeparator) ? json_encode($empleado->nacionalidad->DisplayValueSeparator) : $empleado->nacionalidad->DisplayValueSeparator) ?>" id="x_nacionalidad" name="x_nacionalidad"<?php echo $empleado->nacionalidad->EditAttributes() ?>>
<?php
if (is_array($empleado->nacionalidad->EditValue)) {
	$arwrk = $empleado->nacionalidad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($empleado->nacionalidad->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $empleado->nacionalidad->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($empleado->nacionalidad->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($empleado->nacionalidad->CurrentValue) ?>" selected><?php echo $empleado->nacionalidad->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `nacionalidad_id`, `nacionalidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `nacionalidad`";
$sWhereWrk = "";
$empleado->nacionalidad->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$empleado->nacionalidad->LookupFilters += array("f0" => "`nacionalidad_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$empleado->Lookup_Selecting($empleado->nacionalidad, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $empleado->nacionalidad->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_nacionalidad" id="s_x_nacionalidad" value="<?php echo $empleado->nacionalidad->LookupFilterQuery() ?>">
</span>
<?php echo $empleado->nacionalidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->estado_civil->Visible) { // estado_civil ?>
	<div id="r_estado_civil" class="form-group">
		<label id="elh_empleado_estado_civil" class="col-sm-2 control-label ewLabel"><?php echo $empleado->estado_civil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->estado_civil->CellAttributes() ?>>
<span id="el_empleado_estado_civil">
<?php
$wrkonchange = trim(" " . @$empleado->estado_civil->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$empleado->estado_civil->EditAttrs["onchange"] = "";
?>
<span id="as_x_estado_civil" style="white-space: nowrap; z-index: 8870">
	<input type="text" name="sv_x_estado_civil" id="sv_x_estado_civil" value="<?php echo $empleado->estado_civil->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($empleado->estado_civil->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($empleado->estado_civil->getPlaceHolder()) ?>"<?php echo $empleado->estado_civil->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empleado" data-field="x_estado_civil" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->estado_civil->DisplayValueSeparator) ? json_encode($empleado->estado_civil->DisplayValueSeparator) : $empleado->estado_civil->DisplayValueSeparator) ?>" name="x_estado_civil" id="x_estado_civil" value="<?php echo ew_HtmlEncode($empleado->estado_civil->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `estado_civil_id`, `estado_civil` AS `DispFld` FROM `estado_civil`";
$sWhereWrk = "`estado_civil` LIKE '{query_value}%'";
$empleado->Lookup_Selecting($empleado->estado_civil, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_estado_civil" id="q_x_estado_civil" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fempleadoedit.CreateAutoSuggest({"id":"x_estado_civil","forceSelect":false});
</script>
</span>
<?php echo $empleado->estado_civil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->sexo->Visible) { // sexo ?>
	<div id="r_sexo" class="form-group">
		<label id="elh_empleado_sexo" class="col-sm-2 control-label ewLabel"><?php echo $empleado->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->sexo->CellAttributes() ?>>
<span id="el_empleado_sexo">
<?php
$wrkonchange = trim(" " . @$empleado->sexo->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$empleado->sexo->EditAttrs["onchange"] = "";
?>
<span id="as_x_sexo" style="white-space: nowrap; z-index: 8860">
	<input type="text" name="sv_x_sexo" id="sv_x_sexo" value="<?php echo $empleado->sexo->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($empleado->sexo->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($empleado->sexo->getPlaceHolder()) ?>"<?php echo $empleado->sexo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empleado" data-field="x_sexo" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->sexo->DisplayValueSeparator) ? json_encode($empleado->sexo->DisplayValueSeparator) : $empleado->sexo->DisplayValueSeparator) ?>" name="x_sexo" id="x_sexo" value="<?php echo ew_HtmlEncode($empleado->sexo->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `sexo_id`, `sexo` AS `DispFld` FROM `sexo`";
$sWhereWrk = "`sexo` LIKE '{query_value}%'";
$empleado->Lookup_Selecting($empleado->sexo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_sexo" id="q_x_sexo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fempleadoedit.CreateAutoSuggest({"id":"x_sexo","forceSelect":false});
</script>
</span>
<?php echo $empleado->sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->igss->Visible) { // igss ?>
	<div id="r_igss" class="form-group">
		<label id="elh_empleado_igss" for="x_igss" class="col-sm-2 control-label ewLabel"><?php echo $empleado->igss->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->igss->CellAttributes() ?>>
<span id="el_empleado_igss">
<input type="text" data-table="empleado" data-field="x_igss" name="x_igss" id="x_igss" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($empleado->igss->getPlaceHolder()) ?>" value="<?php echo $empleado->igss->EditValue ?>"<?php echo $empleado->igss->EditAttributes() ?>>
</span>
<?php echo $empleado->igss->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->nit->Visible) { // nit ?>
	<div id="r_nit" class="form-group">
		<label id="elh_empleado_nit" for="x_nit" class="col-sm-2 control-label ewLabel"><?php echo $empleado->nit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->nit->CellAttributes() ?>>
<span id="el_empleado_nit">
<input type="text" data-table="empleado" data-field="x_nit" name="x_nit" id="x_nit" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($empleado->nit->getPlaceHolder()) ?>" value="<?php echo $empleado->nit->EditValue ?>"<?php echo $empleado->nit->EditAttributes() ?>>
</span>
<?php echo $empleado->nit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->licencia_conducir->Visible) { // licencia_conducir ?>
	<div id="r_licencia_conducir" class="form-group">
		<label id="elh_empleado_licencia_conducir" for="x_licencia_conducir" class="col-sm-2 control-label ewLabel"><?php echo $empleado->licencia_conducir->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->licencia_conducir->CellAttributes() ?>>
<span id="el_empleado_licencia_conducir">
<input type="text" data-table="empleado" data-field="x_licencia_conducir" name="x_licencia_conducir" id="x_licencia_conducir" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($empleado->licencia_conducir->getPlaceHolder()) ?>" value="<?php echo $empleado->licencia_conducir->EditValue ?>"<?php echo $empleado->licencia_conducir->EditAttributes() ?>>
</span>
<?php echo $empleado->licencia_conducir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->area_id->Visible) { // area_id ?>
	<div id="r_area_id" class="form-group">
		<label id="elh_empleado_area_id" class="col-sm-2 control-label ewLabel"><?php echo $empleado->area_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->area_id->CellAttributes() ?>>
<span id="el_empleado_area_id">
<?php
$wrkonchange = trim(" " . @$empleado->area_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$empleado->area_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_area_id" style="white-space: nowrap; z-index: 8820">
	<input type="text" name="sv_x_area_id" id="sv_x_area_id" value="<?php echo $empleado->area_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($empleado->area_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($empleado->area_id->getPlaceHolder()) ?>"<?php echo $empleado->area_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empleado" data-field="x_area_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->area_id->DisplayValueSeparator) ? json_encode($empleado->area_id->DisplayValueSeparator) : $empleado->area_id->DisplayValueSeparator) ?>" name="x_area_id" id="x_area_id" value="<?php echo ew_HtmlEncode($empleado->area_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `area_id`, `nombre` AS `DispFld` FROM `area`";
$sWhereWrk = "`nombre` LIKE '{query_value}%'";
$empleado->Lookup_Selecting($empleado->area_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_area_id" id="q_x_area_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fempleadoedit.CreateAutoSuggest({"id":"x_area_id","forceSelect":false});
</script>
</span>
<?php echo $empleado->area_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->departmento_id->Visible) { // departmento_id ?>
	<div id="r_departmento_id" class="form-group">
		<label id="elh_empleado_departmento_id" for="x_departmento_id" class="col-sm-2 control-label ewLabel"><?php echo $empleado->departmento_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->departmento_id->CellAttributes() ?>>
<span id="el_empleado_departmento_id">
<?php $empleado->departmento_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$empleado->departmento_id->EditAttrs["onchange"]; ?>
<select data-table="empleado" data-field="x_departmento_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->departmento_id->DisplayValueSeparator) ? json_encode($empleado->departmento_id->DisplayValueSeparator) : $empleado->departmento_id->DisplayValueSeparator) ?>" id="x_departmento_id" name="x_departmento_id"<?php echo $empleado->departmento_id->EditAttributes() ?>>
<?php
if (is_array($empleado->departmento_id->EditValue)) {
	$arwrk = $empleado->departmento_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($empleado->departmento_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $empleado->departmento_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($empleado->departmento_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($empleado->departmento_id->CurrentValue) ?>" selected><?php echo $empleado->departmento_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `departamento_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
$sWhereWrk = "{filter}";
$empleado->departmento_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$empleado->departmento_id->LookupFilters += array("f0" => "`departamento_id` = {filter_value}", "t0" => "3", "fn0" => "");
$empleado->departmento_id->LookupFilters += array("f1" => "`departamento_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$empleado->Lookup_Selecting($empleado->departmento_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $empleado->departmento_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_departmento_id" id="s_x_departmento_id" value="<?php echo $empleado->departmento_id->LookupFilterQuery() ?>">
</span>
<?php echo $empleado->departmento_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->seccion_id->Visible) { // seccion_id ?>
	<div id="r_seccion_id" class="form-group">
		<label id="elh_empleado_seccion_id" for="x_seccion_id" class="col-sm-2 control-label ewLabel"><?php echo $empleado->seccion_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->seccion_id->CellAttributes() ?>>
<span id="el_empleado_seccion_id">
<?php $empleado->seccion_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$empleado->seccion_id->EditAttrs["onchange"]; ?>
<select data-table="empleado" data-field="x_seccion_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->seccion_id->DisplayValueSeparator) ? json_encode($empleado->seccion_id->DisplayValueSeparator) : $empleado->seccion_id->DisplayValueSeparator) ?>" id="x_seccion_id" name="x_seccion_id"<?php echo $empleado->seccion_id->EditAttributes() ?>>
<?php
if (is_array($empleado->seccion_id->EditValue)) {
	$arwrk = $empleado->seccion_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($empleado->seccion_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $empleado->seccion_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($empleado->seccion_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($empleado->seccion_id->CurrentValue) ?>" selected><?php echo $empleado->seccion_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `seccion_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `seccion`";
$sWhereWrk = "{filter}";
$empleado->seccion_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$empleado->seccion_id->LookupFilters += array("f0" => "`seccion_id` = {filter_value}", "t0" => "3", "fn0" => "");
$empleado->seccion_id->LookupFilters += array("f1" => "`seccion_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$empleado->Lookup_Selecting($empleado->seccion_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $empleado->seccion_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_seccion_id" id="s_x_seccion_id" value="<?php echo $empleado->seccion_id->LookupFilterQuery() ?>">
</span>
<?php echo $empleado->seccion_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->puesto_id->Visible) { // puesto_id ?>
	<div id="r_puesto_id" class="form-group">
		<label id="elh_empleado_puesto_id" for="x_puesto_id" class="col-sm-2 control-label ewLabel"><?php echo $empleado->puesto_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->puesto_id->CellAttributes() ?>>
<span id="el_empleado_puesto_id">
<?php $empleado->puesto_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$empleado->puesto_id->EditAttrs["onchange"]; ?>
<select data-table="empleado" data-field="x_puesto_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->puesto_id->DisplayValueSeparator) ? json_encode($empleado->puesto_id->DisplayValueSeparator) : $empleado->puesto_id->DisplayValueSeparator) ?>" id="x_puesto_id" name="x_puesto_id"<?php echo $empleado->puesto_id->EditAttributes() ?>>
<?php
if (is_array($empleado->puesto_id->EditValue)) {
	$arwrk = $empleado->puesto_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($empleado->puesto_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $empleado->puesto_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($empleado->puesto_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($empleado->puesto_id->CurrentValue) ?>" selected><?php echo $empleado->puesto_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `puesto_id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `puesto`";
$sWhereWrk = "{filter}";
$empleado->puesto_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$empleado->puesto_id->LookupFilters += array("f0" => "`puesto_id` = {filter_value}", "t0" => "3", "fn0" => "");
$empleado->puesto_id->LookupFilters += array("f1" => "`puesto_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$empleado->Lookup_Selecting($empleado->puesto_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $empleado->puesto_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_puesto_id" id="s_x_puesto_id" value="<?php echo $empleado->puesto_id->LookupFilterQuery() ?>">
</span>
<?php echo $empleado->puesto_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_empleado_observaciones" for="x_observaciones" class="col-sm-2 control-label ewLabel"><?php echo $empleado->observaciones->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->observaciones->CellAttributes() ?>>
<span id="el_empleado_observaciones">
<textarea data-table="empleado" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($empleado->observaciones->getPlaceHolder()) ?>"<?php echo $empleado->observaciones->EditAttributes() ?>><?php echo $empleado->observaciones->EditValue ?></textarea>
</span>
<?php echo $empleado->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->tipo_sangre_id->Visible) { // tipo_sangre_id ?>
	<div id="r_tipo_sangre_id" class="form-group">
		<label id="elh_empleado_tipo_sangre_id" for="x_tipo_sangre_id" class="col-sm-2 control-label ewLabel"><?php echo $empleado->tipo_sangre_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->tipo_sangre_id->CellAttributes() ?>>
<span id="el_empleado_tipo_sangre_id">
<select data-table="empleado" data-field="x_tipo_sangre_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($empleado->tipo_sangre_id->DisplayValueSeparator) ? json_encode($empleado->tipo_sangre_id->DisplayValueSeparator) : $empleado->tipo_sangre_id->DisplayValueSeparator) ?>" id="x_tipo_sangre_id" name="x_tipo_sangre_id"<?php echo $empleado->tipo_sangre_id->EditAttributes() ?>>
<?php
if (is_array($empleado->tipo_sangre_id->EditValue)) {
	$arwrk = $empleado->tipo_sangre_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($empleado->tipo_sangre_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $empleado->tipo_sangre_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($empleado->tipo_sangre_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($empleado->tipo_sangre_id->CurrentValue) ?>" selected><?php echo $empleado->tipo_sangre_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `tipo_sangre_id`, `tipo_sangre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_sangre`";
$sWhereWrk = "";
$empleado->tipo_sangre_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$empleado->tipo_sangre_id->LookupFilters += array("f0" => "`tipo_sangre_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$empleado->Lookup_Selecting($empleado->tipo_sangre_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $empleado->tipo_sangre_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_tipo_sangre_id" id="s_x_tipo_sangre_id" value="<?php echo $empleado->tipo_sangre_id->LookupFilterQuery() ?>">
</span>
<?php echo $empleado->tipo_sangre_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleado->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_empleado_estado" for="x_estado" class="col-sm-2 control-label ewLabel"><?php echo $empleado->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empleado->estado->CellAttributes() ?>>
<span id="el_empleado_estado">
<input type="text" data-table="empleado" data-field="x_estado" name="x_estado" id="x_estado" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->estado->getPlaceHolder()) ?>" value="<?php echo $empleado->estado->EditValue ?>"<?php echo $empleado->estado->EditAttributes() ?>>
</span>
<?php echo $empleado->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="empleado" data-field="x_empleado_id" name="x_empleado_id" id="x_empleado_id" value="<?php echo ew_HtmlEncode($empleado->empleado_id->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $empleado_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fempleadoedit.Init();
</script>
<?php
$empleado_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empleado_edit->Page_Terminate();
?>
