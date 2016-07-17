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

$empleado_list = NULL; // Initialize page object first

class cempleado_list extends cempleado {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{8127A4B8-77E3-4A91-B475-0A98E2BB4762}";

	// Table name
	var $TableName = 'empleado';

	// Page object name
	var $PageObjName = 'empleado_list';

	// Grid form hidden field names
	var $FormName = 'fempleadolist';
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

		// Table object (empleado)
		if (!isset($GLOBALS["empleado"]) || get_class($GLOBALS["empleado"]) == "cempleado") {
			$GLOBALS["empleado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empleado"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "empleadoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "empleadodelete.php";
		$this->MultiUpdateUrl = "empleadoupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fempleadolistsrch";

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

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

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
			$this->empleado_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->empleado_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->codigo); // codigo
			$this->UpdateSort($this->cui); // cui
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->apellido); // apellido
			$this->UpdateSort($this->direccion); // direccion
			$this->UpdateSort($this->departamento_origen_id); // departamento_origen_id
			$this->UpdateSort($this->municipio_id); // municipio_id
			$this->UpdateSort($this->telefono_residencia); // telefono_residencia
			$this->UpdateSort($this->telefono_celular); // telefono_celular
			$this->UpdateSort($this->fecha_nacimiento); // fecha_nacimiento
			$this->UpdateSort($this->nacionalidad); // nacionalidad
			$this->UpdateSort($this->estado_civil); // estado_civil
			$this->UpdateSort($this->sexo); // sexo
			$this->UpdateSort($this->igss); // igss
			$this->UpdateSort($this->nit); // nit
			$this->UpdateSort($this->licencia_conducir); // licencia_conducir
			$this->UpdateSort($this->area_id); // area_id
			$this->UpdateSort($this->departmento_id); // departmento_id
			$this->UpdateSort($this->seccion_id); // seccion_id
			$this->UpdateSort($this->puesto_id); // puesto_id
			$this->UpdateSort($this->tipo_sangre_id); // tipo_sangre_id
			$this->UpdateSort($this->estado); // estado
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->codigo->setSort("");
				$this->cui->setSort("");
				$this->nombre->setSort("");
				$this->apellido->setSort("");
				$this->direccion->setSort("");
				$this->departamento_origen_id->setSort("");
				$this->municipio_id->setSort("");
				$this->telefono_residencia->setSort("");
				$this->telefono_celular->setSort("");
				$this->fecha_nacimiento->setSort("");
				$this->nacionalidad->setSort("");
				$this->estado_civil->setSort("");
				$this->sexo->setSort("");
				$this->igss->setSort("");
				$this->nit->setSort("");
				$this->licencia_conducir->setSort("");
				$this->area_id->setSort("");
				$this->departmento_id->setSort("");
				$this->seccion_id->setSort("");
				$this->puesto_id->setSort("");
				$this->tipo_sangre_id->setSort("");
				$this->estado->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->empleado_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fempleadolistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fempleadolistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = FALSE;
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fempleadolist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("empleado_id")) <> "")
			$this->empleado_id->CurrentValue = $this->getKey("empleado_id"); // empleado_id
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
		// empleado_id

		$this->empleado_id->CellCssStyle = "white-space: nowrap;";

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
if (!isset($empleado_list)) $empleado_list = new cempleado_list();

// Page init
$empleado_list->Page_Init();

// Page main
$empleado_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empleado_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fempleadolist = new ew_Form("fempleadolist", "list");
fempleadolist.FormKeyCountName = '<?php echo $empleado_list->FormKeyCountName ?>';

// Form_CustomValidate event
fempleadolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadolist.ValidateRequired = true;
<?php } else { ?>
fempleadolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempleadolist.Lists["x_departamento_origen_id"] = {"LinkField":"x_departamento_origen_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_municipio_id"] = {"LinkField":"x_municipio_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_nacionalidad"] = {"LinkField":"x_nacionalidad_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nacionalidad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_estado_civil"] = {"LinkField":"x_estado_civil_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_estado_civil","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_sexo"] = {"LinkField":"x_sexo_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sexo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_area_id"] = {"LinkField":"x_area_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_departmento_id"] = {"LinkField":"x_departamento_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":["x_departmento_id"],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_seccion_id"] = {"LinkField":"x_seccion_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":["x_seccion_id"],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_puesto_id"] = {"LinkField":"x_puesto_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":["x_puesto_id"],"FilterFields":[],"Options":[],"Template":""};
fempleadolist.Lists["x_tipo_sangre_id"] = {"LinkField":"x_tipo_sangre_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_sangre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($empleado_list->TotalRecs > 0 && $empleado_list->ExportOptions->Visible()) { ?>
<?php $empleado_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $empleado_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($empleado_list->TotalRecs <= 0)
			$empleado_list->TotalRecs = $empleado->SelectRecordCount();
	} else {
		if (!$empleado_list->Recordset && ($empleado_list->Recordset = $empleado_list->LoadRecordset()))
			$empleado_list->TotalRecs = $empleado_list->Recordset->RecordCount();
	}
	$empleado_list->StartRec = 1;
	if ($empleado_list->DisplayRecs <= 0 || ($empleado->Export <> "" && $empleado->ExportAll)) // Display all records
		$empleado_list->DisplayRecs = $empleado_list->TotalRecs;
	if (!($empleado->Export <> "" && $empleado->ExportAll))
		$empleado_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$empleado_list->Recordset = $empleado_list->LoadRecordset($empleado_list->StartRec-1, $empleado_list->DisplayRecs);

	// Set no record found message
	if ($empleado->CurrentAction == "" && $empleado_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$empleado_list->setWarningMessage(ew_DeniedMsg());
		if ($empleado_list->SearchWhere == "0=101")
			$empleado_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$empleado_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$empleado_list->RenderOtherOptions();
?>
<?php $empleado_list->ShowPageHeader(); ?>
<?php
$empleado_list->ShowMessage();
?>
<?php if ($empleado_list->TotalRecs > 0 || $empleado->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fempleadolist" id="fempleadolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empleado_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empleado_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empleado">
<div id="gmp_empleado" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($empleado_list->TotalRecs > 0) { ?>
<table id="tbl_empleadolist" class="table ewTable">
<?php echo $empleado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$empleado_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$empleado_list->RenderListOptions();

// Render list options (header, left)
$empleado_list->ListOptions->Render("header", "left");
?>
<?php if ($empleado->codigo->Visible) { // codigo ?>
	<?php if ($empleado->SortUrl($empleado->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_empleado_codigo" class="empleado_codigo"><div class="ewTableHeaderCaption"><?php echo $empleado->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->codigo) ?>',1);"><div id="elh_empleado_codigo" class="empleado_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->cui->Visible) { // cui ?>
	<?php if ($empleado->SortUrl($empleado->cui) == "") { ?>
		<th data-name="cui"><div id="elh_empleado_cui" class="empleado_cui"><div class="ewTableHeaderCaption"><?php echo $empleado->cui->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cui"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->cui) ?>',1);"><div id="elh_empleado_cui" class="empleado_cui">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->cui->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->cui->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->cui->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->nombre->Visible) { // nombre ?>
	<?php if ($empleado->SortUrl($empleado->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_empleado_nombre" class="empleado_nombre"><div class="ewTableHeaderCaption"><?php echo $empleado->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->nombre) ?>',1);"><div id="elh_empleado_nombre" class="empleado_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->apellido->Visible) { // apellido ?>
	<?php if ($empleado->SortUrl($empleado->apellido) == "") { ?>
		<th data-name="apellido"><div id="elh_empleado_apellido" class="empleado_apellido"><div class="ewTableHeaderCaption"><?php echo $empleado->apellido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellido"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->apellido) ?>',1);"><div id="elh_empleado_apellido" class="empleado_apellido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->apellido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->apellido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->apellido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->direccion->Visible) { // direccion ?>
	<?php if ($empleado->SortUrl($empleado->direccion) == "") { ?>
		<th data-name="direccion"><div id="elh_empleado_direccion" class="empleado_direccion"><div class="ewTableHeaderCaption"><?php echo $empleado->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->direccion) ?>',1);"><div id="elh_empleado_direccion" class="empleado_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->departamento_origen_id->Visible) { // departamento_origen_id ?>
	<?php if ($empleado->SortUrl($empleado->departamento_origen_id) == "") { ?>
		<th data-name="departamento_origen_id"><div id="elh_empleado_departamento_origen_id" class="empleado_departamento_origen_id"><div class="ewTableHeaderCaption"><?php echo $empleado->departamento_origen_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departamento_origen_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->departamento_origen_id) ?>',1);"><div id="elh_empleado_departamento_origen_id" class="empleado_departamento_origen_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->departamento_origen_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->departamento_origen_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->departamento_origen_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->municipio_id->Visible) { // municipio_id ?>
	<?php if ($empleado->SortUrl($empleado->municipio_id) == "") { ?>
		<th data-name="municipio_id"><div id="elh_empleado_municipio_id" class="empleado_municipio_id"><div class="ewTableHeaderCaption"><?php echo $empleado->municipio_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="municipio_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->municipio_id) ?>',1);"><div id="elh_empleado_municipio_id" class="empleado_municipio_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->municipio_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->municipio_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->municipio_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->telefono_residencia->Visible) { // telefono_residencia ?>
	<?php if ($empleado->SortUrl($empleado->telefono_residencia) == "") { ?>
		<th data-name="telefono_residencia"><div id="elh_empleado_telefono_residencia" class="empleado_telefono_residencia"><div class="ewTableHeaderCaption"><?php echo $empleado->telefono_residencia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono_residencia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->telefono_residencia) ?>',1);"><div id="elh_empleado_telefono_residencia" class="empleado_telefono_residencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->telefono_residencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->telefono_residencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->telefono_residencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->telefono_celular->Visible) { // telefono_celular ?>
	<?php if ($empleado->SortUrl($empleado->telefono_celular) == "") { ?>
		<th data-name="telefono_celular"><div id="elh_empleado_telefono_celular" class="empleado_telefono_celular"><div class="ewTableHeaderCaption"><?php echo $empleado->telefono_celular->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono_celular"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->telefono_celular) ?>',1);"><div id="elh_empleado_telefono_celular" class="empleado_telefono_celular">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->telefono_celular->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->telefono_celular->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->telefono_celular->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<?php if ($empleado->SortUrl($empleado->fecha_nacimiento) == "") { ?>
		<th data-name="fecha_nacimiento"><div id="elh_empleado_fecha_nacimiento" class="empleado_fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $empleado->fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_nacimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->fecha_nacimiento) ?>',1);"><div id="elh_empleado_fecha_nacimiento" class="empleado_fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->nacionalidad->Visible) { // nacionalidad ?>
	<?php if ($empleado->SortUrl($empleado->nacionalidad) == "") { ?>
		<th data-name="nacionalidad"><div id="elh_empleado_nacionalidad" class="empleado_nacionalidad"><div class="ewTableHeaderCaption"><?php echo $empleado->nacionalidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nacionalidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->nacionalidad) ?>',1);"><div id="elh_empleado_nacionalidad" class="empleado_nacionalidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->nacionalidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->nacionalidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->nacionalidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->estado_civil->Visible) { // estado_civil ?>
	<?php if ($empleado->SortUrl($empleado->estado_civil) == "") { ?>
		<th data-name="estado_civil"><div id="elh_empleado_estado_civil" class="empleado_estado_civil"><div class="ewTableHeaderCaption"><?php echo $empleado->estado_civil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado_civil"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->estado_civil) ?>',1);"><div id="elh_empleado_estado_civil" class="empleado_estado_civil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->estado_civil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->estado_civil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->estado_civil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->sexo->Visible) { // sexo ?>
	<?php if ($empleado->SortUrl($empleado->sexo) == "") { ?>
		<th data-name="sexo"><div id="elh_empleado_sexo" class="empleado_sexo"><div class="ewTableHeaderCaption"><?php echo $empleado->sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sexo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->sexo) ?>',1);"><div id="elh_empleado_sexo" class="empleado_sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->igss->Visible) { // igss ?>
	<?php if ($empleado->SortUrl($empleado->igss) == "") { ?>
		<th data-name="igss"><div id="elh_empleado_igss" class="empleado_igss"><div class="ewTableHeaderCaption"><?php echo $empleado->igss->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="igss"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->igss) ?>',1);"><div id="elh_empleado_igss" class="empleado_igss">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->igss->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->igss->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->igss->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->nit->Visible) { // nit ?>
	<?php if ($empleado->SortUrl($empleado->nit) == "") { ?>
		<th data-name="nit"><div id="elh_empleado_nit" class="empleado_nit"><div class="ewTableHeaderCaption"><?php echo $empleado->nit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nit"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->nit) ?>',1);"><div id="elh_empleado_nit" class="empleado_nit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->nit->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->nit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->nit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->licencia_conducir->Visible) { // licencia_conducir ?>
	<?php if ($empleado->SortUrl($empleado->licencia_conducir) == "") { ?>
		<th data-name="licencia_conducir"><div id="elh_empleado_licencia_conducir" class="empleado_licencia_conducir"><div class="ewTableHeaderCaption"><?php echo $empleado->licencia_conducir->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="licencia_conducir"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->licencia_conducir) ?>',1);"><div id="elh_empleado_licencia_conducir" class="empleado_licencia_conducir">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->licencia_conducir->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->licencia_conducir->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->licencia_conducir->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->area_id->Visible) { // area_id ?>
	<?php if ($empleado->SortUrl($empleado->area_id) == "") { ?>
		<th data-name="area_id"><div id="elh_empleado_area_id" class="empleado_area_id"><div class="ewTableHeaderCaption"><?php echo $empleado->area_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="area_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->area_id) ?>',1);"><div id="elh_empleado_area_id" class="empleado_area_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->area_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->area_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->area_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->departmento_id->Visible) { // departmento_id ?>
	<?php if ($empleado->SortUrl($empleado->departmento_id) == "") { ?>
		<th data-name="departmento_id"><div id="elh_empleado_departmento_id" class="empleado_departmento_id"><div class="ewTableHeaderCaption"><?php echo $empleado->departmento_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departmento_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->departmento_id) ?>',1);"><div id="elh_empleado_departmento_id" class="empleado_departmento_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->departmento_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->departmento_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->departmento_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->seccion_id->Visible) { // seccion_id ?>
	<?php if ($empleado->SortUrl($empleado->seccion_id) == "") { ?>
		<th data-name="seccion_id"><div id="elh_empleado_seccion_id" class="empleado_seccion_id"><div class="ewTableHeaderCaption"><?php echo $empleado->seccion_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="seccion_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->seccion_id) ?>',1);"><div id="elh_empleado_seccion_id" class="empleado_seccion_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->seccion_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->seccion_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->seccion_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->puesto_id->Visible) { // puesto_id ?>
	<?php if ($empleado->SortUrl($empleado->puesto_id) == "") { ?>
		<th data-name="puesto_id"><div id="elh_empleado_puesto_id" class="empleado_puesto_id"><div class="ewTableHeaderCaption"><?php echo $empleado->puesto_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="puesto_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->puesto_id) ?>',1);"><div id="elh_empleado_puesto_id" class="empleado_puesto_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->puesto_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->puesto_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->puesto_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->tipo_sangre_id->Visible) { // tipo_sangre_id ?>
	<?php if ($empleado->SortUrl($empleado->tipo_sangre_id) == "") { ?>
		<th data-name="tipo_sangre_id"><div id="elh_empleado_tipo_sangre_id" class="empleado_tipo_sangre_id"><div class="ewTableHeaderCaption"><?php echo $empleado->tipo_sangre_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_sangre_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->tipo_sangre_id) ?>',1);"><div id="elh_empleado_tipo_sangre_id" class="empleado_tipo_sangre_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->tipo_sangre_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->tipo_sangre_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->tipo_sangre_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->estado->Visible) { // estado ?>
	<?php if ($empleado->SortUrl($empleado->estado) == "") { ?>
		<th data-name="estado"><div id="elh_empleado_estado" class="empleado_estado"><div class="ewTableHeaderCaption"><?php echo $empleado->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleado->SortUrl($empleado->estado) ?>',1);"><div id="elh_empleado_estado" class="empleado_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$empleado_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($empleado->ExportAll && $empleado->Export <> "") {
	$empleado_list->StopRec = $empleado_list->TotalRecs;
} else {

	// Set the last record to display
	if ($empleado_list->TotalRecs > $empleado_list->StartRec + $empleado_list->DisplayRecs - 1)
		$empleado_list->StopRec = $empleado_list->StartRec + $empleado_list->DisplayRecs - 1;
	else
		$empleado_list->StopRec = $empleado_list->TotalRecs;
}
$empleado_list->RecCnt = $empleado_list->StartRec - 1;
if ($empleado_list->Recordset && !$empleado_list->Recordset->EOF) {
	$empleado_list->Recordset->MoveFirst();
	$bSelectLimit = $empleado_list->UseSelectLimit;
	if (!$bSelectLimit && $empleado_list->StartRec > 1)
		$empleado_list->Recordset->Move($empleado_list->StartRec - 1);
} elseif (!$empleado->AllowAddDeleteRow && $empleado_list->StopRec == 0) {
	$empleado_list->StopRec = $empleado->GridAddRowCount;
}

// Initialize aggregate
$empleado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$empleado->ResetAttrs();
$empleado_list->RenderRow();
while ($empleado_list->RecCnt < $empleado_list->StopRec) {
	$empleado_list->RecCnt++;
	if (intval($empleado_list->RecCnt) >= intval($empleado_list->StartRec)) {
		$empleado_list->RowCnt++;

		// Set up key count
		$empleado_list->KeyCount = $empleado_list->RowIndex;

		// Init row class and style
		$empleado->ResetAttrs();
		$empleado->CssClass = "";
		if ($empleado->CurrentAction == "gridadd") {
		} else {
			$empleado_list->LoadRowValues($empleado_list->Recordset); // Load row values
		}
		$empleado->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$empleado->RowAttrs = array_merge($empleado->RowAttrs, array('data-rowindex'=>$empleado_list->RowCnt, 'id'=>'r' . $empleado_list->RowCnt . '_empleado', 'data-rowtype'=>$empleado->RowType));

		// Render row
		$empleado_list->RenderRow();

		// Render list options
		$empleado_list->RenderListOptions();
?>
	<tr<?php echo $empleado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empleado_list->ListOptions->Render("body", "left", $empleado_list->RowCnt);
?>
	<?php if ($empleado->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $empleado->codigo->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_codigo" class="empleado_codigo">
<span<?php echo $empleado->codigo->ViewAttributes() ?>>
<?php echo $empleado->codigo->ListViewValue() ?></span>
</span>
<a id="<?php echo $empleado_list->PageObjName . "_row_" . $empleado_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($empleado->cui->Visible) { // cui ?>
		<td data-name="cui"<?php echo $empleado->cui->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_cui" class="empleado_cui">
<span<?php echo $empleado->cui->ViewAttributes() ?>>
<?php echo $empleado->cui->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $empleado->nombre->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_nombre" class="empleado_nombre">
<span<?php echo $empleado->nombre->ViewAttributes() ?>>
<?php echo $empleado->nombre->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->apellido->Visible) { // apellido ?>
		<td data-name="apellido"<?php echo $empleado->apellido->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_apellido" class="empleado_apellido">
<span<?php echo $empleado->apellido->ViewAttributes() ?>>
<?php echo $empleado->apellido->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $empleado->direccion->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_direccion" class="empleado_direccion">
<span<?php echo $empleado->direccion->ViewAttributes() ?>>
<?php echo $empleado->direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->departamento_origen_id->Visible) { // departamento_origen_id ?>
		<td data-name="departamento_origen_id"<?php echo $empleado->departamento_origen_id->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_departamento_origen_id" class="empleado_departamento_origen_id">
<span<?php echo $empleado->departamento_origen_id->ViewAttributes() ?>>
<?php echo $empleado->departamento_origen_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->municipio_id->Visible) { // municipio_id ?>
		<td data-name="municipio_id"<?php echo $empleado->municipio_id->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_municipio_id" class="empleado_municipio_id">
<span<?php echo $empleado->municipio_id->ViewAttributes() ?>>
<?php echo $empleado->municipio_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->telefono_residencia->Visible) { // telefono_residencia ?>
		<td data-name="telefono_residencia"<?php echo $empleado->telefono_residencia->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_telefono_residencia" class="empleado_telefono_residencia">
<span<?php echo $empleado->telefono_residencia->ViewAttributes() ?>>
<?php echo $empleado->telefono_residencia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->telefono_celular->Visible) { // telefono_celular ?>
		<td data-name="telefono_celular"<?php echo $empleado->telefono_celular->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_telefono_celular" class="empleado_telefono_celular">
<span<?php echo $empleado->telefono_celular->ViewAttributes() ?>>
<?php echo $empleado->telefono_celular->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento"<?php echo $empleado->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_fecha_nacimiento" class="empleado_fecha_nacimiento">
<span<?php echo $empleado->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $empleado->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->nacionalidad->Visible) { // nacionalidad ?>
		<td data-name="nacionalidad"<?php echo $empleado->nacionalidad->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_nacionalidad" class="empleado_nacionalidad">
<span<?php echo $empleado->nacionalidad->ViewAttributes() ?>>
<?php echo $empleado->nacionalidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->estado_civil->Visible) { // estado_civil ?>
		<td data-name="estado_civil"<?php echo $empleado->estado_civil->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_estado_civil" class="empleado_estado_civil">
<span<?php echo $empleado->estado_civil->ViewAttributes() ?>>
<?php echo $empleado->estado_civil->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->sexo->Visible) { // sexo ?>
		<td data-name="sexo"<?php echo $empleado->sexo->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_sexo" class="empleado_sexo">
<span<?php echo $empleado->sexo->ViewAttributes() ?>>
<?php echo $empleado->sexo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->igss->Visible) { // igss ?>
		<td data-name="igss"<?php echo $empleado->igss->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_igss" class="empleado_igss">
<span<?php echo $empleado->igss->ViewAttributes() ?>>
<?php echo $empleado->igss->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->nit->Visible) { // nit ?>
		<td data-name="nit"<?php echo $empleado->nit->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_nit" class="empleado_nit">
<span<?php echo $empleado->nit->ViewAttributes() ?>>
<?php echo $empleado->nit->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->licencia_conducir->Visible) { // licencia_conducir ?>
		<td data-name="licencia_conducir"<?php echo $empleado->licencia_conducir->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_licencia_conducir" class="empleado_licencia_conducir">
<span<?php echo $empleado->licencia_conducir->ViewAttributes() ?>>
<?php echo $empleado->licencia_conducir->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->area_id->Visible) { // area_id ?>
		<td data-name="area_id"<?php echo $empleado->area_id->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_area_id" class="empleado_area_id">
<span<?php echo $empleado->area_id->ViewAttributes() ?>>
<?php echo $empleado->area_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->departmento_id->Visible) { // departmento_id ?>
		<td data-name="departmento_id"<?php echo $empleado->departmento_id->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_departmento_id" class="empleado_departmento_id">
<span<?php echo $empleado->departmento_id->ViewAttributes() ?>>
<?php echo $empleado->departmento_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->seccion_id->Visible) { // seccion_id ?>
		<td data-name="seccion_id"<?php echo $empleado->seccion_id->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_seccion_id" class="empleado_seccion_id">
<span<?php echo $empleado->seccion_id->ViewAttributes() ?>>
<?php echo $empleado->seccion_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->puesto_id->Visible) { // puesto_id ?>
		<td data-name="puesto_id"<?php echo $empleado->puesto_id->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_puesto_id" class="empleado_puesto_id">
<span<?php echo $empleado->puesto_id->ViewAttributes() ?>>
<?php echo $empleado->puesto_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->tipo_sangre_id->Visible) { // tipo_sangre_id ?>
		<td data-name="tipo_sangre_id"<?php echo $empleado->tipo_sangre_id->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_tipo_sangre_id" class="empleado_tipo_sangre_id">
<span<?php echo $empleado->tipo_sangre_id->ViewAttributes() ?>>
<?php echo $empleado->tipo_sangre_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empleado->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $empleado->estado->CellAttributes() ?>>
<span id="el<?php echo $empleado_list->RowCnt ?>_empleado_estado" class="empleado_estado">
<span<?php echo $empleado->estado->ViewAttributes() ?>>
<?php echo $empleado->estado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$empleado_list->ListOptions->Render("body", "right", $empleado_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($empleado->CurrentAction <> "gridadd")
		$empleado_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($empleado->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($empleado_list->Recordset)
	$empleado_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($empleado->CurrentAction <> "gridadd" && $empleado->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($empleado_list->Pager)) $empleado_list->Pager = new cPrevNextPager($empleado_list->StartRec, $empleado_list->DisplayRecs, $empleado_list->TotalRecs) ?>
<?php if ($empleado_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($empleado_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $empleado_list->PageUrl() ?>start=<?php echo $empleado_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($empleado_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $empleado_list->PageUrl() ?>start=<?php echo $empleado_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $empleado_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($empleado_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $empleado_list->PageUrl() ?>start=<?php echo $empleado_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($empleado_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $empleado_list->PageUrl() ?>start=<?php echo $empleado_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $empleado_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $empleado_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $empleado_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $empleado_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empleado_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($empleado_list->TotalRecs == 0 && $empleado->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empleado_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fempleadolist.Init();
</script>
<?php
$empleado_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empleado_list->Page_Terminate();
?>
