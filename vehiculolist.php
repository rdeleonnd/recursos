<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "vehiculoinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$vehiculo_list = NULL; // Initialize page object first

class cvehiculo_list extends cvehiculo {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'vehiculo';

	// Page object name
	var $PageObjName = 'vehiculo_list';

	// Grid form hidden field names
	var $FormName = 'fvehiculolist';
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

		// Table object (vehiculo)
		if (!isset($GLOBALS["vehiculo"]) || get_class($GLOBALS["vehiculo"]) == "cvehiculo") {
			$GLOBALS["vehiculo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vehiculo"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vehiculoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vehiculodelete.php";
		$this->MultiUpdateUrl = "vehiculoupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vehiculo', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fvehiculolistsrch";

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
		global $EW_EXPORT, $vehiculo;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vehiculo);
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
			$this->vehiculo_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->vehiculo_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->tipo_vehiculo_id->AdvancedSearch->ToJSON(), ","); // Field tipo_vehiculo_id
		$sFilterList = ew_Concat($sFilterList, $this->placas->AdvancedSearch->ToJSON(), ","); // Field placas
		$sFilterList = ew_Concat($sFilterList, $this->modelo->AdvancedSearch->ToJSON(), ","); // Field modelo
		$sFilterList = ew_Concat($sFilterList, $this->color->AdvancedSearch->ToJSON(), ","); // Field color
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

		// Field tipo_vehiculo_id
		$this->tipo_vehiculo_id->AdvancedSearch->SearchValue = @$filter["x_tipo_vehiculo_id"];
		$this->tipo_vehiculo_id->AdvancedSearch->SearchOperator = @$filter["z_tipo_vehiculo_id"];
		$this->tipo_vehiculo_id->AdvancedSearch->SearchCondition = @$filter["v_tipo_vehiculo_id"];
		$this->tipo_vehiculo_id->AdvancedSearch->SearchValue2 = @$filter["y_tipo_vehiculo_id"];
		$this->tipo_vehiculo_id->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_vehiculo_id"];
		$this->tipo_vehiculo_id->AdvancedSearch->Save();

		// Field placas
		$this->placas->AdvancedSearch->SearchValue = @$filter["x_placas"];
		$this->placas->AdvancedSearch->SearchOperator = @$filter["z_placas"];
		$this->placas->AdvancedSearch->SearchCondition = @$filter["v_placas"];
		$this->placas->AdvancedSearch->SearchValue2 = @$filter["y_placas"];
		$this->placas->AdvancedSearch->SearchOperator2 = @$filter["w_placas"];
		$this->placas->AdvancedSearch->Save();

		// Field modelo
		$this->modelo->AdvancedSearch->SearchValue = @$filter["x_modelo"];
		$this->modelo->AdvancedSearch->SearchOperator = @$filter["z_modelo"];
		$this->modelo->AdvancedSearch->SearchCondition = @$filter["v_modelo"];
		$this->modelo->AdvancedSearch->SearchValue2 = @$filter["y_modelo"];
		$this->modelo->AdvancedSearch->SearchOperator2 = @$filter["w_modelo"];
		$this->modelo->AdvancedSearch->Save();

		// Field color
		$this->color->AdvancedSearch->SearchValue = @$filter["x_color"];
		$this->color->AdvancedSearch->SearchOperator = @$filter["z_color"];
		$this->color->AdvancedSearch->SearchCondition = @$filter["v_color"];
		$this->color->AdvancedSearch->SearchValue2 = @$filter["y_color"];
		$this->color->AdvancedSearch->SearchOperator2 = @$filter["w_color"];
		$this->color->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->tipo_vehiculo_id, $Default, FALSE); // tipo_vehiculo_id
		$this->BuildSearchSql($sWhere, $this->placas, $Default, FALSE); // placas
		$this->BuildSearchSql($sWhere, $this->modelo, $Default, FALSE); // modelo
		$this->BuildSearchSql($sWhere, $this->color, $Default, FALSE); // color
		$this->BuildSearchSql($sWhere, $this->empleado_id, $Default, FALSE); // empleado_id

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->tipo_vehiculo_id->AdvancedSearch->Save(); // tipo_vehiculo_id
			$this->placas->AdvancedSearch->Save(); // placas
			$this->modelo->AdvancedSearch->Save(); // modelo
			$this->color->AdvancedSearch->Save(); // color
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
		if ($this->tipo_vehiculo_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->placas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->modelo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->color->AdvancedSearch->IssetSession())
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
		$this->tipo_vehiculo_id->AdvancedSearch->UnsetSession();
		$this->placas->AdvancedSearch->UnsetSession();
		$this->modelo->AdvancedSearch->UnsetSession();
		$this->color->AdvancedSearch->UnsetSession();
		$this->empleado_id->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->tipo_vehiculo_id->AdvancedSearch->Load();
		$this->placas->AdvancedSearch->Load();
		$this->modelo->AdvancedSearch->Load();
		$this->color->AdvancedSearch->Load();
		$this->empleado_id->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->tipo_vehiculo_id); // tipo_vehiculo_id
			$this->UpdateSort($this->placas); // placas
			$this->UpdateSort($this->modelo); // modelo
			$this->UpdateSort($this->color); // color
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
				$this->tipo_vehiculo_id->setSort("");
				$this->placas->setSort("");
				$this->modelo->setSort("");
				$this->color->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->vehiculo_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fvehiculolistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fvehiculolistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fvehiculolist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fvehiculolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// tipo_vehiculo_id

		$this->tipo_vehiculo_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipo_vehiculo_id"]);
		if ($this->tipo_vehiculo_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipo_vehiculo_id->AdvancedSearch->SearchOperator = @$_GET["z_tipo_vehiculo_id"];

		// placas
		$this->placas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_placas"]);
		if ($this->placas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->placas->AdvancedSearch->SearchOperator = @$_GET["z_placas"];

		// modelo
		$this->modelo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_modelo"]);
		if ($this->modelo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->modelo->AdvancedSearch->SearchOperator = @$_GET["z_modelo"];

		// color
		$this->color->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_color"]);
		if ($this->color->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->color->AdvancedSearch->SearchOperator = @$_GET["z_color"];

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
		$this->vehiculo_id->setDbValue($rs->fields('vehiculo_id'));
		$this->tipo_vehiculo_id->setDbValue($rs->fields('tipo_vehiculo_id'));
		$this->placas->setDbValue($rs->fields('placas'));
		$this->modelo->setDbValue($rs->fields('modelo'));
		$this->color->setDbValue($rs->fields('color'));
		$this->empleado_id->setDbValue($rs->fields('empleado_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->vehiculo_id->DbValue = $row['vehiculo_id'];
		$this->tipo_vehiculo_id->DbValue = $row['tipo_vehiculo_id'];
		$this->placas->DbValue = $row['placas'];
		$this->modelo->DbValue = $row['modelo'];
		$this->color->DbValue = $row['color'];
		$this->empleado_id->DbValue = $row['empleado_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("vehiculo_id")) <> "")
			$this->vehiculo_id->CurrentValue = $this->getKey("vehiculo_id"); // vehiculo_id
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
		// vehiculo_id

		$this->vehiculo_id->CellCssStyle = "white-space: nowrap;";

		// tipo_vehiculo_id
		// placas
		// modelo
		// color
		// empleado_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// tipo_vehiculo_id
		if (strval($this->tipo_vehiculo_id->CurrentValue) <> "") {
			$sFilterWrk = "`tipo_vehiculo_id`" . ew_SearchString("=", $this->tipo_vehiculo_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `tipo_vehiculo_id`, `tipo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_vehiculo`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipo_vehiculo_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipo_vehiculo_id->ViewValue = $this->tipo_vehiculo_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipo_vehiculo_id->ViewValue = $this->tipo_vehiculo_id->CurrentValue;
			}
		} else {
			$this->tipo_vehiculo_id->ViewValue = NULL;
		}
		$this->tipo_vehiculo_id->ViewCustomAttributes = "";

		// placas
		$this->placas->ViewValue = $this->placas->CurrentValue;
		$this->placas->ViewCustomAttributes = "";

		// modelo
		$this->modelo->ViewValue = $this->modelo->CurrentValue;
		$this->modelo->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

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

			// tipo_vehiculo_id
			$this->tipo_vehiculo_id->LinkCustomAttributes = "";
			$this->tipo_vehiculo_id->HrefValue = "";
			$this->tipo_vehiculo_id->TooltipValue = "";

			// placas
			$this->placas->LinkCustomAttributes = "";
			$this->placas->HrefValue = "";
			$this->placas->TooltipValue = "";

			// modelo
			$this->modelo->LinkCustomAttributes = "";
			$this->modelo->HrefValue = "";
			$this->modelo->TooltipValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";

			// empleado_id
			$this->empleado_id->LinkCustomAttributes = "";
			$this->empleado_id->HrefValue = "";
			$this->empleado_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// tipo_vehiculo_id
			$this->tipo_vehiculo_id->EditAttrs["class"] = "form-control";
			$this->tipo_vehiculo_id->EditCustomAttributes = "";
			if (trim(strval($this->tipo_vehiculo_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`tipo_vehiculo_id`" . ew_SearchString("=", $this->tipo_vehiculo_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `tipo_vehiculo_id`, `tipo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_vehiculo`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tipo_vehiculo_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tipo_vehiculo_id->EditValue = $arwrk;

			// placas
			$this->placas->EditAttrs["class"] = "form-control";
			$this->placas->EditCustomAttributes = "";
			$this->placas->EditValue = ew_HtmlEncode($this->placas->AdvancedSearch->SearchValue);
			$this->placas->PlaceHolder = ew_RemoveHtml($this->placas->FldCaption());

			// modelo
			$this->modelo->EditAttrs["class"] = "form-control";
			$this->modelo->EditCustomAttributes = "";
			$this->modelo->EditValue = ew_HtmlEncode($this->modelo->AdvancedSearch->SearchValue);
			$this->modelo->PlaceHolder = ew_RemoveHtml($this->modelo->FldCaption());

			// color
			$this->color->EditAttrs["class"] = "form-control";
			$this->color->EditCustomAttributes = "";
			$this->color->EditValue = ew_HtmlEncode($this->color->AdvancedSearch->SearchValue);
			$this->color->PlaceHolder = ew_RemoveHtml($this->color->FldCaption());

			// empleado_id
			$this->empleado_id->EditAttrs["class"] = "form-control";
			$this->empleado_id->EditCustomAttributes = "";
			if (trim(strval($this->empleado_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`empleado_id`" . ew_SearchString("=", $this->empleado_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
		$this->tipo_vehiculo_id->AdvancedSearch->Load();
		$this->placas->AdvancedSearch->Load();
		$this->modelo->AdvancedSearch->Load();
		$this->color->AdvancedSearch->Load();
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
if (!isset($vehiculo_list)) $vehiculo_list = new cvehiculo_list();

// Page init
$vehiculo_list->Page_Init();

// Page main
$vehiculo_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vehiculo_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fvehiculolist = new ew_Form("fvehiculolist", "list");
fvehiculolist.FormKeyCountName = '<?php echo $vehiculo_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvehiculolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvehiculolist.ValidateRequired = true;
<?php } else { ?>
fvehiculolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvehiculolist.Lists["x_tipo_vehiculo_id"] = {"LinkField":"x_tipo_vehiculo_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvehiculolist.Lists["x_empleado_id"] = {"LinkField":"x_empleado_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fvehiculolistsrch = new ew_Form("fvehiculolistsrch");

// Validate function for search
fvehiculolistsrch.Validate = function(fobj) {
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
fvehiculolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvehiculolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fvehiculolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fvehiculolistsrch.Lists["x_tipo_vehiculo_id"] = {"LinkField":"x_tipo_vehiculo_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvehiculolistsrch.Lists["x_empleado_id"] = {"LinkField":"x_empleado_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($vehiculo_list->TotalRecs > 0 && $vehiculo_list->ExportOptions->Visible()) { ?>
<?php $vehiculo_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($vehiculo_list->SearchOptions->Visible()) { ?>
<?php $vehiculo_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($vehiculo_list->FilterOptions->Visible()) { ?>
<?php $vehiculo_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $vehiculo_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($vehiculo_list->TotalRecs <= 0)
			$vehiculo_list->TotalRecs = $vehiculo->SelectRecordCount();
	} else {
		if (!$vehiculo_list->Recordset && ($vehiculo_list->Recordset = $vehiculo_list->LoadRecordset()))
			$vehiculo_list->TotalRecs = $vehiculo_list->Recordset->RecordCount();
	}
	$vehiculo_list->StartRec = 1;
	if ($vehiculo_list->DisplayRecs <= 0 || ($vehiculo->Export <> "" && $vehiculo->ExportAll)) // Display all records
		$vehiculo_list->DisplayRecs = $vehiculo_list->TotalRecs;
	if (!($vehiculo->Export <> "" && $vehiculo->ExportAll))
		$vehiculo_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vehiculo_list->Recordset = $vehiculo_list->LoadRecordset($vehiculo_list->StartRec-1, $vehiculo_list->DisplayRecs);

	// Set no record found message
	if ($vehiculo->CurrentAction == "" && $vehiculo_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$vehiculo_list->setWarningMessage(ew_DeniedMsg());
		if ($vehiculo_list->SearchWhere == "0=101")
			$vehiculo_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$vehiculo_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$vehiculo_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($vehiculo->Export == "" && $vehiculo->CurrentAction == "") { ?>
<form name="fvehiculolistsrch" id="fvehiculolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($vehiculo_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fvehiculolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="vehiculo">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$vehiculo_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$vehiculo->RowType = EW_ROWTYPE_SEARCH;

// Render row
$vehiculo->ResetAttrs();
$vehiculo_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($vehiculo->tipo_vehiculo_id->Visible) { // tipo_vehiculo_id ?>
	<div id="xsc_tipo_vehiculo_id" class="ewCell form-group">
		<label for="x_tipo_vehiculo_id" class="ewSearchCaption ewLabel"><?php echo $vehiculo->tipo_vehiculo_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_tipo_vehiculo_id" id="z_tipo_vehiculo_id" value="="></span>
		<span class="ewSearchField">
<select data-table="vehiculo" data-field="x_tipo_vehiculo_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($vehiculo->tipo_vehiculo_id->DisplayValueSeparator) ? json_encode($vehiculo->tipo_vehiculo_id->DisplayValueSeparator) : $vehiculo->tipo_vehiculo_id->DisplayValueSeparator) ?>" id="x_tipo_vehiculo_id" name="x_tipo_vehiculo_id"<?php echo $vehiculo->tipo_vehiculo_id->EditAttributes() ?>>
<?php
if (is_array($vehiculo->tipo_vehiculo_id->EditValue)) {
	$arwrk = $vehiculo->tipo_vehiculo_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($vehiculo->tipo_vehiculo_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $vehiculo->tipo_vehiculo_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($vehiculo->tipo_vehiculo_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($vehiculo->tipo_vehiculo_id->CurrentValue) ?>" selected><?php echo $vehiculo->tipo_vehiculo_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `tipo_vehiculo_id`, `tipo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_vehiculo`";
$sWhereWrk = "";
$vehiculo->tipo_vehiculo_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$vehiculo->tipo_vehiculo_id->LookupFilters += array("f0" => "`tipo_vehiculo_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$vehiculo->Lookup_Selecting($vehiculo->tipo_vehiculo_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $vehiculo->tipo_vehiculo_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_tipo_vehiculo_id" id="s_x_tipo_vehiculo_id" value="<?php echo $vehiculo->tipo_vehiculo_id->LookupFilterQuery() ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($vehiculo->placas->Visible) { // placas ?>
	<div id="xsc_placas" class="ewCell form-group">
		<label for="x_placas" class="ewSearchCaption ewLabel"><?php echo $vehiculo->placas->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_placas" id="z_placas" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="vehiculo" data-field="x_placas" name="x_placas" id="x_placas" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($vehiculo->placas->getPlaceHolder()) ?>" value="<?php echo $vehiculo->placas->EditValue ?>"<?php echo $vehiculo->placas->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($vehiculo->empleado_id->Visible) { // empleado_id ?>
	<div id="xsc_empleado_id" class="ewCell form-group">
		<label for="x_empleado_id" class="ewSearchCaption ewLabel"><?php echo $vehiculo->empleado_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_empleado_id" id="z_empleado_id" value="="></span>
		<span class="ewSearchField">
<select data-table="vehiculo" data-field="x_empleado_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($vehiculo->empleado_id->DisplayValueSeparator) ? json_encode($vehiculo->empleado_id->DisplayValueSeparator) : $vehiculo->empleado_id->DisplayValueSeparator) ?>" id="x_empleado_id" name="x_empleado_id"<?php echo $vehiculo->empleado_id->EditAttributes() ?>>
<?php
if (is_array($vehiculo->empleado_id->EditValue)) {
	$arwrk = $vehiculo->empleado_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($vehiculo->empleado_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $vehiculo->empleado_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($vehiculo->empleado_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($vehiculo->empleado_id->CurrentValue) ?>" selected><?php echo $vehiculo->empleado_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `empleado_id`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
$sWhereWrk = "";
$vehiculo->empleado_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$vehiculo->empleado_id->LookupFilters += array("f0" => "`empleado_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$vehiculo->Lookup_Selecting($vehiculo->empleado_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $vehiculo->empleado_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_empleado_id" id="s_x_empleado_id" value="<?php echo $vehiculo->empleado_id->LookupFilterQuery() ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $vehiculo_list->ShowPageHeader(); ?>
<?php
$vehiculo_list->ShowMessage();
?>
<?php if ($vehiculo_list->TotalRecs > 0 || $vehiculo->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fvehiculolist" id="fvehiculolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vehiculo_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vehiculo_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vehiculo">
<div id="gmp_vehiculo" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($vehiculo_list->TotalRecs > 0) { ?>
<table id="tbl_vehiculolist" class="table ewTable">
<?php echo $vehiculo->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$vehiculo_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$vehiculo_list->RenderListOptions();

// Render list options (header, left)
$vehiculo_list->ListOptions->Render("header", "left");
?>
<?php if ($vehiculo->tipo_vehiculo_id->Visible) { // tipo_vehiculo_id ?>
	<?php if ($vehiculo->SortUrl($vehiculo->tipo_vehiculo_id) == "") { ?>
		<th data-name="tipo_vehiculo_id"><div id="elh_vehiculo_tipo_vehiculo_id" class="vehiculo_tipo_vehiculo_id"><div class="ewTableHeaderCaption"><?php echo $vehiculo->tipo_vehiculo_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_vehiculo_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vehiculo->SortUrl($vehiculo->tipo_vehiculo_id) ?>',1);"><div id="elh_vehiculo_tipo_vehiculo_id" class="vehiculo_tipo_vehiculo_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculo->tipo_vehiculo_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculo->tipo_vehiculo_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculo->tipo_vehiculo_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vehiculo->placas->Visible) { // placas ?>
	<?php if ($vehiculo->SortUrl($vehiculo->placas) == "") { ?>
		<th data-name="placas"><div id="elh_vehiculo_placas" class="vehiculo_placas"><div class="ewTableHeaderCaption"><?php echo $vehiculo->placas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="placas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vehiculo->SortUrl($vehiculo->placas) ?>',1);"><div id="elh_vehiculo_placas" class="vehiculo_placas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculo->placas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculo->placas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculo->placas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vehiculo->modelo->Visible) { // modelo ?>
	<?php if ($vehiculo->SortUrl($vehiculo->modelo) == "") { ?>
		<th data-name="modelo"><div id="elh_vehiculo_modelo" class="vehiculo_modelo"><div class="ewTableHeaderCaption"><?php echo $vehiculo->modelo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modelo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vehiculo->SortUrl($vehiculo->modelo) ?>',1);"><div id="elh_vehiculo_modelo" class="vehiculo_modelo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculo->modelo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculo->modelo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculo->modelo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vehiculo->color->Visible) { // color ?>
	<?php if ($vehiculo->SortUrl($vehiculo->color) == "") { ?>
		<th data-name="color"><div id="elh_vehiculo_color" class="vehiculo_color"><div class="ewTableHeaderCaption"><?php echo $vehiculo->color->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="color"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vehiculo->SortUrl($vehiculo->color) ?>',1);"><div id="elh_vehiculo_color" class="vehiculo_color">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculo->color->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculo->color->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculo->color->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vehiculo->empleado_id->Visible) { // empleado_id ?>
	<?php if ($vehiculo->SortUrl($vehiculo->empleado_id) == "") { ?>
		<th data-name="empleado_id"><div id="elh_vehiculo_empleado_id" class="vehiculo_empleado_id"><div class="ewTableHeaderCaption"><?php echo $vehiculo->empleado_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="empleado_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vehiculo->SortUrl($vehiculo->empleado_id) ?>',1);"><div id="elh_vehiculo_empleado_id" class="vehiculo_empleado_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculo->empleado_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculo->empleado_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculo->empleado_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vehiculo_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vehiculo->ExportAll && $vehiculo->Export <> "") {
	$vehiculo_list->StopRec = $vehiculo_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vehiculo_list->TotalRecs > $vehiculo_list->StartRec + $vehiculo_list->DisplayRecs - 1)
		$vehiculo_list->StopRec = $vehiculo_list->StartRec + $vehiculo_list->DisplayRecs - 1;
	else
		$vehiculo_list->StopRec = $vehiculo_list->TotalRecs;
}
$vehiculo_list->RecCnt = $vehiculo_list->StartRec - 1;
if ($vehiculo_list->Recordset && !$vehiculo_list->Recordset->EOF) {
	$vehiculo_list->Recordset->MoveFirst();
	$bSelectLimit = $vehiculo_list->UseSelectLimit;
	if (!$bSelectLimit && $vehiculo_list->StartRec > 1)
		$vehiculo_list->Recordset->Move($vehiculo_list->StartRec - 1);
} elseif (!$vehiculo->AllowAddDeleteRow && $vehiculo_list->StopRec == 0) {
	$vehiculo_list->StopRec = $vehiculo->GridAddRowCount;
}

// Initialize aggregate
$vehiculo->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vehiculo->ResetAttrs();
$vehiculo_list->RenderRow();
while ($vehiculo_list->RecCnt < $vehiculo_list->StopRec) {
	$vehiculo_list->RecCnt++;
	if (intval($vehiculo_list->RecCnt) >= intval($vehiculo_list->StartRec)) {
		$vehiculo_list->RowCnt++;

		// Set up key count
		$vehiculo_list->KeyCount = $vehiculo_list->RowIndex;

		// Init row class and style
		$vehiculo->ResetAttrs();
		$vehiculo->CssClass = "";
		if ($vehiculo->CurrentAction == "gridadd") {
		} else {
			$vehiculo_list->LoadRowValues($vehiculo_list->Recordset); // Load row values
		}
		$vehiculo->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vehiculo->RowAttrs = array_merge($vehiculo->RowAttrs, array('data-rowindex'=>$vehiculo_list->RowCnt, 'id'=>'r' . $vehiculo_list->RowCnt . '_vehiculo', 'data-rowtype'=>$vehiculo->RowType));

		// Render row
		$vehiculo_list->RenderRow();

		// Render list options
		$vehiculo_list->RenderListOptions();
?>
	<tr<?php echo $vehiculo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vehiculo_list->ListOptions->Render("body", "left", $vehiculo_list->RowCnt);
?>
	<?php if ($vehiculo->tipo_vehiculo_id->Visible) { // tipo_vehiculo_id ?>
		<td data-name="tipo_vehiculo_id"<?php echo $vehiculo->tipo_vehiculo_id->CellAttributes() ?>>
<span id="el<?php echo $vehiculo_list->RowCnt ?>_vehiculo_tipo_vehiculo_id" class="vehiculo_tipo_vehiculo_id">
<span<?php echo $vehiculo->tipo_vehiculo_id->ViewAttributes() ?>>
<?php echo $vehiculo->tipo_vehiculo_id->ListViewValue() ?></span>
</span>
<a id="<?php echo $vehiculo_list->PageObjName . "_row_" . $vehiculo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vehiculo->placas->Visible) { // placas ?>
		<td data-name="placas"<?php echo $vehiculo->placas->CellAttributes() ?>>
<span id="el<?php echo $vehiculo_list->RowCnt ?>_vehiculo_placas" class="vehiculo_placas">
<span<?php echo $vehiculo->placas->ViewAttributes() ?>>
<?php echo $vehiculo->placas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vehiculo->modelo->Visible) { // modelo ?>
		<td data-name="modelo"<?php echo $vehiculo->modelo->CellAttributes() ?>>
<span id="el<?php echo $vehiculo_list->RowCnt ?>_vehiculo_modelo" class="vehiculo_modelo">
<span<?php echo $vehiculo->modelo->ViewAttributes() ?>>
<?php echo $vehiculo->modelo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vehiculo->color->Visible) { // color ?>
		<td data-name="color"<?php echo $vehiculo->color->CellAttributes() ?>>
<span id="el<?php echo $vehiculo_list->RowCnt ?>_vehiculo_color" class="vehiculo_color">
<span<?php echo $vehiculo->color->ViewAttributes() ?>>
<?php echo $vehiculo->color->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vehiculo->empleado_id->Visible) { // empleado_id ?>
		<td data-name="empleado_id"<?php echo $vehiculo->empleado_id->CellAttributes() ?>>
<span id="el<?php echo $vehiculo_list->RowCnt ?>_vehiculo_empleado_id" class="vehiculo_empleado_id">
<span<?php echo $vehiculo->empleado_id->ViewAttributes() ?>>
<?php echo $vehiculo->empleado_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vehiculo_list->ListOptions->Render("body", "right", $vehiculo_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vehiculo->CurrentAction <> "gridadd")
		$vehiculo_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vehiculo->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vehiculo_list->Recordset)
	$vehiculo_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($vehiculo->CurrentAction <> "gridadd" && $vehiculo->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($vehiculo_list->Pager)) $vehiculo_list->Pager = new cPrevNextPager($vehiculo_list->StartRec, $vehiculo_list->DisplayRecs, $vehiculo_list->TotalRecs) ?>
<?php if ($vehiculo_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($vehiculo_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $vehiculo_list->PageUrl() ?>start=<?php echo $vehiculo_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($vehiculo_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $vehiculo_list->PageUrl() ?>start=<?php echo $vehiculo_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $vehiculo_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($vehiculo_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $vehiculo_list->PageUrl() ?>start=<?php echo $vehiculo_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($vehiculo_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $vehiculo_list->PageUrl() ?>start=<?php echo $vehiculo_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $vehiculo_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vehiculo_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vehiculo_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vehiculo_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vehiculo_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($vehiculo_list->TotalRecs == 0 && $vehiculo->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vehiculo_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fvehiculolistsrch.Init();
fvehiculolistsrch.FilterList = <?php echo $vehiculo_list->GetFilterList() ?>;
fvehiculolist.Init();
</script>
<?php
$vehiculo_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vehiculo_list->Page_Terminate();
?>
