<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$user_add = NULL; // Initialize page object first

class cuser_add extends cuser {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'user';

	// Page object name
	var $PageObjName = 'user_add';

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

		// Table object (user)
		if (!isset($GLOBALS["user"]) || get_class($GLOBALS["user"]) == "cuser") {
			$GLOBALS["user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'user', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("userlist.php"));
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
		global $EW_EXPORT, $user;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($user);
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
			if (@$_GET["user_id"] != "") {
				$this->user_id->setQueryStringValue($_GET["user_id"]);
				$this->setKey("user_id", $this->user_id->CurrentValue); // Set up key
			} else {
				$this->setKey("user_id", ""); // Clear key
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
					$this->Page_Terminate("userlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "userlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "userview.php")
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
		$this->user_name->CurrentValue = NULL;
		$this->user_name->OldValue = $this->user_name->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->surname->CurrentValue = NULL;
		$this->surname->OldValue = $this->surname->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->userlevel_id->CurrentValue = NULL;
		$this->userlevel_id->OldValue = $this->userlevel_id->CurrentValue;
		$this->email_address->CurrentValue = NULL;
		$this->email_address->OldValue = $this->email_address->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->state->CurrentValue = "Activo";
		$this->session->CurrentValue = NULL;
		$this->session->OldValue = $this->session->CurrentValue;
		$this->enterprise_id->CurrentValue = NULL;
		$this->enterprise_id->OldValue = $this->enterprise_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->user_name->FldIsDetailKey) {
			$this->user_name->setFormValue($objForm->GetValue("x_user_name"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->surname->FldIsDetailKey) {
			$this->surname->setFormValue($objForm->GetValue("x_surname"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->userlevel_id->FldIsDetailKey) {
			$this->userlevel_id->setFormValue($objForm->GetValue("x_userlevel_id"));
		}
		if (!$this->email_address->FldIsDetailKey) {
			$this->email_address->setFormValue($objForm->GetValue("x_email_address"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->state->FldIsDetailKey) {
			$this->state->setFormValue($objForm->GetValue("x_state"));
		}
		if (!$this->session->FldIsDetailKey) {
			$this->session->setFormValue($objForm->GetValue("x_session"));
		}
		if (!$this->enterprise_id->FldIsDetailKey) {
			$this->enterprise_id->setFormValue($objForm->GetValue("x_enterprise_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->user_name->CurrentValue = $this->user_name->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->surname->CurrentValue = $this->surname->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->userlevel_id->CurrentValue = $this->userlevel_id->FormValue;
		$this->email_address->CurrentValue = $this->email_address->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->state->CurrentValue = $this->state->FormValue;
		$this->session->CurrentValue = $this->session->FormValue;
		$this->enterprise_id->CurrentValue = $this->enterprise_id->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->user_id->DbValue = $row['user_id'];
		$this->user_name->DbValue = $row['user_name'];
		$this->name->DbValue = $row['name'];
		$this->surname->DbValue = $row['surname'];
		$this->password->DbValue = $row['password'];
		$this->userlevel_id->DbValue = $row['userlevel_id'];
		$this->email_address->DbValue = $row['email_address'];
		$this->description->DbValue = $row['description'];
		$this->state->DbValue = $row['state'];
		$this->session->DbValue = $row['session'];
		$this->enterprise_id->DbValue = $row['enterprise_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("user_id")) <> "")
			$this->user_id->CurrentValue = $this->getKey("user_id"); // user_id
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// user_name
			$this->user_name->EditAttrs["class"] = "form-control";
			$this->user_name->EditCustomAttributes = "";
			$this->user_name->EditValue = ew_HtmlEncode($this->user_name->CurrentValue);
			$this->user_name->PlaceHolder = ew_RemoveHtml($this->user_name->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// surname
			$this->surname->EditAttrs["class"] = "form-control";
			$this->surname->EditCustomAttributes = "";
			$this->surname->EditValue = ew_HtmlEncode($this->surname->CurrentValue);
			$this->surname->PlaceHolder = ew_RemoveHtml($this->surname->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// userlevel_id
			$this->userlevel_id->EditAttrs["class"] = "form-control";
			$this->userlevel_id->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->userlevel_id->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->userlevel_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->userlevel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevel`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->userlevel_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->userlevel_id->EditValue = $arwrk;
			}

			// email_address
			$this->email_address->EditAttrs["class"] = "form-control";
			$this->email_address->EditCustomAttributes = "";
			$this->email_address->EditValue = ew_HtmlEncode($this->email_address->CurrentValue);
			$this->email_address->PlaceHolder = ew_RemoveHtml($this->email_address->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// state
			$this->state->EditCustomAttributes = "";
			$this->state->EditValue = $this->state->Options(FALSE);

			// session
			$this->session->EditAttrs["class"] = "form-control";
			$this->session->EditCustomAttributes = "";
			$this->session->EditValue = ew_HtmlEncode($this->session->CurrentValue);
			$this->session->PlaceHolder = ew_RemoveHtml($this->session->FldCaption());

			// enterprise_id
			$this->enterprise_id->EditAttrs["class"] = "form-control";
			$this->enterprise_id->EditCustomAttributes = "";
			$this->enterprise_id->EditValue = ew_HtmlEncode($this->enterprise_id->CurrentValue);
			$this->enterprise_id->PlaceHolder = ew_RemoveHtml($this->enterprise_id->FldCaption());

			// Add refer script
			// user_name

			$this->user_name->LinkCustomAttributes = "";
			$this->user_name->HrefValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// surname
			$this->surname->LinkCustomAttributes = "";
			$this->surname->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// userlevel_id
			$this->userlevel_id->LinkCustomAttributes = "";
			$this->userlevel_id->HrefValue = "";

			// email_address
			$this->email_address->LinkCustomAttributes = "";
			$this->email_address->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// state
			$this->state->LinkCustomAttributes = "";
			$this->state->HrefValue = "";

			// session
			$this->session->LinkCustomAttributes = "";
			$this->session->HrefValue = "";

			// enterprise_id
			$this->enterprise_id->LinkCustomAttributes = "";
			$this->enterprise_id->HrefValue = "";
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
		if (!$this->user_name->FldIsDetailKey && !is_null($this->user_name->FormValue) && $this->user_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_name->FldCaption(), $this->user_name->ReqErrMsg));
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->surname->FldIsDetailKey && !is_null($this->surname->FormValue) && $this->surname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->surname->FldCaption(), $this->surname->ReqErrMsg));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->password->FldCaption(), $this->password->ReqErrMsg));
		}
		if (!$this->userlevel_id->FldIsDetailKey && !is_null($this->userlevel_id->FormValue) && $this->userlevel_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->userlevel_id->FldCaption(), $this->userlevel_id->ReqErrMsg));
		}
		if (!$this->email_address->FldIsDetailKey && !is_null($this->email_address->FormValue) && $this->email_address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->email_address->FldCaption(), $this->email_address->ReqErrMsg));
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if ($this->state->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->state->FldCaption(), $this->state->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->enterprise_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->enterprise_id->FldErrMsg());
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

		// user_name
		$this->user_name->SetDbValueDef($rsnew, $this->user_name->CurrentValue, "", FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// surname
		$this->surname->SetDbValueDef($rsnew, $this->surname->CurrentValue, "", FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, "", FALSE);

		// userlevel_id
		if ($Security->CanAdmin()) { // System admin
		$this->userlevel_id->SetDbValueDef($rsnew, $this->userlevel_id->CurrentValue, 0, FALSE);
		}

		// email_address
		$this->email_address->SetDbValueDef($rsnew, $this->email_address->CurrentValue, "", FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, "", FALSE);

		// state
		$this->state->SetDbValueDef($rsnew, $this->state->CurrentValue, "", strval($this->state->CurrentValue) == "");

		// session
		$this->session->SetDbValueDef($rsnew, $this->session->CurrentValue, NULL, FALSE);

		// enterprise_id
		$this->enterprise_id->SetDbValueDef($rsnew, $this->enterprise_id->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->user_id->setDbValue($conn->Insert_ID());
				$rsnew['user_id'] = $this->user_id->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($user_add)) $user_add = new cuser_add();

// Page init
$user_add->Page_Init();

// Page main
$user_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fuseradd = new ew_Form("fuseradd", "add");

// Validate form
fuseradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_user_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->user_name->FldCaption(), $user->user_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->name->FldCaption(), $user->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_surname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->surname->FldCaption(), $user->surname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->password->FldCaption(), $user->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && $(elm).hasClass("ewPasswordStrength") && !$(elm).data("validated"))
				return this.OnError(elm, ewLanguage.Phrase("PasswordTooSimple"));
			elm = this.GetElements("x" + infix + "_userlevel_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->userlevel_id->FldCaption(), $user->userlevel_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_email_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->email_address->FldCaption(), $user->email_address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->description->FldCaption(), $user->description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_state");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->state->FldCaption(), $user->state->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_enterprise_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user->enterprise_id->FldErrMsg()) ?>");

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
fuseradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuseradd.ValidateRequired = true;
<?php } else { ?>
fuseradd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fuseradd.Lists["x_userlevel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuseradd.Lists["x_state"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuseradd.Lists["x_state"].Options = <?php echo json_encode($user->state->Options()) ?>;

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
<?php $user_add->ShowPageHeader(); ?>
<?php
$user_add->ShowMessage();
?>
<form name="fuseradd" id="fuseradd" class="<?php echo $user_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($user_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $user_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user">
<input type="hidden" name="a_add" id="a_add" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($user->user_name->Visible) { // user_name ?>
	<div id="r_user_name" class="form-group">
		<label id="elh_user_user_name" for="x_user_name" class="col-sm-2 control-label ewLabel"><?php echo $user->user_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->user_name->CellAttributes() ?>>
<span id="el_user_user_name">
<input type="text" data-table="user" data-field="x_user_name" name="x_user_name" id="x_user_name" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($user->user_name->getPlaceHolder()) ?>" value="<?php echo $user->user_name->EditValue ?>"<?php echo $user->user_name->EditAttributes() ?>>
</span>
<?php echo $user->user_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_user_name" for="x_name" class="col-sm-2 control-label ewLabel"><?php echo $user->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->name->CellAttributes() ?>>
<span id="el_user_name">
<input type="text" data-table="user" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="75" placeholder="<?php echo ew_HtmlEncode($user->name->getPlaceHolder()) ?>" value="<?php echo $user->name->EditValue ?>"<?php echo $user->name->EditAttributes() ?>>
</span>
<?php echo $user->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->surname->Visible) { // surname ?>
	<div id="r_surname" class="form-group">
		<label id="elh_user_surname" for="x_surname" class="col-sm-2 control-label ewLabel"><?php echo $user->surname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->surname->CellAttributes() ?>>
<span id="el_user_surname">
<input type="text" data-table="user" data-field="x_surname" name="x_surname" id="x_surname" size="30" maxlength="75" placeholder="<?php echo ew_HtmlEncode($user->surname->getPlaceHolder()) ?>" value="<?php echo $user->surname->EditValue ?>"<?php echo $user->surname->EditAttributes() ?>>
</span>
<?php echo $user->surname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_user_password" for="x_password" class="col-sm-2 control-label ewLabel"><?php echo $user->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->password->CellAttributes() ?>>
<span id="el_user_password">
<div class="input-group" id="ig_password">
<input type="text" data-password-strength="pst_password" data-password-generated="pgt_password" data-table="user" data-field="x_password" name="x_password" id="x_password" value="<?php echo $user->password->EditValue ?>" size="30" maxlength="75" placeholder="<?php echo ew_HtmlEncode($user->password->getPlaceHolder()) ?>"<?php echo $user->password->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_password" data-password-confirm="c_password" data-password-strength="pst_password" data-password-generated="pgt_password"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_password" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst_password" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php echo $user->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->userlevel_id->Visible) { // userlevel_id ?>
	<div id="r_userlevel_id" class="form-group">
		<label id="elh_user_userlevel_id" for="x_userlevel_id" class="col-sm-2 control-label ewLabel"><?php echo $user->userlevel_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->userlevel_id->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_user_userlevel_id">
<p class="form-control-static"><?php echo $user->userlevel_id->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_user_userlevel_id">
<select data-table="user" data-field="x_userlevel_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($user->userlevel_id->DisplayValueSeparator) ? json_encode($user->userlevel_id->DisplayValueSeparator) : $user->userlevel_id->DisplayValueSeparator) ?>" id="x_userlevel_id" name="x_userlevel_id"<?php echo $user->userlevel_id->EditAttributes() ?>>
<?php
if (is_array($user->userlevel_id->EditValue)) {
	$arwrk = $user->userlevel_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($user->userlevel_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $user->userlevel_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($user->userlevel_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($user->userlevel_id->CurrentValue) ?>" selected><?php echo $user->userlevel_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevel`";
$sWhereWrk = "";
$user->userlevel_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$user->userlevel_id->LookupFilters += array("f0" => "`userlevelid` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$user->Lookup_Selecting($user->userlevel_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $user->userlevel_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_userlevel_id" id="s_x_userlevel_id" value="<?php echo $user->userlevel_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $user->userlevel_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->email_address->Visible) { // email_address ?>
	<div id="r_email_address" class="form-group">
		<label id="elh_user_email_address" for="x_email_address" class="col-sm-2 control-label ewLabel"><?php echo $user->email_address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->email_address->CellAttributes() ?>>
<span id="el_user_email_address">
<input type="text" data-table="user" data-field="x_email_address" name="x_email_address" id="x_email_address" size="30" maxlength="175" placeholder="<?php echo ew_HtmlEncode($user->email_address->getPlaceHolder()) ?>" value="<?php echo $user->email_address->EditValue ?>"<?php echo $user->email_address->EditAttributes() ?>>
</span>
<?php echo $user->email_address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_user_description" for="x_description" class="col-sm-2 control-label ewLabel"><?php echo $user->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->description->CellAttributes() ?>>
<span id="el_user_description">
<input type="text" data-table="user" data-field="x_description" name="x_description" id="x_description" size="30" maxlength="175" placeholder="<?php echo ew_HtmlEncode($user->description->getPlaceHolder()) ?>" value="<?php echo $user->description->EditValue ?>"<?php echo $user->description->EditAttributes() ?>>
</span>
<?php echo $user->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->state->Visible) { // state ?>
	<div id="r_state" class="form-group">
		<label id="elh_user_state" class="col-sm-2 control-label ewLabel"><?php echo $user->state->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->state->CellAttributes() ?>>
<span id="el_user_state">
<div id="tp_x_state" class="ewTemplate"><input type="radio" data-table="user" data-field="x_state" data-value-separator="<?php echo ew_HtmlEncode(is_array($user->state->DisplayValueSeparator) ? json_encode($user->state->DisplayValueSeparator) : $user->state->DisplayValueSeparator) ?>" name="x_state" id="x_state" value="{value}"<?php echo $user->state->EditAttributes() ?>></div>
<div id="dsl_x_state" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $user->state->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($user->state->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="user" data-field="x_state" name="x_state" id="x_state_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $user->state->EditAttributes() ?>><?php echo $user->state->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($user->state->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="user" data-field="x_state" name="x_state" id="x_state_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($user->state->CurrentValue) ?>" checked<?php echo $user->state->EditAttributes() ?>><?php echo $user->state->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $user->state->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->session->Visible) { // session ?>
	<div id="r_session" class="form-group">
		<label id="elh_user_session" for="x_session" class="col-sm-2 control-label ewLabel"><?php echo $user->session->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $user->session->CellAttributes() ?>>
<span id="el_user_session">
<textarea data-table="user" data-field="x_session" name="x_session" id="x_session" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($user->session->getPlaceHolder()) ?>"<?php echo $user->session->EditAttributes() ?>><?php echo $user->session->EditValue ?></textarea>
</span>
<?php echo $user->session->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->enterprise_id->Visible) { // enterprise_id ?>
	<div id="r_enterprise_id" class="form-group">
		<label id="elh_user_enterprise_id" for="x_enterprise_id" class="col-sm-2 control-label ewLabel"><?php echo $user->enterprise_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $user->enterprise_id->CellAttributes() ?>>
<span id="el_user_enterprise_id">
<input type="text" data-table="user" data-field="x_enterprise_id" name="x_enterprise_id" id="x_enterprise_id" size="30" placeholder="<?php echo ew_HtmlEncode($user->enterprise_id->getPlaceHolder()) ?>" value="<?php echo $user->enterprise_id->EditValue ?>"<?php echo $user->enterprise_id->EditAttributes() ?>>
</span>
<?php echo $user->enterprise_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $user_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fuseradd.Init();
</script>
<?php
$user_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$user_add->Page_Terminate();
?>
