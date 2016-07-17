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

$empleado_view = NULL; // Initialize page object first

class cempleado_view extends cempleado {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'empleado';

	// Page object name
	var $PageObjName = 'empleado_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["empleado_id"] <> "") {
			$this->RecKey["empleado_id"] = $_GET["empleado_id"];
			$KeyUrl .= "&amp;empleado_id=" . urlencode($this->RecKey["empleado_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["empleado_id"] <> "") {
				$this->empleado_id->setQueryStringValue($_GET["empleado_id"]);
				$this->RecKey["empleado_id"] = $this->empleado_id->QueryStringValue;
			} elseif (@$_POST["empleado_id"] <> "") {
				$this->empleado_id->setFormValue($_POST["empleado_id"]);
				$this->RecKey["empleado_id"] = $this->empleado_id->FormValue;
			} else {
				$sReturnUrl = "empleadolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "empleadolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "empleadolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("empleadolist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($empleado_view)) $empleado_view = new cempleado_view();

// Page init
$empleado_view->Page_Init();

// Page main
$empleado_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empleado_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fempleadoview = new ew_Form("fempleadoview", "view");

// Form_CustomValidate event
fempleadoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadoview.ValidateRequired = true;
<?php } else { ?>
fempleadoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempleadoview.Lists["x_departamento_origen_id"] = {"LinkField":"x_departamento_origen_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_municipio_id"] = {"LinkField":"x_municipio_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_nacionalidad"] = {"LinkField":"x_nacionalidad_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nacionalidad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_estado_civil"] = {"LinkField":"x_estado_civil_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_estado_civil","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_sexo"] = {"LinkField":"x_sexo_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sexo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_area_id"] = {"LinkField":"x_area_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_departmento_id"] = {"LinkField":"x_departamento_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":["x_departmento_id"],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_seccion_id"] = {"LinkField":"x_seccion_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":["x_seccion_id"],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_puesto_id"] = {"LinkField":"x_puesto_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":["x_puesto_id"],"FilterFields":[],"Options":[],"Template":""};
fempleadoview.Lists["x_tipo_sangre_id"] = {"LinkField":"x_tipo_sangre_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_sangre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $empleado_view->ExportOptions->Render("body") ?>
<?php
	foreach ($empleado_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $empleado_view->ShowPageHeader(); ?>
<?php
$empleado_view->ShowMessage();
?>
<form name="fempleadoview" id="fempleadoview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empleado_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empleado_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empleado">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($empleado->codigo->Visible) { // codigo ?>
	<tr id="r_codigo">
		<td><span id="elh_empleado_codigo"><?php echo $empleado->codigo->FldCaption() ?></span></td>
		<td data-name="codigo"<?php echo $empleado->codigo->CellAttributes() ?>>
<span id="el_empleado_codigo">
<span<?php echo $empleado->codigo->ViewAttributes() ?>>
<?php echo $empleado->codigo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->cui->Visible) { // cui ?>
	<tr id="r_cui">
		<td><span id="elh_empleado_cui"><?php echo $empleado->cui->FldCaption() ?></span></td>
		<td data-name="cui"<?php echo $empleado->cui->CellAttributes() ?>>
<span id="el_empleado_cui">
<span<?php echo $empleado->cui->ViewAttributes() ?>>
<?php echo $empleado->cui->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_empleado_nombre"><?php echo $empleado->nombre->FldCaption() ?></span></td>
		<td data-name="nombre"<?php echo $empleado->nombre->CellAttributes() ?>>
<span id="el_empleado_nombre">
<span<?php echo $empleado->nombre->ViewAttributes() ?>>
<?php echo $empleado->nombre->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->apellido->Visible) { // apellido ?>
	<tr id="r_apellido">
		<td><span id="elh_empleado_apellido"><?php echo $empleado->apellido->FldCaption() ?></span></td>
		<td data-name="apellido"<?php echo $empleado->apellido->CellAttributes() ?>>
<span id="el_empleado_apellido">
<span<?php echo $empleado->apellido->ViewAttributes() ?>>
<?php echo $empleado->apellido->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->direccion->Visible) { // direccion ?>
	<tr id="r_direccion">
		<td><span id="elh_empleado_direccion"><?php echo $empleado->direccion->FldCaption() ?></span></td>
		<td data-name="direccion"<?php echo $empleado->direccion->CellAttributes() ?>>
<span id="el_empleado_direccion">
<span<?php echo $empleado->direccion->ViewAttributes() ?>>
<?php echo $empleado->direccion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->departamento_origen_id->Visible) { // departamento_origen_id ?>
	<tr id="r_departamento_origen_id">
		<td><span id="elh_empleado_departamento_origen_id"><?php echo $empleado->departamento_origen_id->FldCaption() ?></span></td>
		<td data-name="departamento_origen_id"<?php echo $empleado->departamento_origen_id->CellAttributes() ?>>
<span id="el_empleado_departamento_origen_id">
<span<?php echo $empleado->departamento_origen_id->ViewAttributes() ?>>
<?php echo $empleado->departamento_origen_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->municipio_id->Visible) { // municipio_id ?>
	<tr id="r_municipio_id">
		<td><span id="elh_empleado_municipio_id"><?php echo $empleado->municipio_id->FldCaption() ?></span></td>
		<td data-name="municipio_id"<?php echo $empleado->municipio_id->CellAttributes() ?>>
<span id="el_empleado_municipio_id">
<span<?php echo $empleado->municipio_id->ViewAttributes() ?>>
<?php echo $empleado->municipio_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->telefono_residencia->Visible) { // telefono_residencia ?>
	<tr id="r_telefono_residencia">
		<td><span id="elh_empleado_telefono_residencia"><?php echo $empleado->telefono_residencia->FldCaption() ?></span></td>
		<td data-name="telefono_residencia"<?php echo $empleado->telefono_residencia->CellAttributes() ?>>
<span id="el_empleado_telefono_residencia">
<span<?php echo $empleado->telefono_residencia->ViewAttributes() ?>>
<?php echo $empleado->telefono_residencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->telefono_celular->Visible) { // telefono_celular ?>
	<tr id="r_telefono_celular">
		<td><span id="elh_empleado_telefono_celular"><?php echo $empleado->telefono_celular->FldCaption() ?></span></td>
		<td data-name="telefono_celular"<?php echo $empleado->telefono_celular->CellAttributes() ?>>
<span id="el_empleado_telefono_celular">
<span<?php echo $empleado->telefono_celular->ViewAttributes() ?>>
<?php echo $empleado->telefono_celular->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<tr id="r_fecha_nacimiento">
		<td><span id="elh_empleado_fecha_nacimiento"><?php echo $empleado->fecha_nacimiento->FldCaption() ?></span></td>
		<td data-name="fecha_nacimiento"<?php echo $empleado->fecha_nacimiento->CellAttributes() ?>>
<span id="el_empleado_fecha_nacimiento">
<span<?php echo $empleado->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $empleado->fecha_nacimiento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->nacionalidad->Visible) { // nacionalidad ?>
	<tr id="r_nacionalidad">
		<td><span id="elh_empleado_nacionalidad"><?php echo $empleado->nacionalidad->FldCaption() ?></span></td>
		<td data-name="nacionalidad"<?php echo $empleado->nacionalidad->CellAttributes() ?>>
<span id="el_empleado_nacionalidad">
<span<?php echo $empleado->nacionalidad->ViewAttributes() ?>>
<?php echo $empleado->nacionalidad->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->estado_civil->Visible) { // estado_civil ?>
	<tr id="r_estado_civil">
		<td><span id="elh_empleado_estado_civil"><?php echo $empleado->estado_civil->FldCaption() ?></span></td>
		<td data-name="estado_civil"<?php echo $empleado->estado_civil->CellAttributes() ?>>
<span id="el_empleado_estado_civil">
<span<?php echo $empleado->estado_civil->ViewAttributes() ?>>
<?php echo $empleado->estado_civil->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->sexo->Visible) { // sexo ?>
	<tr id="r_sexo">
		<td><span id="elh_empleado_sexo"><?php echo $empleado->sexo->FldCaption() ?></span></td>
		<td data-name="sexo"<?php echo $empleado->sexo->CellAttributes() ?>>
<span id="el_empleado_sexo">
<span<?php echo $empleado->sexo->ViewAttributes() ?>>
<?php echo $empleado->sexo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->igss->Visible) { // igss ?>
	<tr id="r_igss">
		<td><span id="elh_empleado_igss"><?php echo $empleado->igss->FldCaption() ?></span></td>
		<td data-name="igss"<?php echo $empleado->igss->CellAttributes() ?>>
<span id="el_empleado_igss">
<span<?php echo $empleado->igss->ViewAttributes() ?>>
<?php echo $empleado->igss->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->nit->Visible) { // nit ?>
	<tr id="r_nit">
		<td><span id="elh_empleado_nit"><?php echo $empleado->nit->FldCaption() ?></span></td>
		<td data-name="nit"<?php echo $empleado->nit->CellAttributes() ?>>
<span id="el_empleado_nit">
<span<?php echo $empleado->nit->ViewAttributes() ?>>
<?php echo $empleado->nit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->licencia_conducir->Visible) { // licencia_conducir ?>
	<tr id="r_licencia_conducir">
		<td><span id="elh_empleado_licencia_conducir"><?php echo $empleado->licencia_conducir->FldCaption() ?></span></td>
		<td data-name="licencia_conducir"<?php echo $empleado->licencia_conducir->CellAttributes() ?>>
<span id="el_empleado_licencia_conducir">
<span<?php echo $empleado->licencia_conducir->ViewAttributes() ?>>
<?php echo $empleado->licencia_conducir->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->area_id->Visible) { // area_id ?>
	<tr id="r_area_id">
		<td><span id="elh_empleado_area_id"><?php echo $empleado->area_id->FldCaption() ?></span></td>
		<td data-name="area_id"<?php echo $empleado->area_id->CellAttributes() ?>>
<span id="el_empleado_area_id">
<span<?php echo $empleado->area_id->ViewAttributes() ?>>
<?php echo $empleado->area_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->departmento_id->Visible) { // departmento_id ?>
	<tr id="r_departmento_id">
		<td><span id="elh_empleado_departmento_id"><?php echo $empleado->departmento_id->FldCaption() ?></span></td>
		<td data-name="departmento_id"<?php echo $empleado->departmento_id->CellAttributes() ?>>
<span id="el_empleado_departmento_id">
<span<?php echo $empleado->departmento_id->ViewAttributes() ?>>
<?php echo $empleado->departmento_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->seccion_id->Visible) { // seccion_id ?>
	<tr id="r_seccion_id">
		<td><span id="elh_empleado_seccion_id"><?php echo $empleado->seccion_id->FldCaption() ?></span></td>
		<td data-name="seccion_id"<?php echo $empleado->seccion_id->CellAttributes() ?>>
<span id="el_empleado_seccion_id">
<span<?php echo $empleado->seccion_id->ViewAttributes() ?>>
<?php echo $empleado->seccion_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->puesto_id->Visible) { // puesto_id ?>
	<tr id="r_puesto_id">
		<td><span id="elh_empleado_puesto_id"><?php echo $empleado->puesto_id->FldCaption() ?></span></td>
		<td data-name="puesto_id"<?php echo $empleado->puesto_id->CellAttributes() ?>>
<span id="el_empleado_puesto_id">
<span<?php echo $empleado->puesto_id->ViewAttributes() ?>>
<?php echo $empleado->puesto_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->observaciones->Visible) { // observaciones ?>
	<tr id="r_observaciones">
		<td><span id="elh_empleado_observaciones"><?php echo $empleado->observaciones->FldCaption() ?></span></td>
		<td data-name="observaciones"<?php echo $empleado->observaciones->CellAttributes() ?>>
<span id="el_empleado_observaciones">
<span<?php echo $empleado->observaciones->ViewAttributes() ?>>
<?php echo $empleado->observaciones->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->tipo_sangre_id->Visible) { // tipo_sangre_id ?>
	<tr id="r_tipo_sangre_id">
		<td><span id="elh_empleado_tipo_sangre_id"><?php echo $empleado->tipo_sangre_id->FldCaption() ?></span></td>
		<td data-name="tipo_sangre_id"<?php echo $empleado->tipo_sangre_id->CellAttributes() ?>>
<span id="el_empleado_tipo_sangre_id">
<span<?php echo $empleado->tipo_sangre_id->ViewAttributes() ?>>
<?php echo $empleado->tipo_sangre_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleado->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_empleado_estado"><?php echo $empleado->estado->FldCaption() ?></span></td>
		<td data-name="estado"<?php echo $empleado->estado->CellAttributes() ?>>
<span id="el_empleado_estado">
<span<?php echo $empleado->estado->ViewAttributes() ?>>
<?php echo $empleado->estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fempleadoview.Init();
</script>
<?php
$empleado_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empleado_view->Page_Terminate();
?>
