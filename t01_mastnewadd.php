<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t01_mastnewinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t01_mastnew_add = NULL; // Initialize page object first

class ct01_mastnew_add extends ct01_mastnew {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{C65252F3-D17C-4838-BE42-BEF263C3CA1E}";

	// Table name
	var $TableName = 't01_mastnew';

	// Page object name
	var $PageObjName = 't01_mastnew_add';

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
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
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
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t01_mastnew)
		if (!isset($GLOBALS["t01_mastnew"]) || get_class($GLOBALS["t01_mastnew"]) == "ct01_mastnew") {
			$GLOBALS["t01_mastnew"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t01_mastnew"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't01_mastnew', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->norubm->SetVisibility();
		$this->noperm->SetVisibility();
		$this->naperm->SetVisibility();
		$this->rpdini->SetVisibility();
		$this->rpkini->SetVisibility();

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
		global $EW_EXPORT, $t01_mastnew;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t01_mastnew);
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
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
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
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
					$this->Page_Terminate("t01_mastnewlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t01_mastnewlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t01_mastnewview.php")
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
		$this->norubm->CurrentValue = NULL;
		$this->norubm->OldValue = $this->norubm->CurrentValue;
		$this->noperm->CurrentValue = NULL;
		$this->noperm->OldValue = $this->noperm->CurrentValue;
		$this->naperm->CurrentValue = NULL;
		$this->naperm->OldValue = $this->naperm->CurrentValue;
		$this->rpdini->CurrentValue = 0.00;
		$this->rpkini->CurrentValue = 0.00;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->norubm->FldIsDetailKey) {
			$this->norubm->setFormValue($objForm->GetValue("x_norubm"));
		}
		if (!$this->noperm->FldIsDetailKey) {
			$this->noperm->setFormValue($objForm->GetValue("x_noperm"));
		}
		if (!$this->naperm->FldIsDetailKey) {
			$this->naperm->setFormValue($objForm->GetValue("x_naperm"));
		}
		if (!$this->rpdini->FldIsDetailKey) {
			$this->rpdini->setFormValue($objForm->GetValue("x_rpdini"));
		}
		if (!$this->rpkini->FldIsDetailKey) {
			$this->rpkini->setFormValue($objForm->GetValue("x_rpkini"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->norubm->CurrentValue = $this->norubm->FormValue;
		$this->noperm->CurrentValue = $this->noperm->FormValue;
		$this->naperm->CurrentValue = $this->naperm->FormValue;
		$this->rpdini->CurrentValue = $this->rpdini->FormValue;
		$this->rpkini->CurrentValue = $this->rpkini->FormValue;
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
		$this->id->setDbValue($rs->fields('id'));
		$this->norubm->setDbValue($rs->fields('norubm'));
		$this->noperm->setDbValue($rs->fields('noperm'));
		$this->naperm->setDbValue($rs->fields('naperm'));
		$this->rpdlalu->setDbValue($rs->fields('rpdlalu'));
		$this->rpklalu->setDbValue($rs->fields('rpklalu'));
		$this->rpdini->setDbValue($rs->fields('rpdini'));
		$this->rpkini->setDbValue($rs->fields('rpkini'));
		$this->blth->setDbValue($rs->fields('blth'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->norubm->DbValue = $row['norubm'];
		$this->noperm->DbValue = $row['noperm'];
		$this->naperm->DbValue = $row['naperm'];
		$this->rpdlalu->DbValue = $row['rpdlalu'];
		$this->rpklalu->DbValue = $row['rpklalu'];
		$this->rpdini->DbValue = $row['rpdini'];
		$this->rpkini->DbValue = $row['rpkini'];
		$this->blth->DbValue = $row['blth'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// Convert decimal values if posted back

		if ($this->rpdini->FormValue == $this->rpdini->CurrentValue && is_numeric(ew_StrToFloat($this->rpdini->CurrentValue)))
			$this->rpdini->CurrentValue = ew_StrToFloat($this->rpdini->CurrentValue);

		// Convert decimal values if posted back
		if ($this->rpkini->FormValue == $this->rpkini->CurrentValue && is_numeric(ew_StrToFloat($this->rpkini->CurrentValue)))
			$this->rpkini->CurrentValue = ew_StrToFloat($this->rpkini->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// norubm
		// noperm
		// naperm
		// rpdlalu
		// rpklalu
		// rpdini
		// rpkini
		// blth

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// norubm
		if (strval($this->norubm->CurrentValue) <> "") {
			$this->norubm->ViewValue = $this->norubm->OptionCaption($this->norubm->CurrentValue);
		} else {
			$this->norubm->ViewValue = NULL;
		}
		$this->norubm->ViewCustomAttributes = "";

		// noperm
		$this->noperm->ViewValue = $this->noperm->CurrentValue;
		$this->noperm->ViewCustomAttributes = "";

		// naperm
		$this->naperm->ViewValue = $this->naperm->CurrentValue;
		$this->naperm->ViewCustomAttributes = "";

		// rpdlalu
		$this->rpdlalu->ViewValue = $this->rpdlalu->CurrentValue;
		$this->rpdlalu->ViewCustomAttributes = "";

		// rpklalu
		$this->rpklalu->ViewValue = $this->rpklalu->CurrentValue;
		$this->rpklalu->ViewCustomAttributes = "";

		// rpdini
		$this->rpdini->ViewValue = $this->rpdini->CurrentValue;
		$this->rpdini->ViewCustomAttributes = "";

		// rpkini
		$this->rpkini->ViewValue = $this->rpkini->CurrentValue;
		$this->rpkini->ViewCustomAttributes = "";

		// blth
		$this->blth->ViewValue = $this->blth->CurrentValue;
		$this->blth->ViewCustomAttributes = "";

			// norubm
			$this->norubm->LinkCustomAttributes = "";
			$this->norubm->HrefValue = "";
			$this->norubm->TooltipValue = "";

			// noperm
			$this->noperm->LinkCustomAttributes = "";
			$this->noperm->HrefValue = "";
			$this->noperm->TooltipValue = "";

			// naperm
			$this->naperm->LinkCustomAttributes = "";
			$this->naperm->HrefValue = "";
			$this->naperm->TooltipValue = "";

			// rpdini
			$this->rpdini->LinkCustomAttributes = "";
			$this->rpdini->HrefValue = "";
			$this->rpdini->TooltipValue = "";

			// rpkini
			$this->rpkini->LinkCustomAttributes = "";
			$this->rpkini->HrefValue = "";
			$this->rpkini->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// norubm
			$this->norubm->EditAttrs["class"] = "form-control";
			$this->norubm->EditCustomAttributes = "";
			$this->norubm->EditValue = $this->norubm->Options(TRUE);

			// noperm
			$this->noperm->EditAttrs["class"] = "form-control";
			$this->noperm->EditCustomAttributes = "";
			$this->noperm->EditValue = ew_HtmlEncode($this->noperm->CurrentValue);
			$this->noperm->PlaceHolder = ew_RemoveHtml($this->noperm->FldCaption());

			// naperm
			$this->naperm->EditAttrs["class"] = "form-control";
			$this->naperm->EditCustomAttributes = "";
			$this->naperm->EditValue = ew_HtmlEncode($this->naperm->CurrentValue);
			$this->naperm->PlaceHolder = ew_RemoveHtml($this->naperm->FldCaption());

			// rpdini
			$this->rpdini->EditAttrs["class"] = "form-control";
			$this->rpdini->EditCustomAttributes = "";
			$this->rpdini->EditValue = ew_HtmlEncode($this->rpdini->CurrentValue);
			$this->rpdini->PlaceHolder = ew_RemoveHtml($this->rpdini->FldCaption());
			if (strval($this->rpdini->EditValue) <> "" && is_numeric($this->rpdini->EditValue)) $this->rpdini->EditValue = ew_FormatNumber($this->rpdini->EditValue, -2, -1, -2, 0);

			// rpkini
			$this->rpkini->EditAttrs["class"] = "form-control";
			$this->rpkini->EditCustomAttributes = "";
			$this->rpkini->EditValue = ew_HtmlEncode($this->rpkini->CurrentValue);
			$this->rpkini->PlaceHolder = ew_RemoveHtml($this->rpkini->FldCaption());
			if (strval($this->rpkini->EditValue) <> "" && is_numeric($this->rpkini->EditValue)) $this->rpkini->EditValue = ew_FormatNumber($this->rpkini->EditValue, -2, -1, -2, 0);

			// Add refer script
			// norubm

			$this->norubm->LinkCustomAttributes = "";
			$this->norubm->HrefValue = "";

			// noperm
			$this->noperm->LinkCustomAttributes = "";
			$this->noperm->HrefValue = "";

			// naperm
			$this->naperm->LinkCustomAttributes = "";
			$this->naperm->HrefValue = "";

			// rpdini
			$this->rpdini->LinkCustomAttributes = "";
			$this->rpdini->HrefValue = "";

			// rpkini
			$this->rpkini->LinkCustomAttributes = "";
			$this->rpkini->HrefValue = "";
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
		if (!$this->norubm->FldIsDetailKey && !is_null($this->norubm->FormValue) && $this->norubm->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->norubm->FldCaption(), $this->norubm->ReqErrMsg));
		}
		if (!$this->noperm->FldIsDetailKey && !is_null($this->noperm->FormValue) && $this->noperm->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->noperm->FldCaption(), $this->noperm->ReqErrMsg));
		}
		if (!$this->naperm->FldIsDetailKey && !is_null($this->naperm->FormValue) && $this->naperm->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->naperm->FldCaption(), $this->naperm->ReqErrMsg));
		}
		if (!$this->rpdini->FldIsDetailKey && !is_null($this->rpdini->FormValue) && $this->rpdini->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rpdini->FldCaption(), $this->rpdini->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->rpdini->FormValue)) {
			ew_AddMessage($gsFormError, $this->rpdini->FldErrMsg());
		}
		if (!$this->rpkini->FldIsDetailKey && !is_null($this->rpkini->FormValue) && $this->rpkini->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rpkini->FldCaption(), $this->rpkini->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->rpkini->FormValue)) {
			ew_AddMessage($gsFormError, $this->rpkini->FldErrMsg());
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

		// norubm
		$this->norubm->SetDbValueDef($rsnew, $this->norubm->CurrentValue, "", FALSE);

		// noperm
		$this->noperm->SetDbValueDef($rsnew, $this->noperm->CurrentValue, "", FALSE);

		// naperm
		$this->naperm->SetDbValueDef($rsnew, $this->naperm->CurrentValue, "", FALSE);

		// rpdini
		$this->rpdini->SetDbValueDef($rsnew, $this->rpdini->CurrentValue, 0, strval($this->rpdini->CurrentValue) == "");

		// rpkini
		$this->rpkini->SetDbValueDef($rsnew, $this->rpkini->CurrentValue, 0, strval($this->rpkini->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t01_mastnewlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
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
if (!isset($t01_mastnew_add)) $t01_mastnew_add = new ct01_mastnew_add();

// Page init
$t01_mastnew_add->Page_Init();

// Page main
$t01_mastnew_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t01_mastnew_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft01_mastnewadd = new ew_Form("ft01_mastnewadd", "add");

// Validate form
ft01_mastnewadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_norubm");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t01_mastnew->norubm->FldCaption(), $t01_mastnew->norubm->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_noperm");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t01_mastnew->noperm->FldCaption(), $t01_mastnew->noperm->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_naperm");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t01_mastnew->naperm->FldCaption(), $t01_mastnew->naperm->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rpdini");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t01_mastnew->rpdini->FldCaption(), $t01_mastnew->rpdini->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rpdini");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t01_mastnew->rpdini->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rpkini");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t01_mastnew->rpkini->FldCaption(), $t01_mastnew->rpkini->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rpkini");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t01_mastnew->rpkini->FldErrMsg()) ?>");

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
ft01_mastnewadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft01_mastnewadd.ValidateRequired = true;
<?php } else { ?>
ft01_mastnewadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft01_mastnewadd.Lists["x_norubm"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft01_mastnewadd.Lists["x_norubm"].Options = <?php echo json_encode($t01_mastnew->norubm->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t01_mastnew_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t01_mastnew_add->ShowPageHeader(); ?>
<?php
$t01_mastnew_add->ShowMessage();
?>
<form name="ft01_mastnewadd" id="ft01_mastnewadd" class="<?php echo $t01_mastnew_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t01_mastnew_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t01_mastnew_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t01_mastnew">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t01_mastnew_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t01_mastnew->norubm->Visible) { // norubm ?>
	<div id="r_norubm" class="form-group">
		<label id="elh_t01_mastnew_norubm" for="x_norubm" class="col-sm-2 control-label ewLabel"><?php echo $t01_mastnew->norubm->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t01_mastnew->norubm->CellAttributes() ?>>
<span id="el_t01_mastnew_norubm">
<select data-table="t01_mastnew" data-field="x_norubm" data-value-separator="<?php echo $t01_mastnew->norubm->DisplayValueSeparatorAttribute() ?>" id="x_norubm" name="x_norubm"<?php echo $t01_mastnew->norubm->EditAttributes() ?>>
<?php echo $t01_mastnew->norubm->SelectOptionListHtml("x_norubm") ?>
</select>
</span>
<?php echo $t01_mastnew->norubm->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t01_mastnew->noperm->Visible) { // noperm ?>
	<div id="r_noperm" class="form-group">
		<label id="elh_t01_mastnew_noperm" for="x_noperm" class="col-sm-2 control-label ewLabel"><?php echo $t01_mastnew->noperm->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t01_mastnew->noperm->CellAttributes() ?>>
<span id="el_t01_mastnew_noperm">
<input type="text" data-table="t01_mastnew" data-field="x_noperm" name="x_noperm" id="x_noperm" size="30" maxlength="4" placeholder="<?php echo ew_HtmlEncode($t01_mastnew->noperm->getPlaceHolder()) ?>" value="<?php echo $t01_mastnew->noperm->EditValue ?>"<?php echo $t01_mastnew->noperm->EditAttributes() ?>>
</span>
<?php echo $t01_mastnew->noperm->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t01_mastnew->naperm->Visible) { // naperm ?>
	<div id="r_naperm" class="form-group">
		<label id="elh_t01_mastnew_naperm" for="x_naperm" class="col-sm-2 control-label ewLabel"><?php echo $t01_mastnew->naperm->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t01_mastnew->naperm->CellAttributes() ?>>
<span id="el_t01_mastnew_naperm">
<input type="text" data-table="t01_mastnew" data-field="x_naperm" name="x_naperm" id="x_naperm" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t01_mastnew->naperm->getPlaceHolder()) ?>" value="<?php echo $t01_mastnew->naperm->EditValue ?>"<?php echo $t01_mastnew->naperm->EditAttributes() ?>>
</span>
<?php echo $t01_mastnew->naperm->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t01_mastnew->rpdini->Visible) { // rpdini ?>
	<div id="r_rpdini" class="form-group">
		<label id="elh_t01_mastnew_rpdini" for="x_rpdini" class="col-sm-2 control-label ewLabel"><?php echo $t01_mastnew->rpdini->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t01_mastnew->rpdini->CellAttributes() ?>>
<span id="el_t01_mastnew_rpdini">
<input type="text" data-table="t01_mastnew" data-field="x_rpdini" name="x_rpdini" id="x_rpdini" size="30" placeholder="<?php echo ew_HtmlEncode($t01_mastnew->rpdini->getPlaceHolder()) ?>" value="<?php echo $t01_mastnew->rpdini->EditValue ?>"<?php echo $t01_mastnew->rpdini->EditAttributes() ?>>
</span>
<?php echo $t01_mastnew->rpdini->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t01_mastnew->rpkini->Visible) { // rpkini ?>
	<div id="r_rpkini" class="form-group">
		<label id="elh_t01_mastnew_rpkini" for="x_rpkini" class="col-sm-2 control-label ewLabel"><?php echo $t01_mastnew->rpkini->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t01_mastnew->rpkini->CellAttributes() ?>>
<span id="el_t01_mastnew_rpkini">
<input type="text" data-table="t01_mastnew" data-field="x_rpkini" name="x_rpkini" id="x_rpkini" size="30" placeholder="<?php echo ew_HtmlEncode($t01_mastnew->rpkini->getPlaceHolder()) ?>" value="<?php echo $t01_mastnew->rpkini->EditValue ?>"<?php echo $t01_mastnew->rpkini->EditAttributes() ?>>
</span>
<?php echo $t01_mastnew->rpkini->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t01_mastnew_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t01_mastnew_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft01_mastnewadd.Init();
</script>
<?php
$t01_mastnew_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t01_mastnew_add->Page_Terminate();
?>
