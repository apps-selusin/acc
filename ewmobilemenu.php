<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(8, "mmi_cf01_home_php", $Language->MenuPhrase("8", "MenuText"), "cf01_home.php", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(1, "mmi_t01_mastnew", $Language->MenuPhrase("1", "MenuText"), "t01_mastnewlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
