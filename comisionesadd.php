<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "comisionesinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$comisiones_add = NULL; // Initialize page object first

class ccomisiones_add extends ccomisiones {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'comisiones';

	// Page object name
	var $PageObjName = 'comisiones_add';

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

		// Table object (comisiones)
		if (!isset($GLOBALS["comisiones"]) || get_class($GLOBALS["comisiones"]) == "ccomisiones") {
			$GLOBALS["comisiones"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["comisiones"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'comisiones', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("comisioneslist.php"));
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
		global $EW_EXPORT, $comisiones;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($comisiones);
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
			if (@$_GET["idcomisiones"] != "") {
				$this->idcomisiones->setQueryStringValue($_GET["idcomisiones"]);
				$this->setKey("idcomisiones", $this->idcomisiones->CurrentValue); // Set up key
			} else {
				$this->setKey("idcomisiones", ""); // Clear key
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
					$this->Page_Terminate("comisioneslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "comisioneslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "comisionesview.php")
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
		$this->inicio->CurrentValue = NULL;
		$this->inicio->OldValue = $this->inicio->CurrentValue;
		$this->fin->CurrentValue = NULL;
		$this->fin->OldValue = $this->fin->CurrentValue;
		$this->observaciones->CurrentValue = NULL;
		$this->observaciones->OldValue = $this->observaciones->CurrentValue;
		$this->empleado_id->CurrentValue = NULL;
		$this->empleado_id->OldValue = $this->empleado_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->inicio->FldIsDetailKey) {
			$this->inicio->setFormValue($objForm->GetValue("x_inicio"));
			$this->inicio->CurrentValue = ew_UnFormatDateTime($this->inicio->CurrentValue, 11);
		}
		if (!$this->fin->FldIsDetailKey) {
			$this->fin->setFormValue($objForm->GetValue("x_fin"));
			$this->fin->CurrentValue = ew_UnFormatDateTime($this->fin->CurrentValue, 11);
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
		if (!$this->empleado_id->FldIsDetailKey) {
			$this->empleado_id->setFormValue($objForm->GetValue("x_empleado_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->inicio->CurrentValue = $this->inicio->FormValue;
		$this->inicio->CurrentValue = ew_UnFormatDateTime($this->inicio->CurrentValue, 11);
		$this->fin->CurrentValue = $this->fin->FormValue;
		$this->fin->CurrentValue = ew_UnFormatDateTime($this->fin->CurrentValue, 11);
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
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
		$this->idcomisiones->setDbValue($rs->fields('idcomisiones'));
		$this->inicio->setDbValue($rs->fields('inicio'));
		$this->fin->setDbValue($rs->fields('fin'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->empleado_id->setDbValue($rs->fields('empleado_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idcomisiones->DbValue = $row['idcomisiones'];
		$this->inicio->DbValue = $row['inicio'];
		$this->fin->DbValue = $row['fin'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->empleado_id->DbValue = $row['empleado_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idcomisiones")) <> "")
			$this->idcomisiones->CurrentValue = $this->getKey("idcomisiones"); // idcomisiones
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idcomisiones
		// inicio
		// fin
		// observaciones
		// empleado_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// inicio
		$this->inicio->ViewValue = $this->inicio->CurrentValue;
		$this->inicio->ViewValue = ew_FormatDateTime($this->inicio->ViewValue, 11);
		$this->inicio->ViewCustomAttributes = "";

		// fin
		$this->fin->ViewValue = $this->fin->CurrentValue;
		$this->fin->ViewValue = ew_FormatDateTime($this->fin->ViewValue, 11);
		$this->fin->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

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

			// inicio
			$this->inicio->LinkCustomAttributes = "";
			$this->inicio->HrefValue = "";
			$this->inicio->TooltipValue = "";

			// fin
			$this->fin->LinkCustomAttributes = "";
			$this->fin->HrefValue = "";
			$this->fin->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// empleado_id
			$this->empleado_id->LinkCustomAttributes = "";
			$this->empleado_id->HrefValue = "";
			$this->empleado_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// inicio
			$this->inicio->EditAttrs["class"] = "form-control";
			$this->inicio->EditCustomAttributes = "";
			$this->inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->inicio->CurrentValue, 11));
			$this->inicio->PlaceHolder = ew_RemoveHtml($this->inicio->FldCaption());

			// fin
			$this->fin->EditAttrs["class"] = "form-control";
			$this->fin->EditCustomAttributes = "";
			$this->fin->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fin->CurrentValue, 11));
			$this->fin->PlaceHolder = ew_RemoveHtml($this->fin->FldCaption());

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

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
			// inicio

			$this->inicio->LinkCustomAttributes = "";
			$this->inicio->HrefValue = "";

			// fin
			$this->fin->LinkCustomAttributes = "";
			$this->fin->HrefValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";

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
		if (!$this->inicio->FldIsDetailKey && !is_null($this->inicio->FormValue) && $this->inicio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->inicio->FldCaption(), $this->inicio->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->inicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->inicio->FldErrMsg());
		}
		if (!$this->fin->FldIsDetailKey && !is_null($this->fin->FormValue) && $this->fin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fin->FldCaption(), $this->fin->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fin->FormValue)) {
			ew_AddMessage($gsFormError, $this->fin->FldErrMsg());
		}
		if (!$this->observaciones->FldIsDetailKey && !is_null($this->observaciones->FormValue) && $this->observaciones->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->observaciones->FldCaption(), $this->observaciones->ReqErrMsg));
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

		// inicio
		$this->inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->inicio->CurrentValue, 11), ew_CurrentDate(), FALSE);

		// fin
		$this->fin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fin->CurrentValue, 11), ew_CurrentDate(), FALSE);

		// observaciones
		$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, "", FALSE);

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
				$this->idcomisiones->setDbValue($conn->Insert_ID());
				$rsnew['idcomisiones'] = $this->idcomisiones->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("comisioneslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($comisiones_add)) $comisiones_add = new ccomisiones_add();

// Page init
$comisiones_add->Page_Init();

// Page main
$comisiones_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$comisiones_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcomisionesadd = new ew_Form("fcomisionesadd", "add");

// Validate form
fcomisionesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_inicio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comisiones->inicio->FldCaption(), $comisiones->inicio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comisiones->inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comisiones->fin->FldCaption(), $comisiones->fin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comisiones->fin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_observaciones");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comisiones->observaciones->FldCaption(), $comisiones->observaciones->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_empleado_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comisiones->empleado_id->FldCaption(), $comisiones->empleado_id->ReqErrMsg)) ?>");

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
fcomisionesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcomisionesadd.ValidateRequired = true;
<?php } else { ?>
fcomisionesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcomisionesadd.Lists["x_empleado_id"] = {"LinkField":"x_empleado_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $comisiones_add->ShowPageHeader(); ?>
<?php
$comisiones_add->ShowMessage();
?>
<form name="fcomisionesadd" id="fcomisionesadd" class="<?php echo $comisiones_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($comisiones_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $comisiones_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="comisiones">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($comisiones->inicio->Visible) { // inicio ?>
	<div id="r_inicio" class="form-group">
		<label id="elh_comisiones_inicio" for="x_inicio" class="col-sm-2 control-label ewLabel"><?php echo $comisiones->inicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $comisiones->inicio->CellAttributes() ?>>
<span id="el_comisiones_inicio">
<input type="text" data-table="comisiones" data-field="x_inicio" data-format="11" name="x_inicio" id="x_inicio" placeholder="<?php echo ew_HtmlEncode($comisiones->inicio->getPlaceHolder()) ?>" value="<?php echo $comisiones->inicio->EditValue ?>"<?php echo $comisiones->inicio->EditAttributes() ?>>
<?php if (!$comisiones->inicio->ReadOnly && !$comisiones->inicio->Disabled && !isset($comisiones->inicio->EditAttrs["readonly"]) && !isset($comisiones->inicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcomisionesadd", "x_inicio", "%d/%m/%Y %H:%M:%S");
</script>
<?php } ?>
</span>
<?php echo $comisiones->inicio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comisiones->fin->Visible) { // fin ?>
	<div id="r_fin" class="form-group">
		<label id="elh_comisiones_fin" for="x_fin" class="col-sm-2 control-label ewLabel"><?php echo $comisiones->fin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $comisiones->fin->CellAttributes() ?>>
<span id="el_comisiones_fin">
<input type="text" data-table="comisiones" data-field="x_fin" data-format="11" name="x_fin" id="x_fin" placeholder="<?php echo ew_HtmlEncode($comisiones->fin->getPlaceHolder()) ?>" value="<?php echo $comisiones->fin->EditValue ?>"<?php echo $comisiones->fin->EditAttributes() ?>>
<?php if (!$comisiones->fin->ReadOnly && !$comisiones->fin->Disabled && !isset($comisiones->fin->EditAttrs["readonly"]) && !isset($comisiones->fin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcomisionesadd", "x_fin", "%d/%m/%Y %H:%M:%S");
</script>
<?php } ?>
</span>
<?php echo $comisiones->fin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comisiones->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_comisiones_observaciones" for="x_observaciones" class="col-sm-2 control-label ewLabel"><?php echo $comisiones->observaciones->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $comisiones->observaciones->CellAttributes() ?>>
<span id="el_comisiones_observaciones">
<input type="text" data-table="comisiones" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($comisiones->observaciones->getPlaceHolder()) ?>" value="<?php echo $comisiones->observaciones->EditValue ?>"<?php echo $comisiones->observaciones->EditAttributes() ?>>
</span>
<?php echo $comisiones->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comisiones->empleado_id->Visible) { // empleado_id ?>
	<div id="r_empleado_id" class="form-group">
		<label id="elh_comisiones_empleado_id" for="x_empleado_id" class="col-sm-2 control-label ewLabel"><?php echo $comisiones->empleado_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $comisiones->empleado_id->CellAttributes() ?>>
<span id="el_comisiones_empleado_id">
<select data-table="comisiones" data-field="x_empleado_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($comisiones->empleado_id->DisplayValueSeparator) ? json_encode($comisiones->empleado_id->DisplayValueSeparator) : $comisiones->empleado_id->DisplayValueSeparator) ?>" id="x_empleado_id" name="x_empleado_id"<?php echo $comisiones->empleado_id->EditAttributes() ?>>
<?php
if (is_array($comisiones->empleado_id->EditValue)) {
	$arwrk = $comisiones->empleado_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($comisiones->empleado_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $comisiones->empleado_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($comisiones->empleado_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($comisiones->empleado_id->CurrentValue) ?>" selected><?php echo $comisiones->empleado_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `empleado_id`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
$sWhereWrk = "";
$comisiones->empleado_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$comisiones->empleado_id->LookupFilters += array("f0" => "`empleado_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$comisiones->Lookup_Selecting($comisiones->empleado_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $comisiones->empleado_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_empleado_id" id="s_x_empleado_id" value="<?php echo $comisiones->empleado_id->LookupFilterQuery() ?>">
</span>
<?php echo $comisiones->empleado_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $comisiones_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcomisionesadd.Init();
</script>
<?php
$comisiones_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$comisiones_add->Page_Terminate();
?>
