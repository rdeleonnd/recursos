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

$historial_laboral_list = NULL; // Initialize page object first

class chistorial_laboral_list extends chistorial_laboral {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'historial_laboral';

	// Page object name
	var $PageObjName = 'historial_laboral_list';

	// Grid form hidden field names
	var $FormName = 'fhistorial_laborallist';
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

		// Table object (historial_laboral)
		if (!isset($GLOBALS["historial_laboral"]) || get_class($GLOBALS["historial_laboral"]) == "chistorial_laboral") {
			$GLOBALS["historial_laboral"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["historial_laboral"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "historial_laboraladd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "historial_laboraldelete.php";
		$this->MultiUpdateUrl = "historial_laboralupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fhistorial_laborallistsrch";

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
			$this->historial_laboral_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->historial_laboral_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->institucion->AdvancedSearch->ToJSON(), ","); // Field institucion
		$sFilterList = ew_Concat($sFilterList, $this->direccion->AdvancedSearch->ToJSON(), ","); // Field direccion
		$sFilterList = ew_Concat($sFilterList, $this->telefono->AdvancedSearch->ToJSON(), ","); // Field telefono
		$sFilterList = ew_Concat($sFilterList, $this->puesto->AdvancedSearch->ToJSON(), ","); // Field puesto
		$sFilterList = ew_Concat($sFilterList, $this->atribuciones->AdvancedSearch->ToJSON(), ","); // Field atribuciones
		$sFilterList = ew_Concat($sFilterList, $this->jefe->AdvancedSearch->ToJSON(), ","); // Field jefe
		$sFilterList = ew_Concat($sFilterList, $this->fecha_ingreso->AdvancedSearch->ToJSON(), ","); // Field fecha_ingreso
		$sFilterList = ew_Concat($sFilterList, $this->fecha_egreso->AdvancedSearch->ToJSON(), ","); // Field fecha_egreso
		$sFilterList = ew_Concat($sFilterList, $this->sueldo_inicial->AdvancedSearch->ToJSON(), ","); // Field sueldo_inicial
		$sFilterList = ew_Concat($sFilterList, $this->sueldo_final->AdvancedSearch->ToJSON(), ","); // Field sueldo_final
		$sFilterList = ew_Concat($sFilterList, $this->motivo_retiro->AdvancedSearch->ToJSON(), ","); // Field motivo_retiro
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

		// Field institucion
		$this->institucion->AdvancedSearch->SearchValue = @$filter["x_institucion"];
		$this->institucion->AdvancedSearch->SearchOperator = @$filter["z_institucion"];
		$this->institucion->AdvancedSearch->SearchCondition = @$filter["v_institucion"];
		$this->institucion->AdvancedSearch->SearchValue2 = @$filter["y_institucion"];
		$this->institucion->AdvancedSearch->SearchOperator2 = @$filter["w_institucion"];
		$this->institucion->AdvancedSearch->Save();

		// Field direccion
		$this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
		$this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
		$this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
		$this->direccion->AdvancedSearch->Save();

		// Field telefono
		$this->telefono->AdvancedSearch->SearchValue = @$filter["x_telefono"];
		$this->telefono->AdvancedSearch->SearchOperator = @$filter["z_telefono"];
		$this->telefono->AdvancedSearch->SearchCondition = @$filter["v_telefono"];
		$this->telefono->AdvancedSearch->SearchValue2 = @$filter["y_telefono"];
		$this->telefono->AdvancedSearch->SearchOperator2 = @$filter["w_telefono"];
		$this->telefono->AdvancedSearch->Save();

		// Field puesto
		$this->puesto->AdvancedSearch->SearchValue = @$filter["x_puesto"];
		$this->puesto->AdvancedSearch->SearchOperator = @$filter["z_puesto"];
		$this->puesto->AdvancedSearch->SearchCondition = @$filter["v_puesto"];
		$this->puesto->AdvancedSearch->SearchValue2 = @$filter["y_puesto"];
		$this->puesto->AdvancedSearch->SearchOperator2 = @$filter["w_puesto"];
		$this->puesto->AdvancedSearch->Save();

		// Field atribuciones
		$this->atribuciones->AdvancedSearch->SearchValue = @$filter["x_atribuciones"];
		$this->atribuciones->AdvancedSearch->SearchOperator = @$filter["z_atribuciones"];
		$this->atribuciones->AdvancedSearch->SearchCondition = @$filter["v_atribuciones"];
		$this->atribuciones->AdvancedSearch->SearchValue2 = @$filter["y_atribuciones"];
		$this->atribuciones->AdvancedSearch->SearchOperator2 = @$filter["w_atribuciones"];
		$this->atribuciones->AdvancedSearch->Save();

		// Field jefe
		$this->jefe->AdvancedSearch->SearchValue = @$filter["x_jefe"];
		$this->jefe->AdvancedSearch->SearchOperator = @$filter["z_jefe"];
		$this->jefe->AdvancedSearch->SearchCondition = @$filter["v_jefe"];
		$this->jefe->AdvancedSearch->SearchValue2 = @$filter["y_jefe"];
		$this->jefe->AdvancedSearch->SearchOperator2 = @$filter["w_jefe"];
		$this->jefe->AdvancedSearch->Save();

		// Field fecha_ingreso
		$this->fecha_ingreso->AdvancedSearch->SearchValue = @$filter["x_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->SearchOperator = @$filter["z_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->SearchCondition = @$filter["v_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->SearchValue2 = @$filter["y_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->Save();

		// Field fecha_egreso
		$this->fecha_egreso->AdvancedSearch->SearchValue = @$filter["x_fecha_egreso"];
		$this->fecha_egreso->AdvancedSearch->SearchOperator = @$filter["z_fecha_egreso"];
		$this->fecha_egreso->AdvancedSearch->SearchCondition = @$filter["v_fecha_egreso"];
		$this->fecha_egreso->AdvancedSearch->SearchValue2 = @$filter["y_fecha_egreso"];
		$this->fecha_egreso->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_egreso"];
		$this->fecha_egreso->AdvancedSearch->Save();

		// Field sueldo_inicial
		$this->sueldo_inicial->AdvancedSearch->SearchValue = @$filter["x_sueldo_inicial"];
		$this->sueldo_inicial->AdvancedSearch->SearchOperator = @$filter["z_sueldo_inicial"];
		$this->sueldo_inicial->AdvancedSearch->SearchCondition = @$filter["v_sueldo_inicial"];
		$this->sueldo_inicial->AdvancedSearch->SearchValue2 = @$filter["y_sueldo_inicial"];
		$this->sueldo_inicial->AdvancedSearch->SearchOperator2 = @$filter["w_sueldo_inicial"];
		$this->sueldo_inicial->AdvancedSearch->Save();

		// Field sueldo_final
		$this->sueldo_final->AdvancedSearch->SearchValue = @$filter["x_sueldo_final"];
		$this->sueldo_final->AdvancedSearch->SearchOperator = @$filter["z_sueldo_final"];
		$this->sueldo_final->AdvancedSearch->SearchCondition = @$filter["v_sueldo_final"];
		$this->sueldo_final->AdvancedSearch->SearchValue2 = @$filter["y_sueldo_final"];
		$this->sueldo_final->AdvancedSearch->SearchOperator2 = @$filter["w_sueldo_final"];
		$this->sueldo_final->AdvancedSearch->Save();

		// Field motivo_retiro
		$this->motivo_retiro->AdvancedSearch->SearchValue = @$filter["x_motivo_retiro"];
		$this->motivo_retiro->AdvancedSearch->SearchOperator = @$filter["z_motivo_retiro"];
		$this->motivo_retiro->AdvancedSearch->SearchCondition = @$filter["v_motivo_retiro"];
		$this->motivo_retiro->AdvancedSearch->SearchValue2 = @$filter["y_motivo_retiro"];
		$this->motivo_retiro->AdvancedSearch->SearchOperator2 = @$filter["w_motivo_retiro"];
		$this->motivo_retiro->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->institucion, $Default, FALSE); // institucion
		$this->BuildSearchSql($sWhere, $this->direccion, $Default, FALSE); // direccion
		$this->BuildSearchSql($sWhere, $this->telefono, $Default, FALSE); // telefono
		$this->BuildSearchSql($sWhere, $this->puesto, $Default, FALSE); // puesto
		$this->BuildSearchSql($sWhere, $this->atribuciones, $Default, FALSE); // atribuciones
		$this->BuildSearchSql($sWhere, $this->jefe, $Default, FALSE); // jefe
		$this->BuildSearchSql($sWhere, $this->fecha_ingreso, $Default, FALSE); // fecha_ingreso
		$this->BuildSearchSql($sWhere, $this->fecha_egreso, $Default, FALSE); // fecha_egreso
		$this->BuildSearchSql($sWhere, $this->sueldo_inicial, $Default, FALSE); // sueldo_inicial
		$this->BuildSearchSql($sWhere, $this->sueldo_final, $Default, FALSE); // sueldo_final
		$this->BuildSearchSql($sWhere, $this->motivo_retiro, $Default, FALSE); // motivo_retiro
		$this->BuildSearchSql($sWhere, $this->empleado_id, $Default, FALSE); // empleado_id

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->institucion->AdvancedSearch->Save(); // institucion
			$this->direccion->AdvancedSearch->Save(); // direccion
			$this->telefono->AdvancedSearch->Save(); // telefono
			$this->puesto->AdvancedSearch->Save(); // puesto
			$this->atribuciones->AdvancedSearch->Save(); // atribuciones
			$this->jefe->AdvancedSearch->Save(); // jefe
			$this->fecha_ingreso->AdvancedSearch->Save(); // fecha_ingreso
			$this->fecha_egreso->AdvancedSearch->Save(); // fecha_egreso
			$this->sueldo_inicial->AdvancedSearch->Save(); // sueldo_inicial
			$this->sueldo_final->AdvancedSearch->Save(); // sueldo_final
			$this->motivo_retiro->AdvancedSearch->Save(); // motivo_retiro
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
		if ($this->institucion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->telefono->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->puesto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->atribuciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->jefe->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_ingreso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_egreso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sueldo_inicial->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sueldo_final->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->motivo_retiro->AdvancedSearch->IssetSession())
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
		$this->institucion->AdvancedSearch->UnsetSession();
		$this->direccion->AdvancedSearch->UnsetSession();
		$this->telefono->AdvancedSearch->UnsetSession();
		$this->puesto->AdvancedSearch->UnsetSession();
		$this->atribuciones->AdvancedSearch->UnsetSession();
		$this->jefe->AdvancedSearch->UnsetSession();
		$this->fecha_ingreso->AdvancedSearch->UnsetSession();
		$this->fecha_egreso->AdvancedSearch->UnsetSession();
		$this->sueldo_inicial->AdvancedSearch->UnsetSession();
		$this->sueldo_final->AdvancedSearch->UnsetSession();
		$this->motivo_retiro->AdvancedSearch->UnsetSession();
		$this->empleado_id->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->institucion->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->telefono->AdvancedSearch->Load();
		$this->puesto->AdvancedSearch->Load();
		$this->atribuciones->AdvancedSearch->Load();
		$this->jefe->AdvancedSearch->Load();
		$this->fecha_ingreso->AdvancedSearch->Load();
		$this->fecha_egreso->AdvancedSearch->Load();
		$this->sueldo_inicial->AdvancedSearch->Load();
		$this->sueldo_final->AdvancedSearch->Load();
		$this->motivo_retiro->AdvancedSearch->Load();
		$this->empleado_id->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->institucion); // institucion
			$this->UpdateSort($this->direccion); // direccion
			$this->UpdateSort($this->telefono); // telefono
			$this->UpdateSort($this->puesto); // puesto
			$this->UpdateSort($this->atribuciones); // atribuciones
			$this->UpdateSort($this->jefe); // jefe
			$this->UpdateSort($this->fecha_ingreso); // fecha_ingreso
			$this->UpdateSort($this->fecha_egreso); // fecha_egreso
			$this->UpdateSort($this->sueldo_inicial); // sueldo_inicial
			$this->UpdateSort($this->sueldo_final); // sueldo_final
			$this->UpdateSort($this->motivo_retiro); // motivo_retiro
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
				$this->institucion->setSort("");
				$this->direccion->setSort("");
				$this->telefono->setSort("");
				$this->puesto->setSort("");
				$this->atribuciones->setSort("");
				$this->jefe->setSort("");
				$this->fecha_ingreso->setSort("");
				$this->fecha_egreso->setSort("");
				$this->sueldo_inicial->setSort("");
				$this->sueldo_final->setSort("");
				$this->motivo_retiro->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->historial_laboral_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fhistorial_laborallistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fhistorial_laborallistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fhistorial_laborallist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fhistorial_laborallistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// institucion

		$this->institucion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_institucion"]);
		if ($this->institucion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->institucion->AdvancedSearch->SearchOperator = @$_GET["z_institucion"];

		// direccion
		$this->direccion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_direccion"]);
		if ($this->direccion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->direccion->AdvancedSearch->SearchOperator = @$_GET["z_direccion"];

		// telefono
		$this->telefono->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telefono"]);
		if ($this->telefono->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->telefono->AdvancedSearch->SearchOperator = @$_GET["z_telefono"];

		// puesto
		$this->puesto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_puesto"]);
		if ($this->puesto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->puesto->AdvancedSearch->SearchOperator = @$_GET["z_puesto"];

		// atribuciones
		$this->atribuciones->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_atribuciones"]);
		if ($this->atribuciones->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->atribuciones->AdvancedSearch->SearchOperator = @$_GET["z_atribuciones"];

		// jefe
		$this->jefe->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_jefe"]);
		if ($this->jefe->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->jefe->AdvancedSearch->SearchOperator = @$_GET["z_jefe"];

		// fecha_ingreso
		$this->fecha_ingreso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_ingreso"]);
		if ($this->fecha_ingreso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_ingreso->AdvancedSearch->SearchOperator = @$_GET["z_fecha_ingreso"];

		// fecha_egreso
		$this->fecha_egreso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_egreso"]);
		if ($this->fecha_egreso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_egreso->AdvancedSearch->SearchOperator = @$_GET["z_fecha_egreso"];

		// sueldo_inicial
		$this->sueldo_inicial->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_sueldo_inicial"]);
		if ($this->sueldo_inicial->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->sueldo_inicial->AdvancedSearch->SearchOperator = @$_GET["z_sueldo_inicial"];

		// sueldo_final
		$this->sueldo_final->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_sueldo_final"]);
		if ($this->sueldo_final->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->sueldo_final->AdvancedSearch->SearchOperator = @$_GET["z_sueldo_final"];

		// motivo_retiro
		$this->motivo_retiro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_motivo_retiro"]);
		if ($this->motivo_retiro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->motivo_retiro->AdvancedSearch->SearchOperator = @$_GET["z_motivo_retiro"];

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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// institucion
			$this->institucion->EditAttrs["class"] = "form-control";
			$this->institucion->EditCustomAttributes = "";
			$this->institucion->EditValue = ew_HtmlEncode($this->institucion->AdvancedSearch->SearchValue);
			$this->institucion->PlaceHolder = ew_RemoveHtml($this->institucion->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->AdvancedSearch->SearchValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->AdvancedSearch->SearchValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// puesto
			$this->puesto->EditAttrs["class"] = "form-control";
			$this->puesto->EditCustomAttributes = "";
			$this->puesto->EditValue = ew_HtmlEncode($this->puesto->AdvancedSearch->SearchValue);
			$this->puesto->PlaceHolder = ew_RemoveHtml($this->puesto->FldCaption());

			// atribuciones
			$this->atribuciones->EditAttrs["class"] = "form-control";
			$this->atribuciones->EditCustomAttributes = "";
			$this->atribuciones->EditValue = ew_HtmlEncode($this->atribuciones->AdvancedSearch->SearchValue);
			$this->atribuciones->PlaceHolder = ew_RemoveHtml($this->atribuciones->FldCaption());

			// jefe
			$this->jefe->EditAttrs["class"] = "form-control";
			$this->jefe->EditCustomAttributes = "";
			$this->jefe->EditValue = ew_HtmlEncode($this->jefe->AdvancedSearch->SearchValue);
			$this->jefe->PlaceHolder = ew_RemoveHtml($this->jefe->FldCaption());

			// fecha_ingreso
			$this->fecha_ingreso->EditAttrs["class"] = "form-control";
			$this->fecha_ingreso->EditCustomAttributes = "";
			$this->fecha_ingreso->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_ingreso->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_ingreso->PlaceHolder = ew_RemoveHtml($this->fecha_ingreso->FldCaption());

			// fecha_egreso
			$this->fecha_egreso->EditAttrs["class"] = "form-control";
			$this->fecha_egreso->EditCustomAttributes = "";
			$this->fecha_egreso->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_egreso->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_egreso->PlaceHolder = ew_RemoveHtml($this->fecha_egreso->FldCaption());

			// sueldo_inicial
			$this->sueldo_inicial->EditAttrs["class"] = "form-control";
			$this->sueldo_inicial->EditCustomAttributes = "";
			$this->sueldo_inicial->EditValue = ew_HtmlEncode($this->sueldo_inicial->AdvancedSearch->SearchValue);
			$this->sueldo_inicial->PlaceHolder = ew_RemoveHtml($this->sueldo_inicial->FldCaption());

			// sueldo_final
			$this->sueldo_final->EditAttrs["class"] = "form-control";
			$this->sueldo_final->EditCustomAttributes = "";
			$this->sueldo_final->EditValue = ew_HtmlEncode($this->sueldo_final->AdvancedSearch->SearchValue);
			$this->sueldo_final->PlaceHolder = ew_RemoveHtml($this->sueldo_final->FldCaption());

			// motivo_retiro
			$this->motivo_retiro->EditAttrs["class"] = "form-control";
			$this->motivo_retiro->EditCustomAttributes = "";
			$this->motivo_retiro->EditValue = ew_HtmlEncode($this->motivo_retiro->AdvancedSearch->SearchValue);
			$this->motivo_retiro->PlaceHolder = ew_RemoveHtml($this->motivo_retiro->FldCaption());

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
		$this->institucion->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->telefono->AdvancedSearch->Load();
		$this->puesto->AdvancedSearch->Load();
		$this->atribuciones->AdvancedSearch->Load();
		$this->jefe->AdvancedSearch->Load();
		$this->fecha_ingreso->AdvancedSearch->Load();
		$this->fecha_egreso->AdvancedSearch->Load();
		$this->sueldo_inicial->AdvancedSearch->Load();
		$this->sueldo_final->AdvancedSearch->Load();
		$this->motivo_retiro->AdvancedSearch->Load();
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
if (!isset($historial_laboral_list)) $historial_laboral_list = new chistorial_laboral_list();

// Page init
$historial_laboral_list->Page_Init();

// Page main
$historial_laboral_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$historial_laboral_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fhistorial_laborallist = new ew_Form("fhistorial_laborallist", "list");
fhistorial_laborallist.FormKeyCountName = '<?php echo $historial_laboral_list->FormKeyCountName ?>';

// Form_CustomValidate event
fhistorial_laborallist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhistorial_laborallist.ValidateRequired = true;
<?php } else { ?>
fhistorial_laborallist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhistorial_laborallist.Lists["x_empleado_id"] = {"LinkField":"x_empleado_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fhistorial_laborallistsrch = new ew_Form("fhistorial_laborallistsrch");

// Validate function for search
fhistorial_laborallistsrch.Validate = function(fobj) {
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
fhistorial_laborallistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhistorial_laborallistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fhistorial_laborallistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fhistorial_laborallistsrch.Lists["x_empleado_id"] = {"LinkField":"x_empleado_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($historial_laboral_list->TotalRecs > 0 && $historial_laboral_list->ExportOptions->Visible()) { ?>
<?php $historial_laboral_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($historial_laboral_list->SearchOptions->Visible()) { ?>
<?php $historial_laboral_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($historial_laboral_list->FilterOptions->Visible()) { ?>
<?php $historial_laboral_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $historial_laboral_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($historial_laboral_list->TotalRecs <= 0)
			$historial_laboral_list->TotalRecs = $historial_laboral->SelectRecordCount();
	} else {
		if (!$historial_laboral_list->Recordset && ($historial_laboral_list->Recordset = $historial_laboral_list->LoadRecordset()))
			$historial_laboral_list->TotalRecs = $historial_laboral_list->Recordset->RecordCount();
	}
	$historial_laboral_list->StartRec = 1;
	if ($historial_laboral_list->DisplayRecs <= 0 || ($historial_laboral->Export <> "" && $historial_laboral->ExportAll)) // Display all records
		$historial_laboral_list->DisplayRecs = $historial_laboral_list->TotalRecs;
	if (!($historial_laboral->Export <> "" && $historial_laboral->ExportAll))
		$historial_laboral_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$historial_laboral_list->Recordset = $historial_laboral_list->LoadRecordset($historial_laboral_list->StartRec-1, $historial_laboral_list->DisplayRecs);

	// Set no record found message
	if ($historial_laboral->CurrentAction == "" && $historial_laboral_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$historial_laboral_list->setWarningMessage(ew_DeniedMsg());
		if ($historial_laboral_list->SearchWhere == "0=101")
			$historial_laboral_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$historial_laboral_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$historial_laboral_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($historial_laboral->Export == "" && $historial_laboral->CurrentAction == "") { ?>
<form name="fhistorial_laborallistsrch" id="fhistorial_laborallistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($historial_laboral_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fhistorial_laborallistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="historial_laboral">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$historial_laboral_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$historial_laboral->RowType = EW_ROWTYPE_SEARCH;

// Render row
$historial_laboral->ResetAttrs();
$historial_laboral_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($historial_laboral->empleado_id->Visible) { // empleado_id ?>
	<div id="xsc_empleado_id" class="ewCell form-group">
		<label for="x_empleado_id" class="ewSearchCaption ewLabel"><?php echo $historial_laboral->empleado_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_empleado_id" id="z_empleado_id" value="="></span>
		<span class="ewSearchField">
<select data-table="historial_laboral" data-field="x_empleado_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($historial_laboral->empleado_id->DisplayValueSeparator) ? json_encode($historial_laboral->empleado_id->DisplayValueSeparator) : $historial_laboral->empleado_id->DisplayValueSeparator) ?>" id="x_empleado_id" name="x_empleado_id"<?php echo $historial_laboral->empleado_id->EditAttributes() ?>>
<?php
if (is_array($historial_laboral->empleado_id->EditValue)) {
	$arwrk = $historial_laboral->empleado_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($historial_laboral->empleado_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
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
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $historial_laboral_list->ShowPageHeader(); ?>
<?php
$historial_laboral_list->ShowMessage();
?>
<?php if ($historial_laboral_list->TotalRecs > 0 || $historial_laboral->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fhistorial_laborallist" id="fhistorial_laborallist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($historial_laboral_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $historial_laboral_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="historial_laboral">
<div id="gmp_historial_laboral" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($historial_laboral_list->TotalRecs > 0) { ?>
<table id="tbl_historial_laborallist" class="table ewTable">
<?php echo $historial_laboral->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$historial_laboral_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$historial_laboral_list->RenderListOptions();

// Render list options (header, left)
$historial_laboral_list->ListOptions->Render("header", "left");
?>
<?php if ($historial_laboral->institucion->Visible) { // institucion ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->institucion) == "") { ?>
		<th data-name="institucion"><div id="elh_historial_laboral_institucion" class="historial_laboral_institucion"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->institucion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="institucion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->institucion) ?>',1);"><div id="elh_historial_laboral_institucion" class="historial_laboral_institucion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->institucion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->institucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->institucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->direccion->Visible) { // direccion ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->direccion) == "") { ?>
		<th data-name="direccion"><div id="elh_historial_laboral_direccion" class="historial_laboral_direccion"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->direccion) ?>',1);"><div id="elh_historial_laboral_direccion" class="historial_laboral_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->telefono->Visible) { // telefono ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->telefono) == "") { ?>
		<th data-name="telefono"><div id="elh_historial_laboral_telefono" class="historial_laboral_telefono"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->telefono) ?>',1);"><div id="elh_historial_laboral_telefono" class="historial_laboral_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->telefono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->puesto->Visible) { // puesto ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->puesto) == "") { ?>
		<th data-name="puesto"><div id="elh_historial_laboral_puesto" class="historial_laboral_puesto"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->puesto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="puesto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->puesto) ?>',1);"><div id="elh_historial_laboral_puesto" class="historial_laboral_puesto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->puesto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->puesto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->puesto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->atribuciones->Visible) { // atribuciones ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->atribuciones) == "") { ?>
		<th data-name="atribuciones"><div id="elh_historial_laboral_atribuciones" class="historial_laboral_atribuciones"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->atribuciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="atribuciones"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->atribuciones) ?>',1);"><div id="elh_historial_laboral_atribuciones" class="historial_laboral_atribuciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->atribuciones->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->atribuciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->atribuciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->jefe->Visible) { // jefe ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->jefe) == "") { ?>
		<th data-name="jefe"><div id="elh_historial_laboral_jefe" class="historial_laboral_jefe"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->jefe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jefe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->jefe) ?>',1);"><div id="elh_historial_laboral_jefe" class="historial_laboral_jefe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->jefe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->jefe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->jefe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->fecha_ingreso->Visible) { // fecha_ingreso ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->fecha_ingreso) == "") { ?>
		<th data-name="fecha_ingreso"><div id="elh_historial_laboral_fecha_ingreso" class="historial_laboral_fecha_ingreso"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->fecha_ingreso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_ingreso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->fecha_ingreso) ?>',1);"><div id="elh_historial_laboral_fecha_ingreso" class="historial_laboral_fecha_ingreso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->fecha_ingreso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->fecha_ingreso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->fecha_ingreso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->fecha_egreso->Visible) { // fecha_egreso ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->fecha_egreso) == "") { ?>
		<th data-name="fecha_egreso"><div id="elh_historial_laboral_fecha_egreso" class="historial_laboral_fecha_egreso"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->fecha_egreso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_egreso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->fecha_egreso) ?>',1);"><div id="elh_historial_laboral_fecha_egreso" class="historial_laboral_fecha_egreso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->fecha_egreso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->fecha_egreso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->fecha_egreso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->sueldo_inicial->Visible) { // sueldo_inicial ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->sueldo_inicial) == "") { ?>
		<th data-name="sueldo_inicial"><div id="elh_historial_laboral_sueldo_inicial" class="historial_laboral_sueldo_inicial"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->sueldo_inicial->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sueldo_inicial"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->sueldo_inicial) ?>',1);"><div id="elh_historial_laboral_sueldo_inicial" class="historial_laboral_sueldo_inicial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->sueldo_inicial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->sueldo_inicial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->sueldo_inicial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->sueldo_final->Visible) { // sueldo_final ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->sueldo_final) == "") { ?>
		<th data-name="sueldo_final"><div id="elh_historial_laboral_sueldo_final" class="historial_laboral_sueldo_final"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->sueldo_final->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sueldo_final"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->sueldo_final) ?>',1);"><div id="elh_historial_laboral_sueldo_final" class="historial_laboral_sueldo_final">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->sueldo_final->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->sueldo_final->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->sueldo_final->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->motivo_retiro->Visible) { // motivo_retiro ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->motivo_retiro) == "") { ?>
		<th data-name="motivo_retiro"><div id="elh_historial_laboral_motivo_retiro" class="historial_laboral_motivo_retiro"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->motivo_retiro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="motivo_retiro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->motivo_retiro) ?>',1);"><div id="elh_historial_laboral_motivo_retiro" class="historial_laboral_motivo_retiro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->motivo_retiro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->motivo_retiro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->motivo_retiro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($historial_laboral->empleado_id->Visible) { // empleado_id ?>
	<?php if ($historial_laboral->SortUrl($historial_laboral->empleado_id) == "") { ?>
		<th data-name="empleado_id"><div id="elh_historial_laboral_empleado_id" class="historial_laboral_empleado_id"><div class="ewTableHeaderCaption"><?php echo $historial_laboral->empleado_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="empleado_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $historial_laboral->SortUrl($historial_laboral->empleado_id) ?>',1);"><div id="elh_historial_laboral_empleado_id" class="historial_laboral_empleado_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial_laboral->empleado_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial_laboral->empleado_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial_laboral->empleado_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$historial_laboral_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($historial_laboral->ExportAll && $historial_laboral->Export <> "") {
	$historial_laboral_list->StopRec = $historial_laboral_list->TotalRecs;
} else {

	// Set the last record to display
	if ($historial_laboral_list->TotalRecs > $historial_laboral_list->StartRec + $historial_laboral_list->DisplayRecs - 1)
		$historial_laboral_list->StopRec = $historial_laboral_list->StartRec + $historial_laboral_list->DisplayRecs - 1;
	else
		$historial_laboral_list->StopRec = $historial_laboral_list->TotalRecs;
}
$historial_laboral_list->RecCnt = $historial_laboral_list->StartRec - 1;
if ($historial_laboral_list->Recordset && !$historial_laboral_list->Recordset->EOF) {
	$historial_laboral_list->Recordset->MoveFirst();
	$bSelectLimit = $historial_laboral_list->UseSelectLimit;
	if (!$bSelectLimit && $historial_laboral_list->StartRec > 1)
		$historial_laboral_list->Recordset->Move($historial_laboral_list->StartRec - 1);
} elseif (!$historial_laboral->AllowAddDeleteRow && $historial_laboral_list->StopRec == 0) {
	$historial_laboral_list->StopRec = $historial_laboral->GridAddRowCount;
}

// Initialize aggregate
$historial_laboral->RowType = EW_ROWTYPE_AGGREGATEINIT;
$historial_laboral->ResetAttrs();
$historial_laboral_list->RenderRow();
while ($historial_laboral_list->RecCnt < $historial_laboral_list->StopRec) {
	$historial_laboral_list->RecCnt++;
	if (intval($historial_laboral_list->RecCnt) >= intval($historial_laboral_list->StartRec)) {
		$historial_laboral_list->RowCnt++;

		// Set up key count
		$historial_laboral_list->KeyCount = $historial_laboral_list->RowIndex;

		// Init row class and style
		$historial_laboral->ResetAttrs();
		$historial_laboral->CssClass = "";
		if ($historial_laboral->CurrentAction == "gridadd") {
		} else {
			$historial_laboral_list->LoadRowValues($historial_laboral_list->Recordset); // Load row values
		}
		$historial_laboral->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$historial_laboral->RowAttrs = array_merge($historial_laboral->RowAttrs, array('data-rowindex'=>$historial_laboral_list->RowCnt, 'id'=>'r' . $historial_laboral_list->RowCnt . '_historial_laboral', 'data-rowtype'=>$historial_laboral->RowType));

		// Render row
		$historial_laboral_list->RenderRow();

		// Render list options
		$historial_laboral_list->RenderListOptions();
?>
	<tr<?php echo $historial_laboral->RowAttributes() ?>>
<?php

// Render list options (body, left)
$historial_laboral_list->ListOptions->Render("body", "left", $historial_laboral_list->RowCnt);
?>
	<?php if ($historial_laboral->institucion->Visible) { // institucion ?>
		<td data-name="institucion"<?php echo $historial_laboral->institucion->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_institucion" class="historial_laboral_institucion">
<span<?php echo $historial_laboral->institucion->ViewAttributes() ?>>
<?php echo $historial_laboral->institucion->ListViewValue() ?></span>
</span>
<a id="<?php echo $historial_laboral_list->PageObjName . "_row_" . $historial_laboral_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($historial_laboral->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $historial_laboral->direccion->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_direccion" class="historial_laboral_direccion">
<span<?php echo $historial_laboral->direccion->ViewAttributes() ?>>
<?php echo $historial_laboral->direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->telefono->Visible) { // telefono ?>
		<td data-name="telefono"<?php echo $historial_laboral->telefono->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_telefono" class="historial_laboral_telefono">
<span<?php echo $historial_laboral->telefono->ViewAttributes() ?>>
<?php echo $historial_laboral->telefono->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->puesto->Visible) { // puesto ?>
		<td data-name="puesto"<?php echo $historial_laboral->puesto->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_puesto" class="historial_laboral_puesto">
<span<?php echo $historial_laboral->puesto->ViewAttributes() ?>>
<?php echo $historial_laboral->puesto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->atribuciones->Visible) { // atribuciones ?>
		<td data-name="atribuciones"<?php echo $historial_laboral->atribuciones->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_atribuciones" class="historial_laboral_atribuciones">
<span<?php echo $historial_laboral->atribuciones->ViewAttributes() ?>>
<?php echo $historial_laboral->atribuciones->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->jefe->Visible) { // jefe ?>
		<td data-name="jefe"<?php echo $historial_laboral->jefe->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_jefe" class="historial_laboral_jefe">
<span<?php echo $historial_laboral->jefe->ViewAttributes() ?>>
<?php echo $historial_laboral->jefe->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->fecha_ingreso->Visible) { // fecha_ingreso ?>
		<td data-name="fecha_ingreso"<?php echo $historial_laboral->fecha_ingreso->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_fecha_ingreso" class="historial_laboral_fecha_ingreso">
<span<?php echo $historial_laboral->fecha_ingreso->ViewAttributes() ?>>
<?php echo $historial_laboral->fecha_ingreso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->fecha_egreso->Visible) { // fecha_egreso ?>
		<td data-name="fecha_egreso"<?php echo $historial_laboral->fecha_egreso->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_fecha_egreso" class="historial_laboral_fecha_egreso">
<span<?php echo $historial_laboral->fecha_egreso->ViewAttributes() ?>>
<?php echo $historial_laboral->fecha_egreso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->sueldo_inicial->Visible) { // sueldo_inicial ?>
		<td data-name="sueldo_inicial"<?php echo $historial_laboral->sueldo_inicial->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_sueldo_inicial" class="historial_laboral_sueldo_inicial">
<span<?php echo $historial_laboral->sueldo_inicial->ViewAttributes() ?>>
<?php echo $historial_laboral->sueldo_inicial->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->sueldo_final->Visible) { // sueldo_final ?>
		<td data-name="sueldo_final"<?php echo $historial_laboral->sueldo_final->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_sueldo_final" class="historial_laboral_sueldo_final">
<span<?php echo $historial_laboral->sueldo_final->ViewAttributes() ?>>
<?php echo $historial_laboral->sueldo_final->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->motivo_retiro->Visible) { // motivo_retiro ?>
		<td data-name="motivo_retiro"<?php echo $historial_laboral->motivo_retiro->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_motivo_retiro" class="historial_laboral_motivo_retiro">
<span<?php echo $historial_laboral->motivo_retiro->ViewAttributes() ?>>
<?php echo $historial_laboral->motivo_retiro->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($historial_laboral->empleado_id->Visible) { // empleado_id ?>
		<td data-name="empleado_id"<?php echo $historial_laboral->empleado_id->CellAttributes() ?>>
<span id="el<?php echo $historial_laboral_list->RowCnt ?>_historial_laboral_empleado_id" class="historial_laboral_empleado_id">
<span<?php echo $historial_laboral->empleado_id->ViewAttributes() ?>>
<?php echo $historial_laboral->empleado_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$historial_laboral_list->ListOptions->Render("body", "right", $historial_laboral_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($historial_laboral->CurrentAction <> "gridadd")
		$historial_laboral_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($historial_laboral->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($historial_laboral_list->Recordset)
	$historial_laboral_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($historial_laboral->CurrentAction <> "gridadd" && $historial_laboral->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($historial_laboral_list->Pager)) $historial_laboral_list->Pager = new cPrevNextPager($historial_laboral_list->StartRec, $historial_laboral_list->DisplayRecs, $historial_laboral_list->TotalRecs) ?>
<?php if ($historial_laboral_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($historial_laboral_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $historial_laboral_list->PageUrl() ?>start=<?php echo $historial_laboral_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($historial_laboral_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $historial_laboral_list->PageUrl() ?>start=<?php echo $historial_laboral_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $historial_laboral_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($historial_laboral_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $historial_laboral_list->PageUrl() ?>start=<?php echo $historial_laboral_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($historial_laboral_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $historial_laboral_list->PageUrl() ?>start=<?php echo $historial_laboral_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $historial_laboral_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $historial_laboral_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $historial_laboral_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $historial_laboral_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($historial_laboral_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($historial_laboral_list->TotalRecs == 0 && $historial_laboral->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($historial_laboral_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fhistorial_laborallistsrch.Init();
fhistorial_laborallistsrch.FilterList = <?php echo $historial_laboral_list->GetFilterList() ?>;
fhistorial_laborallist.Init();
</script>
<?php
$historial_laboral_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$historial_laboral_list->Page_Terminate();
?>
