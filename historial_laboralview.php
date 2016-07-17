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

$historial_laboral_view = NULL; // Initialize page object first

class chistorial_laboral_view extends chistorial_laboral {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'historial_laboral';

	// Page object name
	var $PageObjName = 'historial_laboral_view';

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

		// Table object (historial_laboral)
		if (!isset($GLOBALS["historial_laboral"]) || get_class($GLOBALS["historial_laboral"]) == "chistorial_laboral") {
			$GLOBALS["historial_laboral"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["historial_laboral"];
		}
		$KeyUrl = "";
		if (@$_GET["historial_laboral_id"] <> "") {
			$this->RecKey["historial_laboral_id"] = $_GET["historial_laboral_id"];
			$KeyUrl .= "&amp;historial_laboral_id=" . urlencode($this->RecKey["historial_laboral_id"]);
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
			if (@$_GET["historial_laboral_id"] <> "") {
				$this->historial_laboral_id->setQueryStringValue($_GET["historial_laboral_id"]);
				$this->RecKey["historial_laboral_id"] = $this->historial_laboral_id->QueryStringValue;
			} elseif (@$_POST["historial_laboral_id"] <> "") {
				$this->historial_laboral_id->setFormValue($_POST["historial_laboral_id"]);
				$this->RecKey["historial_laboral_id"] = $this->historial_laboral_id->FormValue;
			} else {
				$sReturnUrl = "historial_laborallist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "historial_laborallist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "historial_laborallist.php"; // Not page request, return to list
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("historial_laborallist.php"), "", $this->TableVar, TRUE);
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
if (!isset($historial_laboral_view)) $historial_laboral_view = new chistorial_laboral_view();

// Page init
$historial_laboral_view->Page_Init();

// Page main
$historial_laboral_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$historial_laboral_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fhistorial_laboralview = new ew_Form("fhistorial_laboralview", "view");

// Form_CustomValidate event
fhistorial_laboralview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhistorial_laboralview.ValidateRequired = true;
<?php } else { ?>
fhistorial_laboralview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhistorial_laboralview.Lists["x_empleado_id"] = {"LinkField":"x_empleado_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $historial_laboral_view->ExportOptions->Render("body") ?>
<?php
	foreach ($historial_laboral_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $historial_laboral_view->ShowPageHeader(); ?>
<?php
$historial_laboral_view->ShowMessage();
?>
<form name="fhistorial_laboralview" id="fhistorial_laboralview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($historial_laboral_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $historial_laboral_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="historial_laboral">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($historial_laboral->institucion->Visible) { // institucion ?>
	<tr id="r_institucion">
		<td><span id="elh_historial_laboral_institucion"><?php echo $historial_laboral->institucion->FldCaption() ?></span></td>
		<td data-name="institucion"<?php echo $historial_laboral->institucion->CellAttributes() ?>>
<span id="el_historial_laboral_institucion">
<span<?php echo $historial_laboral->institucion->ViewAttributes() ?>>
<?php echo $historial_laboral->institucion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->direccion->Visible) { // direccion ?>
	<tr id="r_direccion">
		<td><span id="elh_historial_laboral_direccion"><?php echo $historial_laboral->direccion->FldCaption() ?></span></td>
		<td data-name="direccion"<?php echo $historial_laboral->direccion->CellAttributes() ?>>
<span id="el_historial_laboral_direccion">
<span<?php echo $historial_laboral->direccion->ViewAttributes() ?>>
<?php echo $historial_laboral->direccion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->telefono->Visible) { // telefono ?>
	<tr id="r_telefono">
		<td><span id="elh_historial_laboral_telefono"><?php echo $historial_laboral->telefono->FldCaption() ?></span></td>
		<td data-name="telefono"<?php echo $historial_laboral->telefono->CellAttributes() ?>>
<span id="el_historial_laboral_telefono">
<span<?php echo $historial_laboral->telefono->ViewAttributes() ?>>
<?php echo $historial_laboral->telefono->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->puesto->Visible) { // puesto ?>
	<tr id="r_puesto">
		<td><span id="elh_historial_laboral_puesto"><?php echo $historial_laboral->puesto->FldCaption() ?></span></td>
		<td data-name="puesto"<?php echo $historial_laboral->puesto->CellAttributes() ?>>
<span id="el_historial_laboral_puesto">
<span<?php echo $historial_laboral->puesto->ViewAttributes() ?>>
<?php echo $historial_laboral->puesto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->atribuciones->Visible) { // atribuciones ?>
	<tr id="r_atribuciones">
		<td><span id="elh_historial_laboral_atribuciones"><?php echo $historial_laboral->atribuciones->FldCaption() ?></span></td>
		<td data-name="atribuciones"<?php echo $historial_laboral->atribuciones->CellAttributes() ?>>
<span id="el_historial_laboral_atribuciones">
<span<?php echo $historial_laboral->atribuciones->ViewAttributes() ?>>
<?php echo $historial_laboral->atribuciones->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->jefe->Visible) { // jefe ?>
	<tr id="r_jefe">
		<td><span id="elh_historial_laboral_jefe"><?php echo $historial_laboral->jefe->FldCaption() ?></span></td>
		<td data-name="jefe"<?php echo $historial_laboral->jefe->CellAttributes() ?>>
<span id="el_historial_laboral_jefe">
<span<?php echo $historial_laboral->jefe->ViewAttributes() ?>>
<?php echo $historial_laboral->jefe->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->fecha_ingreso->Visible) { // fecha_ingreso ?>
	<tr id="r_fecha_ingreso">
		<td><span id="elh_historial_laboral_fecha_ingreso"><?php echo $historial_laboral->fecha_ingreso->FldCaption() ?></span></td>
		<td data-name="fecha_ingreso"<?php echo $historial_laboral->fecha_ingreso->CellAttributes() ?>>
<span id="el_historial_laboral_fecha_ingreso">
<span<?php echo $historial_laboral->fecha_ingreso->ViewAttributes() ?>>
<?php echo $historial_laboral->fecha_ingreso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->fecha_egreso->Visible) { // fecha_egreso ?>
	<tr id="r_fecha_egreso">
		<td><span id="elh_historial_laboral_fecha_egreso"><?php echo $historial_laboral->fecha_egreso->FldCaption() ?></span></td>
		<td data-name="fecha_egreso"<?php echo $historial_laboral->fecha_egreso->CellAttributes() ?>>
<span id="el_historial_laboral_fecha_egreso">
<span<?php echo $historial_laboral->fecha_egreso->ViewAttributes() ?>>
<?php echo $historial_laboral->fecha_egreso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->sueldo_inicial->Visible) { // sueldo_inicial ?>
	<tr id="r_sueldo_inicial">
		<td><span id="elh_historial_laboral_sueldo_inicial"><?php echo $historial_laboral->sueldo_inicial->FldCaption() ?></span></td>
		<td data-name="sueldo_inicial"<?php echo $historial_laboral->sueldo_inicial->CellAttributes() ?>>
<span id="el_historial_laboral_sueldo_inicial">
<span<?php echo $historial_laboral->sueldo_inicial->ViewAttributes() ?>>
<?php echo $historial_laboral->sueldo_inicial->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->sueldo_final->Visible) { // sueldo_final ?>
	<tr id="r_sueldo_final">
		<td><span id="elh_historial_laboral_sueldo_final"><?php echo $historial_laboral->sueldo_final->FldCaption() ?></span></td>
		<td data-name="sueldo_final"<?php echo $historial_laboral->sueldo_final->CellAttributes() ?>>
<span id="el_historial_laboral_sueldo_final">
<span<?php echo $historial_laboral->sueldo_final->ViewAttributes() ?>>
<?php echo $historial_laboral->sueldo_final->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->motivo_retiro->Visible) { // motivo_retiro ?>
	<tr id="r_motivo_retiro">
		<td><span id="elh_historial_laboral_motivo_retiro"><?php echo $historial_laboral->motivo_retiro->FldCaption() ?></span></td>
		<td data-name="motivo_retiro"<?php echo $historial_laboral->motivo_retiro->CellAttributes() ?>>
<span id="el_historial_laboral_motivo_retiro">
<span<?php echo $historial_laboral->motivo_retiro->ViewAttributes() ?>>
<?php echo $historial_laboral->motivo_retiro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($historial_laboral->empleado_id->Visible) { // empleado_id ?>
	<tr id="r_empleado_id">
		<td><span id="elh_historial_laboral_empleado_id"><?php echo $historial_laboral->empleado_id->FldCaption() ?></span></td>
		<td data-name="empleado_id"<?php echo $historial_laboral->empleado_id->CellAttributes() ?>>
<span id="el_historial_laboral_empleado_id">
<span<?php echo $historial_laboral->empleado_id->ViewAttributes() ?>>
<?php echo $historial_laboral->empleado_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fhistorial_laboralview.Init();
</script>
<?php
$historial_laboral_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$historial_laboral_view->Page_Terminate();
?>
