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

$t01_mastnew_delete = NULL; // Initialize page object first

class ct01_mastnew_delete extends ct01_mastnew {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{C65252F3-D17C-4838-BE42-BEF263C3CA1E}";

	// Table name
	var $TableName = 't01_mastnew';

	// Page object name
	var $PageObjName = 't01_mastnew_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("t01_mastnewlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t01_mastnew class, t01_mastnewinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("t01_mastnewlist.php"); // Return to list
			}
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t01_mastnewlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t01_mastnew_delete)) $t01_mastnew_delete = new ct01_mastnew_delete();

// Page init
$t01_mastnew_delete->Page_Init();

// Page main
$t01_mastnew_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t01_mastnew_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft01_mastnewdelete = new ew_Form("ft01_mastnewdelete", "delete");

// Form_CustomValidate event
ft01_mastnewdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft01_mastnewdelete.ValidateRequired = true;
<?php } else { ?>
ft01_mastnewdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft01_mastnewdelete.Lists["x_norubm"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft01_mastnewdelete.Lists["x_norubm"].Options = <?php echo json_encode($t01_mastnew->norubm->Options()) ?>;

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
<?php $t01_mastnew_delete->ShowPageHeader(); ?>
<?php
$t01_mastnew_delete->ShowMessage();
?>
<form name="ft01_mastnewdelete" id="ft01_mastnewdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t01_mastnew_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t01_mastnew_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t01_mastnew">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t01_mastnew_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $t01_mastnew->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t01_mastnew->norubm->Visible) { // norubm ?>
		<th><span id="elh_t01_mastnew_norubm" class="t01_mastnew_norubm"><?php echo $t01_mastnew->norubm->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t01_mastnew->noperm->Visible) { // noperm ?>
		<th><span id="elh_t01_mastnew_noperm" class="t01_mastnew_noperm"><?php echo $t01_mastnew->noperm->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t01_mastnew->naperm->Visible) { // naperm ?>
		<th><span id="elh_t01_mastnew_naperm" class="t01_mastnew_naperm"><?php echo $t01_mastnew->naperm->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t01_mastnew->rpdini->Visible) { // rpdini ?>
		<th><span id="elh_t01_mastnew_rpdini" class="t01_mastnew_rpdini"><?php echo $t01_mastnew->rpdini->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t01_mastnew->rpkini->Visible) { // rpkini ?>
		<th><span id="elh_t01_mastnew_rpkini" class="t01_mastnew_rpkini"><?php echo $t01_mastnew->rpkini->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t01_mastnew_delete->RecCnt = 0;
$i = 0;
while (!$t01_mastnew_delete->Recordset->EOF) {
	$t01_mastnew_delete->RecCnt++;
	$t01_mastnew_delete->RowCnt++;

	// Set row properties
	$t01_mastnew->ResetAttrs();
	$t01_mastnew->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t01_mastnew_delete->LoadRowValues($t01_mastnew_delete->Recordset);

	// Render row
	$t01_mastnew_delete->RenderRow();
?>
	<tr<?php echo $t01_mastnew->RowAttributes() ?>>
<?php if ($t01_mastnew->norubm->Visible) { // norubm ?>
		<td<?php echo $t01_mastnew->norubm->CellAttributes() ?>>
<span id="el<?php echo $t01_mastnew_delete->RowCnt ?>_t01_mastnew_norubm" class="t01_mastnew_norubm">
<span<?php echo $t01_mastnew->norubm->ViewAttributes() ?>>
<?php echo $t01_mastnew->norubm->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t01_mastnew->noperm->Visible) { // noperm ?>
		<td<?php echo $t01_mastnew->noperm->CellAttributes() ?>>
<span id="el<?php echo $t01_mastnew_delete->RowCnt ?>_t01_mastnew_noperm" class="t01_mastnew_noperm">
<span<?php echo $t01_mastnew->noperm->ViewAttributes() ?>>
<?php echo $t01_mastnew->noperm->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t01_mastnew->naperm->Visible) { // naperm ?>
		<td<?php echo $t01_mastnew->naperm->CellAttributes() ?>>
<span id="el<?php echo $t01_mastnew_delete->RowCnt ?>_t01_mastnew_naperm" class="t01_mastnew_naperm">
<span<?php echo $t01_mastnew->naperm->ViewAttributes() ?>>
<?php echo $t01_mastnew->naperm->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t01_mastnew->rpdini->Visible) { // rpdini ?>
		<td<?php echo $t01_mastnew->rpdini->CellAttributes() ?>>
<span id="el<?php echo $t01_mastnew_delete->RowCnt ?>_t01_mastnew_rpdini" class="t01_mastnew_rpdini">
<span<?php echo $t01_mastnew->rpdini->ViewAttributes() ?>>
<?php echo $t01_mastnew->rpdini->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t01_mastnew->rpkini->Visible) { // rpkini ?>
		<td<?php echo $t01_mastnew->rpkini->CellAttributes() ?>>
<span id="el<?php echo $t01_mastnew_delete->RowCnt ?>_t01_mastnew_rpkini" class="t01_mastnew_rpkini">
<span<?php echo $t01_mastnew->rpkini->ViewAttributes() ?>>
<?php echo $t01_mastnew->rpkini->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t01_mastnew_delete->Recordset->MoveNext();
}
$t01_mastnew_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t01_mastnew_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft01_mastnewdelete.Init();
</script>
<?php
$t01_mastnew_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t01_mastnew_delete->Page_Terminate();
?>
