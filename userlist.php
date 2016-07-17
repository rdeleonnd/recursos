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

$user_list = NULL; // Initialize page object first

class cuser_list extends cuser {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'user';

	// Page object name
	var $PageObjName = 'user_list';

	// Grid form hidden field names
	var $FormName = 'fuserlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (user)
		if (!isset($GLOBALS["user"]) || get_class($GLOBALS["user"]) == "cuser") {
			$GLOBALS["user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "useradd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "userdelete.php";
		$this->MultiUpdateUrl = "userupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fuserlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// Update last accessed time
		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->user_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Restore filter list
			$this->RestoreFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->user_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->user_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->user_id->AdvancedSearch->ToJSON(), ","); // Field user_id
		$sFilterList = ew_Concat($sFilterList, $this->user_name->AdvancedSearch->ToJSON(), ","); // Field user_name
		$sFilterList = ew_Concat($sFilterList, $this->name->AdvancedSearch->ToJSON(), ","); // Field name
		$sFilterList = ew_Concat($sFilterList, $this->surname->AdvancedSearch->ToJSON(), ","); // Field surname
		$sFilterList = ew_Concat($sFilterList, $this->password->AdvancedSearch->ToJSON(), ","); // Field password
		$sFilterList = ew_Concat($sFilterList, $this->userlevel_id->AdvancedSearch->ToJSON(), ","); // Field userlevel_id
		$sFilterList = ew_Concat($sFilterList, $this->email_address->AdvancedSearch->ToJSON(), ","); // Field email_address
		$sFilterList = ew_Concat($sFilterList, $this->description->AdvancedSearch->ToJSON(), ","); // Field description
		$sFilterList = ew_Concat($sFilterList, $this->state->AdvancedSearch->ToJSON(), ","); // Field state
		$sFilterList = ew_Concat($sFilterList, $this->session->AdvancedSearch->ToJSON(), ","); // Field session
		$sFilterList = ew_Concat($sFilterList, $this->enterprise_id->AdvancedSearch->ToJSON(), ","); // Field enterprise_id
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field user_id
		$this->user_id->AdvancedSearch->SearchValue = @$filter["x_user_id"];
		$this->user_id->AdvancedSearch->SearchOperator = @$filter["z_user_id"];
		$this->user_id->AdvancedSearch->SearchCondition = @$filter["v_user_id"];
		$this->user_id->AdvancedSearch->SearchValue2 = @$filter["y_user_id"];
		$this->user_id->AdvancedSearch->SearchOperator2 = @$filter["w_user_id"];
		$this->user_id->AdvancedSearch->Save();

		// Field user_name
		$this->user_name->AdvancedSearch->SearchValue = @$filter["x_user_name"];
		$this->user_name->AdvancedSearch->SearchOperator = @$filter["z_user_name"];
		$this->user_name->AdvancedSearch->SearchCondition = @$filter["v_user_name"];
		$this->user_name->AdvancedSearch->SearchValue2 = @$filter["y_user_name"];
		$this->user_name->AdvancedSearch->SearchOperator2 = @$filter["w_user_name"];
		$this->user_name->AdvancedSearch->Save();

		// Field name
		$this->name->AdvancedSearch->SearchValue = @$filter["x_name"];
		$this->name->AdvancedSearch->SearchOperator = @$filter["z_name"];
		$this->name->AdvancedSearch->SearchCondition = @$filter["v_name"];
		$this->name->AdvancedSearch->SearchValue2 = @$filter["y_name"];
		$this->name->AdvancedSearch->SearchOperator2 = @$filter["w_name"];
		$this->name->AdvancedSearch->Save();

		// Field surname
		$this->surname->AdvancedSearch->SearchValue = @$filter["x_surname"];
		$this->surname->AdvancedSearch->SearchOperator = @$filter["z_surname"];
		$this->surname->AdvancedSearch->SearchCondition = @$filter["v_surname"];
		$this->surname->AdvancedSearch->SearchValue2 = @$filter["y_surname"];
		$this->surname->AdvancedSearch->SearchOperator2 = @$filter["w_surname"];
		$this->surname->AdvancedSearch->Save();

		// Field password
		$this->password->AdvancedSearch->SearchValue = @$filter["x_password"];
		$this->password->AdvancedSearch->SearchOperator = @$filter["z_password"];
		$this->password->AdvancedSearch->SearchCondition = @$filter["v_password"];
		$this->password->AdvancedSearch->SearchValue2 = @$filter["y_password"];
		$this->password->AdvancedSearch->SearchOperator2 = @$filter["w_password"];
		$this->password->AdvancedSearch->Save();

		// Field userlevel_id
		$this->userlevel_id->AdvancedSearch->SearchValue = @$filter["x_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->SearchOperator = @$filter["z_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->SearchCondition = @$filter["v_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->SearchValue2 = @$filter["y_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->SearchOperator2 = @$filter["w_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->Save();

		// Field email_address
		$this->email_address->AdvancedSearch->SearchValue = @$filter["x_email_address"];
		$this->email_address->AdvancedSearch->SearchOperator = @$filter["z_email_address"];
		$this->email_address->AdvancedSearch->SearchCondition = @$filter["v_email_address"];
		$this->email_address->AdvancedSearch->SearchValue2 = @$filter["y_email_address"];
		$this->email_address->AdvancedSearch->SearchOperator2 = @$filter["w_email_address"];
		$this->email_address->AdvancedSearch->Save();

		// Field description
		$this->description->AdvancedSearch->SearchValue = @$filter["x_description"];
		$this->description->AdvancedSearch->SearchOperator = @$filter["z_description"];
		$this->description->AdvancedSearch->SearchCondition = @$filter["v_description"];
		$this->description->AdvancedSearch->SearchValue2 = @$filter["y_description"];
		$this->description->AdvancedSearch->SearchOperator2 = @$filter["w_description"];
		$this->description->AdvancedSearch->Save();

		// Field state
		$this->state->AdvancedSearch->SearchValue = @$filter["x_state"];
		$this->state->AdvancedSearch->SearchOperator = @$filter["z_state"];
		$this->state->AdvancedSearch->SearchCondition = @$filter["v_state"];
		$this->state->AdvancedSearch->SearchValue2 = @$filter["y_state"];
		$this->state->AdvancedSearch->SearchOperator2 = @$filter["w_state"];
		$this->state->AdvancedSearch->Save();

		// Field session
		$this->session->AdvancedSearch->SearchValue = @$filter["x_session"];
		$this->session->AdvancedSearch->SearchOperator = @$filter["z_session"];
		$this->session->AdvancedSearch->SearchCondition = @$filter["v_session"];
		$this->session->AdvancedSearch->SearchValue2 = @$filter["y_session"];
		$this->session->AdvancedSearch->SearchOperator2 = @$filter["w_session"];
		$this->session->AdvancedSearch->Save();

		// Field enterprise_id
		$this->enterprise_id->AdvancedSearch->SearchValue = @$filter["x_enterprise_id"];
		$this->enterprise_id->AdvancedSearch->SearchOperator = @$filter["z_enterprise_id"];
		$this->enterprise_id->AdvancedSearch->SearchCondition = @$filter["v_enterprise_id"];
		$this->enterprise_id->AdvancedSearch->SearchValue2 = @$filter["y_enterprise_id"];
		$this->enterprise_id->AdvancedSearch->SearchOperator2 = @$filter["w_enterprise_id"];
		$this->enterprise_id->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->user_id, $Default, FALSE); // user_id
		$this->BuildSearchSql($sWhere, $this->user_name, $Default, FALSE); // user_name
		$this->BuildSearchSql($sWhere, $this->name, $Default, FALSE); // name
		$this->BuildSearchSql($sWhere, $this->surname, $Default, FALSE); // surname
		$this->BuildSearchSql($sWhere, $this->password, $Default, FALSE); // password
		$this->BuildSearchSql($sWhere, $this->userlevel_id, $Default, FALSE); // userlevel_id
		$this->BuildSearchSql($sWhere, $this->email_address, $Default, FALSE); // email_address
		$this->BuildSearchSql($sWhere, $this->description, $Default, FALSE); // description
		$this->BuildSearchSql($sWhere, $this->state, $Default, FALSE); // state
		$this->BuildSearchSql($sWhere, $this->session, $Default, FALSE); // session
		$this->BuildSearchSql($sWhere, $this->enterprise_id, $Default, FALSE); // enterprise_id

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->user_id->AdvancedSearch->Save(); // user_id
			$this->user_name->AdvancedSearch->Save(); // user_name
			$this->name->AdvancedSearch->Save(); // name
			$this->surname->AdvancedSearch->Save(); // surname
			$this->password->AdvancedSearch->Save(); // password
			$this->userlevel_id->AdvancedSearch->Save(); // userlevel_id
			$this->email_address->AdvancedSearch->Save(); // email_address
			$this->description->AdvancedSearch->Save(); // description
			$this->state->AdvancedSearch->Save(); // state
			$this->session->AdvancedSearch->Save(); // session
			$this->enterprise_id->AdvancedSearch->Save(); // enterprise_id
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->user_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->surname, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->email_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->session, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->user_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->user_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->surname->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->password->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->userlevel_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->email_address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->description->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->state->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->session->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->enterprise_id->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->user_id->AdvancedSearch->UnsetSession();
		$this->user_name->AdvancedSearch->UnsetSession();
		$this->name->AdvancedSearch->UnsetSession();
		$this->surname->AdvancedSearch->UnsetSession();
		$this->password->AdvancedSearch->UnsetSession();
		$this->userlevel_id->AdvancedSearch->UnsetSession();
		$this->email_address->AdvancedSearch->UnsetSession();
		$this->description->AdvancedSearch->UnsetSession();
		$this->state->AdvancedSearch->UnsetSession();
		$this->session->AdvancedSearch->UnsetSession();
		$this->enterprise_id->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->user_id->AdvancedSearch->Load();
		$this->user_name->AdvancedSearch->Load();
		$this->name->AdvancedSearch->Load();
		$this->surname->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->userlevel_id->AdvancedSearch->Load();
		$this->email_address->AdvancedSearch->Load();
		$this->description->AdvancedSearch->Load();
		$this->state->AdvancedSearch->Load();
		$this->session->AdvancedSearch->Load();
		$this->enterprise_id->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->user_id); // user_id
			$this->UpdateSort($this->user_name); // user_name
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->surname); // surname
			$this->UpdateSort($this->password); // password
			$this->UpdateSort($this->userlevel_id); // userlevel_id
			$this->UpdateSort($this->email_address); // email_address
			$this->UpdateSort($this->description); // description
			$this->UpdateSort($this->state); // state
			$this->UpdateSort($this->enterprise_id); // enterprise_id
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->user_id->setSort("");
				$this->user_name->setSort("");
				$this->name->setSort("");
				$this->surname->setSort("");
				$this->password->setSort("");
				$this->userlevel_id->setSort("");
				$this->email_address->setSort("");
				$this->description->setSort("");
				$this->state->setSort("");
				$this->enterprise_id->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->user_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fuserlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fuserlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fuserlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		global $UserProfile;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$user = $row['user_name'];
					if ($userlist <> "") $userlist .= ",";
					$userlist .= $user;
					if ($UserAction == "resendregisteremail")
						$Processed = FALSE;
					elseif ($UserAction == "resetconcurrentuser")
						$Processed = FALSE;
					elseif ($UserAction == "resetloginretry")
						$Processed = FALSE;
					elseif ($UserAction == "setpasswordexpired")
						$Processed = FALSE;
					else
						$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fuserlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// user_id

		$this->user_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_user_id"]);
		if ($this->user_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->user_id->AdvancedSearch->SearchOperator = @$_GET["z_user_id"];

		// user_name
		$this->user_name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_user_name"]);
		if ($this->user_name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->user_name->AdvancedSearch->SearchOperator = @$_GET["z_user_name"];

		// name
		$this->name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_name"]);
		if ($this->name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->name->AdvancedSearch->SearchOperator = @$_GET["z_name"];

		// surname
		$this->surname->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_surname"]);
		if ($this->surname->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->surname->AdvancedSearch->SearchOperator = @$_GET["z_surname"];

		// password
		$this->password->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_password"]);
		if ($this->password->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->password->AdvancedSearch->SearchOperator = @$_GET["z_password"];

		// userlevel_id
		$this->userlevel_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_userlevel_id"]);
		if ($this->userlevel_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->userlevel_id->AdvancedSearch->SearchOperator = @$_GET["z_userlevel_id"];

		// email_address
		$this->email_address->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_email_address"]);
		if ($this->email_address->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->email_address->AdvancedSearch->SearchOperator = @$_GET["z_email_address"];

		// description
		$this->description->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_description"]);
		if ($this->description->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->description->AdvancedSearch->SearchOperator = @$_GET["z_description"];

		// state
		$this->state->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_state"]);
		if ($this->state->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->state->AdvancedSearch->SearchOperator = @$_GET["z_state"];

		// session
		$this->session->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_session"]);
		if ($this->session->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->session->AdvancedSearch->SearchOperator = @$_GET["z_session"];

		// enterprise_id
		$this->enterprise_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_enterprise_id"]);
		if ($this->enterprise_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->enterprise_id->AdvancedSearch->SearchOperator = @$_GET["z_enterprise_id"];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

			// enterprise_id
			$this->enterprise_id->LinkCustomAttributes = "";
			$this->enterprise_id->HrefValue = "";
			$this->enterprise_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->AdvancedSearch->SearchValue);
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// user_name
			$this->user_name->EditAttrs["class"] = "form-control";
			$this->user_name->EditCustomAttributes = "";
			$this->user_name->EditValue = ew_HtmlEncode($this->user_name->AdvancedSearch->SearchValue);
			$this->user_name->PlaceHolder = ew_RemoveHtml($this->user_name->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->AdvancedSearch->SearchValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// surname
			$this->surname->EditAttrs["class"] = "form-control";
			$this->surname->EditCustomAttributes = "";
			$this->surname->EditValue = ew_HtmlEncode($this->surname->AdvancedSearch->SearchValue);
			$this->surname->PlaceHolder = ew_RemoveHtml($this->surname->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->AdvancedSearch->SearchValue);
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
			$this->email_address->EditValue = ew_HtmlEncode($this->email_address->AdvancedSearch->SearchValue);
			$this->email_address->PlaceHolder = ew_RemoveHtml($this->email_address->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->AdvancedSearch->SearchValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// state
			$this->state->EditCustomAttributes = "";
			$this->state->EditValue = $this->state->Options(FALSE);

			// enterprise_id
			$this->enterprise_id->EditAttrs["class"] = "form-control";
			$this->enterprise_id->EditCustomAttributes = "";
			$this->enterprise_id->EditValue = ew_HtmlEncode($this->enterprise_id->AdvancedSearch->SearchValue);
			$this->enterprise_id->PlaceHolder = ew_RemoveHtml($this->enterprise_id->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->user_id->AdvancedSearch->Load();
		$this->user_name->AdvancedSearch->Load();
		$this->name->AdvancedSearch->Load();
		$this->surname->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->userlevel_id->AdvancedSearch->Load();
		$this->email_address->AdvancedSearch->Load();
		$this->description->AdvancedSearch->Load();
		$this->state->AdvancedSearch->Load();
		$this->session->AdvancedSearch->Load();
		$this->enterprise_id->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($user_list)) $user_list = new cuser_list();

// Page init
$user_list->Page_Init();

// Page main
$user_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fuserlist = new ew_Form("fuserlist", "list");
fuserlist.FormKeyCountName = '<?php echo $user_list->FormKeyCountName ?>';

// Form_CustomValidate event
fuserlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuserlist.ValidateRequired = true;
<?php } else { ?>
fuserlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fuserlist.Lists["x_userlevel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserlist.Lists["x_state"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserlist.Lists["x_state"].Options = <?php echo json_encode($user->state->Options()) ?>;

// Form object for search
var CurrentSearchForm = fuserlistsrch = new ew_Form("fuserlistsrch");

// Validate function for search
fuserlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fuserlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuserlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fuserlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fuserlistsrch.Lists["x_state"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserlistsrch.Lists["x_state"].Options = <?php echo json_encode($user->state->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($user_list->TotalRecs > 0 && $user_list->ExportOptions->Visible()) { ?>
<?php $user_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($user_list->SearchOptions->Visible()) { ?>
<?php $user_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($user_list->FilterOptions->Visible()) { ?>
<?php $user_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $user_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($user_list->TotalRecs <= 0)
			$user_list->TotalRecs = $user->SelectRecordCount();
	} else {
		if (!$user_list->Recordset && ($user_list->Recordset = $user_list->LoadRecordset()))
			$user_list->TotalRecs = $user_list->Recordset->RecordCount();
	}
	$user_list->StartRec = 1;
	if ($user_list->DisplayRecs <= 0 || ($user->Export <> "" && $user->ExportAll)) // Display all records
		$user_list->DisplayRecs = $user_list->TotalRecs;
	if (!($user->Export <> "" && $user->ExportAll))
		$user_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$user_list->Recordset = $user_list->LoadRecordset($user_list->StartRec-1, $user_list->DisplayRecs);

	// Set no record found message
	if ($user->CurrentAction == "" && $user_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$user_list->setWarningMessage(ew_DeniedMsg());
		if ($user_list->SearchWhere == "0=101")
			$user_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$user_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$user_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($user->Export == "" && $user->CurrentAction == "") { ?>
<form name="fuserlistsrch" id="fuserlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($user_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fuserlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="user">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$user_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$user->RowType = EW_ROWTYPE_SEARCH;

// Render row
$user->ResetAttrs();
$user_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($user->state->Visible) { // state ?>
	<div id="xsc_state" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $user->state->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_state" id="z_state" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_state" class="ewTemplate"><input type="radio" data-table="user" data-field="x_state" data-value-separator="<?php echo ew_HtmlEncode(is_array($user->state->DisplayValueSeparator) ? json_encode($user->state->DisplayValueSeparator) : $user->state->DisplayValueSeparator) ?>" name="x_state" id="x_state" value="{value}"<?php echo $user->state->EditAttributes() ?>></div>
<div id="dsl_x_state" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $user->state->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($user->state->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
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
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($user_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($user_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $user_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($user_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($user_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($user_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($user_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $user_list->ShowPageHeader(); ?>
<?php
$user_list->ShowMessage();
?>
<?php if ($user_list->TotalRecs > 0 || $user->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fuserlist" id="fuserlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($user_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $user_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user">
<div id="gmp_user" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($user_list->TotalRecs > 0) { ?>
<table id="tbl_userlist" class="table ewTable">
<?php echo $user->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$user_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$user_list->RenderListOptions();

// Render list options (header, left)
$user_list->ListOptions->Render("header", "left");
?>
<?php if ($user->user_id->Visible) { // user_id ?>
	<?php if ($user->SortUrl($user->user_id) == "") { ?>
		<th data-name="user_id"><div id="elh_user_user_id" class="user_user_id"><div class="ewTableHeaderCaption"><?php echo $user->user_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->user_id) ?>',1);"><div id="elh_user_user_id" class="user_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->user_name->Visible) { // user_name ?>
	<?php if ($user->SortUrl($user->user_name) == "") { ?>
		<th data-name="user_name"><div id="elh_user_user_name" class="user_user_name"><div class="ewTableHeaderCaption"><?php echo $user->user_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->user_name) ?>',1);"><div id="elh_user_user_name" class="user_user_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->user_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($user->user_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->user_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->name->Visible) { // name ?>
	<?php if ($user->SortUrl($user->name) == "") { ?>
		<th data-name="name"><div id="elh_user_name" class="user_name"><div class="ewTableHeaderCaption"><?php echo $user->name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->name) ?>',1);"><div id="elh_user_name" class="user_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($user->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->surname->Visible) { // surname ?>
	<?php if ($user->SortUrl($user->surname) == "") { ?>
		<th data-name="surname"><div id="elh_user_surname" class="user_surname"><div class="ewTableHeaderCaption"><?php echo $user->surname->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="surname"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->surname) ?>',1);"><div id="elh_user_surname" class="user_surname">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->surname->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($user->surname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->surname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->password->Visible) { // password ?>
	<?php if ($user->SortUrl($user->password) == "") { ?>
		<th data-name="password"><div id="elh_user_password" class="user_password"><div class="ewTableHeaderCaption"><?php echo $user->password->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="password"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->password) ?>',1);"><div id="elh_user_password" class="user_password">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->password->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($user->password->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->password->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->userlevel_id->Visible) { // userlevel_id ?>
	<?php if ($user->SortUrl($user->userlevel_id) == "") { ?>
		<th data-name="userlevel_id"><div id="elh_user_userlevel_id" class="user_userlevel_id"><div class="ewTableHeaderCaption"><?php echo $user->userlevel_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="userlevel_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->userlevel_id) ?>',1);"><div id="elh_user_userlevel_id" class="user_userlevel_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->userlevel_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user->userlevel_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->userlevel_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->email_address->Visible) { // email_address ?>
	<?php if ($user->SortUrl($user->email_address) == "") { ?>
		<th data-name="email_address"><div id="elh_user_email_address" class="user_email_address"><div class="ewTableHeaderCaption"><?php echo $user->email_address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="email_address"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->email_address) ?>',1);"><div id="elh_user_email_address" class="user_email_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->email_address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($user->email_address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->email_address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->description->Visible) { // description ?>
	<?php if ($user->SortUrl($user->description) == "") { ?>
		<th data-name="description"><div id="elh_user_description" class="user_description"><div class="ewTableHeaderCaption"><?php echo $user->description->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="description"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->description) ?>',1);"><div id="elh_user_description" class="user_description">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->description->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($user->description->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->description->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->state->Visible) { // state ?>
	<?php if ($user->SortUrl($user->state) == "") { ?>
		<th data-name="state"><div id="elh_user_state" class="user_state"><div class="ewTableHeaderCaption"><?php echo $user->state->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="state"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->state) ?>',1);"><div id="elh_user_state" class="user_state">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->state->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user->state->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->state->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($user->enterprise_id->Visible) { // enterprise_id ?>
	<?php if ($user->SortUrl($user->enterprise_id) == "") { ?>
		<th data-name="enterprise_id"><div id="elh_user_enterprise_id" class="user_enterprise_id"><div class="ewTableHeaderCaption"><?php echo $user->enterprise_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="enterprise_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $user->SortUrl($user->enterprise_id) ?>',1);"><div id="elh_user_enterprise_id" class="user_enterprise_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user->enterprise_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user->enterprise_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user->enterprise_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$user_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($user->ExportAll && $user->Export <> "") {
	$user_list->StopRec = $user_list->TotalRecs;
} else {

	// Set the last record to display
	if ($user_list->TotalRecs > $user_list->StartRec + $user_list->DisplayRecs - 1)
		$user_list->StopRec = $user_list->StartRec + $user_list->DisplayRecs - 1;
	else
		$user_list->StopRec = $user_list->TotalRecs;
}
$user_list->RecCnt = $user_list->StartRec - 1;
if ($user_list->Recordset && !$user_list->Recordset->EOF) {
	$user_list->Recordset->MoveFirst();
	$bSelectLimit = $user_list->UseSelectLimit;
	if (!$bSelectLimit && $user_list->StartRec > 1)
		$user_list->Recordset->Move($user_list->StartRec - 1);
} elseif (!$user->AllowAddDeleteRow && $user_list->StopRec == 0) {
	$user_list->StopRec = $user->GridAddRowCount;
}

// Initialize aggregate
$user->RowType = EW_ROWTYPE_AGGREGATEINIT;
$user->ResetAttrs();
$user_list->RenderRow();
while ($user_list->RecCnt < $user_list->StopRec) {
	$user_list->RecCnt++;
	if (intval($user_list->RecCnt) >= intval($user_list->StartRec)) {
		$user_list->RowCnt++;

		// Set up key count
		$user_list->KeyCount = $user_list->RowIndex;

		// Init row class and style
		$user->ResetAttrs();
		$user->CssClass = "";
		if ($user->CurrentAction == "gridadd") {
		} else {
			$user_list->LoadRowValues($user_list->Recordset); // Load row values
		}
		$user->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$user->RowAttrs = array_merge($user->RowAttrs, array('data-rowindex'=>$user_list->RowCnt, 'id'=>'r' . $user_list->RowCnt . '_user', 'data-rowtype'=>$user->RowType));

		// Render row
		$user_list->RenderRow();

		// Render list options
		$user_list->RenderListOptions();
?>
	<tr<?php echo $user->RowAttributes() ?>>
<?php

// Render list options (body, left)
$user_list->ListOptions->Render("body", "left", $user_list->RowCnt);
?>
	<?php if ($user->user_id->Visible) { // user_id ?>
		<td data-name="user_id"<?php echo $user->user_id->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_user_id" class="user_user_id">
<span<?php echo $user->user_id->ViewAttributes() ?>>
<?php echo $user->user_id->ListViewValue() ?></span>
</span>
<a id="<?php echo $user_list->PageObjName . "_row_" . $user_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($user->user_name->Visible) { // user_name ?>
		<td data-name="user_name"<?php echo $user->user_name->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_user_name" class="user_user_name">
<span<?php echo $user->user_name->ViewAttributes() ?>>
<?php echo $user->user_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($user->name->Visible) { // name ?>
		<td data-name="name"<?php echo $user->name->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_name" class="user_name">
<span<?php echo $user->name->ViewAttributes() ?>>
<?php echo $user->name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($user->surname->Visible) { // surname ?>
		<td data-name="surname"<?php echo $user->surname->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_surname" class="user_surname">
<span<?php echo $user->surname->ViewAttributes() ?>>
<?php echo $user->surname->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($user->password->Visible) { // password ?>
		<td data-name="password"<?php echo $user->password->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_password" class="user_password">
<span<?php echo $user->password->ViewAttributes() ?>>
<?php echo $user->password->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($user->userlevel_id->Visible) { // userlevel_id ?>
		<td data-name="userlevel_id"<?php echo $user->userlevel_id->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_userlevel_id" class="user_userlevel_id">
<span<?php echo $user->userlevel_id->ViewAttributes() ?>>
<?php echo $user->userlevel_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($user->email_address->Visible) { // email_address ?>
		<td data-name="email_address"<?php echo $user->email_address->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_email_address" class="user_email_address">
<span<?php echo $user->email_address->ViewAttributes() ?>>
<?php echo $user->email_address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($user->description->Visible) { // description ?>
		<td data-name="description"<?php echo $user->description->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_description" class="user_description">
<span<?php echo $user->description->ViewAttributes() ?>>
<?php echo $user->description->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($user->state->Visible) { // state ?>
		<td data-name="state"<?php echo $user->state->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_state" class="user_state">
<span<?php echo $user->state->ViewAttributes() ?>>
<?php echo $user->state->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($user->enterprise_id->Visible) { // enterprise_id ?>
		<td data-name="enterprise_id"<?php echo $user->enterprise_id->CellAttributes() ?>>
<span id="el<?php echo $user_list->RowCnt ?>_user_enterprise_id" class="user_enterprise_id">
<span<?php echo $user->enterprise_id->ViewAttributes() ?>>
<?php echo $user->enterprise_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$user_list->ListOptions->Render("body", "right", $user_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($user->CurrentAction <> "gridadd")
		$user_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($user->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($user_list->Recordset)
	$user_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($user->CurrentAction <> "gridadd" && $user->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($user_list->Pager)) $user_list->Pager = new cPrevNextPager($user_list->StartRec, $user_list->DisplayRecs, $user_list->TotalRecs) ?>
<?php if ($user_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($user_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $user_list->PageUrl() ?>start=<?php echo $user_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($user_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $user_list->PageUrl() ?>start=<?php echo $user_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $user_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($user_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $user_list->PageUrl() ?>start=<?php echo $user_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($user_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $user_list->PageUrl() ?>start=<?php echo $user_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $user_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $user_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $user_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $user_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($user_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($user_list->TotalRecs == 0 && $user->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($user_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fuserlistsrch.Init();
fuserlistsrch.FilterList = <?php echo $user_list->GetFilterList() ?>;
fuserlist.Init();
</script>
<?php
$user_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$user_list->Page_Terminate();
?>
