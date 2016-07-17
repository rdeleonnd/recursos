<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "informacion_familiarinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$informacion_familiar_list = NULL; // Initialize page object first

class cinformacion_familiar_list extends cinformacion_familiar {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'informacion_familiar';

	// Page object name
	var $PageObjName = 'informacion_familiar_list';

	// Grid form hidden field names
	var $FormName = 'finformacion_familiarlist';
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

		// Table object (informacion_familiar)
		if (!isset($GLOBALS["informacion_familiar"]) || get_class($GLOBALS["informacion_familiar"]) == "cinformacion_familiar") {
			$GLOBALS["informacion_familiar"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["informacion_familiar"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "informacion_familiaradd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "informacion_familiardelete.php";
		$this->MultiUpdateUrl = "informacion_familiarupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'informacion_familiar', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption finformacion_familiarlistsrch";

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
		global $EW_EXPORT, $informacion_familiar;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($informacion_familiar);
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
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

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
			$this->informacion_familiar_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->informacion_familiar_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJSON(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->fecha_nacimiento->AdvancedSearch->ToJSON(), ","); // Field fecha_nacimiento
		$sFilterList = ew_Concat($sFilterList, $this->afinidad_id->AdvancedSearch->ToJSON(), ","); // Field afinidad_id
		$sFilterList = ew_Concat($sFilterList, $this->telefono->AdvancedSearch->ToJSON(), ","); // Field telefono
		$sFilterList = ew_Concat($sFilterList, $this->lugar_trabajo->AdvancedSearch->ToJSON(), ","); // Field lugar_trabajo
		$sFilterList = ew_Concat($sFilterList, $this->empleado_id->AdvancedSearch->ToJSON(), ","); // Field empleado_id

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

		// Field nombre
		$this->nombre->AdvancedSearch->SearchValue = @$filter["x_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator = @$filter["z_nombre"];
		$this->nombre->AdvancedSearch->SearchCondition = @$filter["v_nombre"];
		$this->nombre->AdvancedSearch->SearchValue2 = @$filter["y_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator2 = @$filter["w_nombre"];
		$this->nombre->AdvancedSearch->Save();

		// Field fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = @$filter["x_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = @$filter["z_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchCondition = @$filter["v_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchValue2 = @$filter["y_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->Save();

		// Field afinidad_id
		$this->afinidad_id->AdvancedSearch->SearchValue = @$filter["x_afinidad_id"];
		$this->afinidad_id->AdvancedSearch->SearchOperator = @$filter["z_afinidad_id"];
		$this->afinidad_id->AdvancedSearch->SearchCondition = @$filter["v_afinidad_id"];
		$this->afinidad_id->AdvancedSearch->SearchValue2 = @$filter["y_afinidad_id"];
		$this->afinidad_id->AdvancedSearch->SearchOperator2 = @$filter["w_afinidad_id"];
		$this->afinidad_id->AdvancedSearch->Save();

		// Field telefono
		$this->telefono->AdvancedSearch->SearchValue = @$filter["x_telefono"];
		$this->telefono->AdvancedSearch->SearchOperator = @$filter["z_telefono"];
		$this->telefono->AdvancedSearch->SearchCondition = @$filter["v_telefono"];
		$this->telefono->AdvancedSearch->SearchValue2 = @$filter["y_telefono"];
		$this->telefono->AdvancedSearch->SearchOperator2 = @$filter["w_telefono"];
		$this->telefono->AdvancedSearch->Save();

		// Field lugar_trabajo
		$this->lugar_trabajo->AdvancedSearch->SearchValue = @$filter["x_lugar_trabajo"];
		$this->lugar_trabajo->AdvancedSearch->SearchOperator = @$filter["z_lugar_trabajo"];
		$this->lugar_trabajo->AdvancedSearch->SearchCondition = @$filter["v_lugar_trabajo"];
		$this->lugar_trabajo->AdvancedSearch->SearchValue2 = @$filter["y_lugar_trabajo"];
		$this->lugar_trabajo->AdvancedSearch->SearchOperator2 = @$filter["w_lugar_trabajo"];
		$this->lugar_trabajo->AdvancedSearch->Save();

		// Field empleado_id
		$this->empleado_id->AdvancedSearch->SearchValue = @$filter["x_empleado_id"];
		$this->empleado_id->AdvancedSearch->SearchOperator = @$filter["z_empleado_id"];
		$this->empleado_id->AdvancedSearch->SearchCondition = @$filter["v_empleado_id"];
		$this->empleado_id->AdvancedSearch->SearchValue2 = @$filter["y_empleado_id"];
		$this->empleado_id->AdvancedSearch->SearchOperator2 = @$filter["w_empleado_id"];
		$this->empleado_id->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->fecha_nacimiento, $Default, FALSE); // fecha_nacimiento
		$this->BuildSearchSql($sWhere, $this->afinidad_id, $Default, FALSE); // afinidad_id
		$this->BuildSearchSql($sWhere, $this->telefono, $Default, FALSE); // telefono
		$this->BuildSearchSql($sWhere, $this->lugar_trabajo, $Default, FALSE); // lugar_trabajo
		$this->BuildSearchSql($sWhere, $this->empleado_id, $Default, FALSE); // empleado_id

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->fecha_nacimiento->AdvancedSearch->Save(); // fecha_nacimiento
			$this->afinidad_id->AdvancedSearch->Save(); // afinidad_id
			$this->telefono->AdvancedSearch->Save(); // telefono
			$this->lugar_trabajo->AdvancedSearch->Save(); // lugar_trabajo
			$this->empleado_id->AdvancedSearch->Save(); // empleado_id
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

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_nacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->afinidad_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->telefono->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->lugar_trabajo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->empleado_id->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->fecha_nacimiento->AdvancedSearch->UnsetSession();
		$this->afinidad_id->AdvancedSearch->UnsetSession();
		$this->telefono->AdvancedSearch->UnsetSession();
		$this->lugar_trabajo->AdvancedSearch->UnsetSession();
		$this->empleado_id->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nombre->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->afinidad_id->AdvancedSearch->Load();
		$this->telefono->AdvancedSearch->Load();
		$this->lugar_trabajo->AdvancedSearch->Load();
		$this->empleado_id->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->fecha_nacimiento); // fecha_nacimiento
			$this->UpdateSort($this->afinidad_id); // afinidad_id
			$this->UpdateSort($this->telefono); // telefono
			$this->UpdateSort($this->lugar_trabajo); // lugar_trabajo
			$this->UpdateSort($this->empleado_id); // empleado_id
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
				$this->nombre->setSort("");
				$this->fecha_nacimiento->setSort("");
				$this->afinidad_id->setSort("");
				$this->telefono->setSort("");
				$this->lugar_trabajo->setSort("");
				$this->empleado_id->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->informacion_familiar_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"finformacion_familiarlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"finformacion_familiarlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.finformacion_familiarlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"finformacion_familiarlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// nombre

		$this->nombre->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nombre"]);
		if ($this->nombre->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nombre->AdvancedSearch->SearchOperator = @$_GET["z_nombre"];

		// fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_nacimiento"]);
		if ($this->fecha_nacimiento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = @$_GET["z_fecha_nacimiento"];

		// afinidad_id
		$this->afinidad_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_afinidad_id"]);
		if ($this->afinidad_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->afinidad_id->AdvancedSearch->SearchOperator = @$_GET["z_afinidad_id"];

		// telefono
		$this->telefono->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telefono"]);
		if ($this->telefono->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->telefono->AdvancedSearch->SearchOperator = @$_GET["z_telefono"];

		// lugar_trabajo
		$this->lugar_trabajo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_lugar_trabajo"]);
		if ($this->lugar_trabajo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->lugar_trabajo->AdvancedSearch->SearchOperator = @$_GET["z_lugar_trabajo"];

		// empleado_id
		$this->empleado_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_empleado_id"]);
		if ($this->empleado_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->empleado_id->AdvancedSearch->SearchOperator = @$_GET["z_empleado_id"];
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
		$this->informacion_familiar_id->setDbValue($rs->fields('informacion_familiar_id'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
		$this->afinidad_id->setDbValue($rs->fields('afinidad_id'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->lugar_trabajo->setDbValue($rs->fields('lugar_trabajo'));
		$this->empleado_id->setDbValue($rs->fields('empleado_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->informacion_familiar_id->DbValue = $row['informacion_familiar_id'];
		$this->nombre->DbValue = $row['nombre'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->afinidad_id->DbValue = $row['afinidad_id'];
		$this->telefono->DbValue = $row['telefono'];
		$this->lugar_trabajo->DbValue = $row['lugar_trabajo'];
		$this->empleado_id->DbValue = $row['empleado_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("informacion_familiar_id")) <> "")
			$this->informacion_familiar_id->CurrentValue = $this->getKey("informacion_familiar_id"); // informacion_familiar_id
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
		// informacion_familiar_id

		$this->informacion_familiar_id->CellCssStyle = "white-space: nowrap;";

		// nombre
		// fecha_nacimiento
		// afinidad_id
		// telefono
		// lugar_trabajo
		// empleado_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->AdvancedSearch->SearchValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_nacimiento->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// afinidad_id
			$this->afinidad_id->EditAttrs["class"] = "form-control";
			$this->afinidad_id->EditCustomAttributes = "";

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->AdvancedSearch->SearchValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// lugar_trabajo
			$this->lugar_trabajo->EditAttrs["class"] = "form-control";
			$this->lugar_trabajo->EditCustomAttributes = "";
			$this->lugar_trabajo->EditValue = ew_HtmlEncode($this->lugar_trabajo->AdvancedSearch->SearchValue);
			$this->lugar_trabajo->PlaceHolder = ew_RemoveHtml($this->lugar_trabajo->FldCaption());

			// empleado_id
			$this->empleado_id->EditAttrs["class"] = "form-control";
			$this->empleado_id->EditCustomAttributes = "";
			$this->empleado_id->EditValue = ew_HtmlEncode($this->empleado_id->AdvancedSearch->SearchValue);
			$this->empleado_id->PlaceHolder = ew_RemoveHtml($this->empleado_id->FldCaption());
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
		if (!ew_CheckInteger($this->empleado_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->empleado_id->FldErrMsg());
		}

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
		$this->nombre->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->afinidad_id->AdvancedSearch->Load();
		$this->telefono->AdvancedSearch->Load();
		$this->lugar_trabajo->AdvancedSearch->Load();
		$this->empleado_id->AdvancedSearch->Load();
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
if (!isset($informacion_familiar_list)) $informacion_familiar_list = new cinformacion_familiar_list();

// Page init
$informacion_familiar_list->Page_Init();

// Page main
$informacion_familiar_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$informacion_familiar_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = finformacion_familiarlist = new ew_Form("finformacion_familiarlist", "list");
finformacion_familiarlist.FormKeyCountName = '<?php echo $informacion_familiar_list->FormKeyCountName ?>';

// Form_CustomValidate event
finformacion_familiarlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finformacion_familiarlist.ValidateRequired = true;
<?php } else { ?>
finformacion_familiarlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
finformacion_familiarlist.Lists["x_afinidad_id"] = {"LinkField":"x_afinidad_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_afinidad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = finformacion_familiarlistsrch = new ew_Form("finformacion_familiarlistsrch");

// Validate function for search
finformacion_familiarlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_empleado_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($informacion_familiar->empleado_id->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
finformacion_familiarlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finformacion_familiarlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
finformacion_familiarlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($informacion_familiar_list->TotalRecs > 0 && $informacion_familiar_list->ExportOptions->Visible()) { ?>
<?php $informacion_familiar_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($informacion_familiar_list->SearchOptions->Visible()) { ?>
<?php $informacion_familiar_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($informacion_familiar_list->FilterOptions->Visible()) { ?>
<?php $informacion_familiar_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $informacion_familiar_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($informacion_familiar_list->TotalRecs <= 0)
			$informacion_familiar_list->TotalRecs = $informacion_familiar->SelectRecordCount();
	} else {
		if (!$informacion_familiar_list->Recordset && ($informacion_familiar_list->Recordset = $informacion_familiar_list->LoadRecordset()))
			$informacion_familiar_list->TotalRecs = $informacion_familiar_list->Recordset->RecordCount();
	}
	$informacion_familiar_list->StartRec = 1;
	if ($informacion_familiar_list->DisplayRecs <= 0 || ($informacion_familiar->Export <> "" && $informacion_familiar->ExportAll)) // Display all records
		$informacion_familiar_list->DisplayRecs = $informacion_familiar_list->TotalRecs;
	if (!($informacion_familiar->Export <> "" && $informacion_familiar->ExportAll))
		$informacion_familiar_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$informacion_familiar_list->Recordset = $informacion_familiar_list->LoadRecordset($informacion_familiar_list->StartRec-1, $informacion_familiar_list->DisplayRecs);

	// Set no record found message
	if ($informacion_familiar->CurrentAction == "" && $informacion_familiar_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$informacion_familiar_list->setWarningMessage(ew_DeniedMsg());
		if ($informacion_familiar_list->SearchWhere == "0=101")
			$informacion_familiar_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$informacion_familiar_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$informacion_familiar_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($informacion_familiar->Export == "" && $informacion_familiar->CurrentAction == "") { ?>
<form name="finformacion_familiarlistsrch" id="finformacion_familiarlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($informacion_familiar_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="finformacion_familiarlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="informacion_familiar">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$informacion_familiar_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$informacion_familiar->RowType = EW_ROWTYPE_SEARCH;

// Render row
$informacion_familiar->ResetAttrs();
$informacion_familiar_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($informacion_familiar->nombre->Visible) { // nombre ?>
	<div id="xsc_nombre" class="ewCell form-group">
		<label for="x_nombre" class="ewSearchCaption ewLabel"><?php echo $informacion_familiar->nombre->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombre" id="z_nombre" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="informacion_familiar" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="75" placeholder="<?php echo ew_HtmlEncode($informacion_familiar->nombre->getPlaceHolder()) ?>" value="<?php echo $informacion_familiar->nombre->EditValue ?>"<?php echo $informacion_familiar->nombre->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($informacion_familiar->empleado_id->Visible) { // empleado_id ?>
	<div id="xsc_empleado_id" class="ewCell form-group">
		<label for="x_empleado_id" class="ewSearchCaption ewLabel"><?php echo $informacion_familiar->empleado_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_empleado_id" id="z_empleado_id" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="informacion_familiar" data-field="x_empleado_id" name="x_empleado_id" id="x_empleado_id" size="30" placeholder="<?php echo ew_HtmlEncode($informacion_familiar->empleado_id->getPlaceHolder()) ?>" value="<?php echo $informacion_familiar->empleado_id->EditValue ?>"<?php echo $informacion_familiar->empleado_id->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $informacion_familiar_list->ShowPageHeader(); ?>
<?php
$informacion_familiar_list->ShowMessage();
?>
<?php if ($informacion_familiar_list->TotalRecs > 0 || $informacion_familiar->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="finformacion_familiarlist" id="finformacion_familiarlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($informacion_familiar_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $informacion_familiar_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="informacion_familiar">
<div id="gmp_informacion_familiar" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($informacion_familiar_list->TotalRecs > 0) { ?>
<table id="tbl_informacion_familiarlist" class="table ewTable">
<?php echo $informacion_familiar->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$informacion_familiar_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$informacion_familiar_list->RenderListOptions();

// Render list options (header, left)
$informacion_familiar_list->ListOptions->Render("header", "left");
?>
<?php if ($informacion_familiar->nombre->Visible) { // nombre ?>
	<?php if ($informacion_familiar->SortUrl($informacion_familiar->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_informacion_familiar_nombre" class="informacion_familiar_nombre"><div class="ewTableHeaderCaption"><?php echo $informacion_familiar->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $informacion_familiar->SortUrl($informacion_familiar->nombre) ?>',1);"><div id="elh_informacion_familiar_nombre" class="informacion_familiar_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $informacion_familiar->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($informacion_familiar->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($informacion_familiar->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($informacion_familiar->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<?php if ($informacion_familiar->SortUrl($informacion_familiar->fecha_nacimiento) == "") { ?>
		<th data-name="fecha_nacimiento"><div id="elh_informacion_familiar_fecha_nacimiento" class="informacion_familiar_fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $informacion_familiar->fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_nacimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $informacion_familiar->SortUrl($informacion_familiar->fecha_nacimiento) ?>',1);"><div id="elh_informacion_familiar_fecha_nacimiento" class="informacion_familiar_fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $informacion_familiar->fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($informacion_familiar->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($informacion_familiar->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($informacion_familiar->afinidad_id->Visible) { // afinidad_id ?>
	<?php if ($informacion_familiar->SortUrl($informacion_familiar->afinidad_id) == "") { ?>
		<th data-name="afinidad_id"><div id="elh_informacion_familiar_afinidad_id" class="informacion_familiar_afinidad_id"><div class="ewTableHeaderCaption"><?php echo $informacion_familiar->afinidad_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="afinidad_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $informacion_familiar->SortUrl($informacion_familiar->afinidad_id) ?>',1);"><div id="elh_informacion_familiar_afinidad_id" class="informacion_familiar_afinidad_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $informacion_familiar->afinidad_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($informacion_familiar->afinidad_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($informacion_familiar->afinidad_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($informacion_familiar->telefono->Visible) { // telefono ?>
	<?php if ($informacion_familiar->SortUrl($informacion_familiar->telefono) == "") { ?>
		<th data-name="telefono"><div id="elh_informacion_familiar_telefono" class="informacion_familiar_telefono"><div class="ewTableHeaderCaption"><?php echo $informacion_familiar->telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $informacion_familiar->SortUrl($informacion_familiar->telefono) ?>',1);"><div id="elh_informacion_familiar_telefono" class="informacion_familiar_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $informacion_familiar->telefono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($informacion_familiar->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($informacion_familiar->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($informacion_familiar->lugar_trabajo->Visible) { // lugar_trabajo ?>
	<?php if ($informacion_familiar->SortUrl($informacion_familiar->lugar_trabajo) == "") { ?>
		<th data-name="lugar_trabajo"><div id="elh_informacion_familiar_lugar_trabajo" class="informacion_familiar_lugar_trabajo"><div class="ewTableHeaderCaption"><?php echo $informacion_familiar->lugar_trabajo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lugar_trabajo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $informacion_familiar->SortUrl($informacion_familiar->lugar_trabajo) ?>',1);"><div id="elh_informacion_familiar_lugar_trabajo" class="informacion_familiar_lugar_trabajo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $informacion_familiar->lugar_trabajo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($informacion_familiar->lugar_trabajo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($informacion_familiar->lugar_trabajo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($informacion_familiar->empleado_id->Visible) { // empleado_id ?>
	<?php if ($informacion_familiar->SortUrl($informacion_familiar->empleado_id) == "") { ?>
		<th data-name="empleado_id"><div id="elh_informacion_familiar_empleado_id" class="informacion_familiar_empleado_id"><div class="ewTableHeaderCaption"><?php echo $informacion_familiar->empleado_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="empleado_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $informacion_familiar->SortUrl($informacion_familiar->empleado_id) ?>',1);"><div id="elh_informacion_familiar_empleado_id" class="informacion_familiar_empleado_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $informacion_familiar->empleado_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($informacion_familiar->empleado_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($informacion_familiar->empleado_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$informacion_familiar_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($informacion_familiar->ExportAll && $informacion_familiar->Export <> "") {
	$informacion_familiar_list->StopRec = $informacion_familiar_list->TotalRecs;
} else {

	// Set the last record to display
	if ($informacion_familiar_list->TotalRecs > $informacion_familiar_list->StartRec + $informacion_familiar_list->DisplayRecs - 1)
		$informacion_familiar_list->StopRec = $informacion_familiar_list->StartRec + $informacion_familiar_list->DisplayRecs - 1;
	else
		$informacion_familiar_list->StopRec = $informacion_familiar_list->TotalRecs;
}
$informacion_familiar_list->RecCnt = $informacion_familiar_list->StartRec - 1;
if ($informacion_familiar_list->Recordset && !$informacion_familiar_list->Recordset->EOF) {
	$informacion_familiar_list->Recordset->MoveFirst();
	$bSelectLimit = $informacion_familiar_list->UseSelectLimit;
	if (!$bSelectLimit && $informacion_familiar_list->StartRec > 1)
		$informacion_familiar_list->Recordset->Move($informacion_familiar_list->StartRec - 1);
} elseif (!$informacion_familiar->AllowAddDeleteRow && $informacion_familiar_list->StopRec == 0) {
	$informacion_familiar_list->StopRec = $informacion_familiar->GridAddRowCount;
}

// Initialize aggregate
$informacion_familiar->RowType = EW_ROWTYPE_AGGREGATEINIT;
$informacion_familiar->ResetAttrs();
$informacion_familiar_list->RenderRow();
while ($informacion_familiar_list->RecCnt < $informacion_familiar_list->StopRec) {
	$informacion_familiar_list->RecCnt++;
	if (intval($informacion_familiar_list->RecCnt) >= intval($informacion_familiar_list->StartRec)) {
		$informacion_familiar_list->RowCnt++;

		// Set up key count
		$informacion_familiar_list->KeyCount = $informacion_familiar_list->RowIndex;

		// Init row class and style
		$informacion_familiar->ResetAttrs();
		$informacion_familiar->CssClass = "";
		if ($informacion_familiar->CurrentAction == "gridadd") {
		} else {
			$informacion_familiar_list->LoadRowValues($informacion_familiar_list->Recordset); // Load row values
		}
		$informacion_familiar->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$informacion_familiar->RowAttrs = array_merge($informacion_familiar->RowAttrs, array('data-rowindex'=>$informacion_familiar_list->RowCnt, 'id'=>'r' . $informacion_familiar_list->RowCnt . '_informacion_familiar', 'data-rowtype'=>$informacion_familiar->RowType));

		// Render row
		$informacion_familiar_list->RenderRow();

		// Render list options
		$informacion_familiar_list->RenderListOptions();
?>
	<tr<?php echo $informacion_familiar->RowAttributes() ?>>
<?php

// Render list options (body, left)
$informacion_familiar_list->ListOptions->Render("body", "left", $informacion_familiar_list->RowCnt);
?>
	<?php if ($informacion_familiar->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $informacion_familiar->nombre->CellAttributes() ?>>
<span id="el<?php echo $informacion_familiar_list->RowCnt ?>_informacion_familiar_nombre" class="informacion_familiar_nombre">
<span<?php echo $informacion_familiar->nombre->ViewAttributes() ?>>
<?php echo $informacion_familiar->nombre->ListViewValue() ?></span>
</span>
<a id="<?php echo $informacion_familiar_list->PageObjName . "_row_" . $informacion_familiar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($informacion_familiar->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento"<?php echo $informacion_familiar->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $informacion_familiar_list->RowCnt ?>_informacion_familiar_fecha_nacimiento" class="informacion_familiar_fecha_nacimiento">
<span<?php echo $informacion_familiar->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $informacion_familiar->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($informacion_familiar->afinidad_id->Visible) { // afinidad_id ?>
		<td data-name="afinidad_id"<?php echo $informacion_familiar->afinidad_id->CellAttributes() ?>>
<span id="el<?php echo $informacion_familiar_list->RowCnt ?>_informacion_familiar_afinidad_id" class="informacion_familiar_afinidad_id">
<span<?php echo $informacion_familiar->afinidad_id->ViewAttributes() ?>>
<?php echo $informacion_familiar->afinidad_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($informacion_familiar->telefono->Visible) { // telefono ?>
		<td data-name="telefono"<?php echo $informacion_familiar->telefono->CellAttributes() ?>>
<span id="el<?php echo $informacion_familiar_list->RowCnt ?>_informacion_familiar_telefono" class="informacion_familiar_telefono">
<span<?php echo $informacion_familiar->telefono->ViewAttributes() ?>>
<?php echo $informacion_familiar->telefono->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($informacion_familiar->lugar_trabajo->Visible) { // lugar_trabajo ?>
		<td data-name="lugar_trabajo"<?php echo $informacion_familiar->lugar_trabajo->CellAttributes() ?>>
<span id="el<?php echo $informacion_familiar_list->RowCnt ?>_informacion_familiar_lugar_trabajo" class="informacion_familiar_lugar_trabajo">
<span<?php echo $informacion_familiar->lugar_trabajo->ViewAttributes() ?>>
<?php echo $informacion_familiar->lugar_trabajo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($informacion_familiar->empleado_id->Visible) { // empleado_id ?>
		<td data-name="empleado_id"<?php echo $informacion_familiar->empleado_id->CellAttributes() ?>>
<span id="el<?php echo $informacion_familiar_list->RowCnt ?>_informacion_familiar_empleado_id" class="informacion_familiar_empleado_id">
<span<?php echo $informacion_familiar->empleado_id->ViewAttributes() ?>>
<?php echo $informacion_familiar->empleado_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$informacion_familiar_list->ListOptions->Render("body", "right", $informacion_familiar_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($informacion_familiar->CurrentAction <> "gridadd")
		$informacion_familiar_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($informacion_familiar->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($informacion_familiar_list->Recordset)
	$informacion_familiar_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($informacion_familiar->CurrentAction <> "gridadd" && $informacion_familiar->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($informacion_familiar_list->Pager)) $informacion_familiar_list->Pager = new cPrevNextPager($informacion_familiar_list->StartRec, $informacion_familiar_list->DisplayRecs, $informacion_familiar_list->TotalRecs) ?>
<?php if ($informacion_familiar_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($informacion_familiar_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $informacion_familiar_list->PageUrl() ?>start=<?php echo $informacion_familiar_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($informacion_familiar_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $informacion_familiar_list->PageUrl() ?>start=<?php echo $informacion_familiar_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $informacion_familiar_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($informacion_familiar_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $informacion_familiar_list->PageUrl() ?>start=<?php echo $informacion_familiar_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($informacion_familiar_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $informacion_familiar_list->PageUrl() ?>start=<?php echo $informacion_familiar_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $informacion_familiar_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $informacion_familiar_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $informacion_familiar_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $informacion_familiar_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($informacion_familiar_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($informacion_familiar_list->TotalRecs == 0 && $informacion_familiar->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($informacion_familiar_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
finformacion_familiarlistsrch.Init();
finformacion_familiarlistsrch.FilterList = <?php echo $informacion_familiar_list->GetFilterList() ?>;
finformacion_familiarlist.Init();
</script>
<?php
$informacion_familiar_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$informacion_familiar_list->Page_Terminate();
?>
