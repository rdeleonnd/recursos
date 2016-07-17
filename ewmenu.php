<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(32, "mci_Actividades", $Language->MenuPhrase("32", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(8, "mi_comisiones", $Language->MenuPhrase("8", "MenuText"), "comisioneslist.php", 32, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}comisiones'), FALSE);
$RootMenu->AddMenuItem(19, "mi_permisos", $Language->MenuPhrase("19", "MenuText"), "permisoslist.php", 32, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}permisos'), FALSE);
$RootMenu->AddMenuItem(26, "mi_vacaciones", $Language->MenuPhrase("26", "MenuText"), "vacacioneslist.php", 32, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}vacaciones'), FALSE);
$RootMenu->AddMenuItem(106, "mci_Geografia", $Language->MenuPhrase("106", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(11, "mi_departamento_origen", $Language->MenuPhrase("11", "MenuText"), "departamento_origenlist.php", 106, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}departamento_origen'), FALSE);
$RootMenu->AddMenuItem(17, "mi_municipio", $Language->MenuPhrase("17", "MenuText"), "municipiolist.php", 106, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}municipio'), FALSE);
$RootMenu->AddMenuItem(22, "mi_sede", $Language->MenuPhrase("22", "MenuText"), "sedelist.php", 106, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}sede'), FALSE);
$RootMenu->AddMenuItem(78, "mci_Empresa", $Language->MenuPhrase("78", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(79, "mci_Organizacif3n", $Language->MenuPhrase("79", "MenuText"), "", 78, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mi_area", $Language->MenuPhrase("6", "MenuText"), "arealist.php", 79, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}area'), FALSE);
$RootMenu->AddMenuItem(10, "mi_departamento", $Language->MenuPhrase("10", "MenuText"), "departamentolist.php", 79, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}departamento'), FALSE);
$RootMenu->AddMenuItem(21, "mi_seccion", $Language->MenuPhrase("21", "MenuText"), "seccionlist.php", 79, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}seccion'), FALSE);
$RootMenu->AddMenuItem(20, "mi_puesto", $Language->MenuPhrase("20", "MenuText"), "puestolist.php", 79, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}puesto'), FALSE);
$RootMenu->AddMenuItem(63, "mci_Empleado", $Language->MenuPhrase("63", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(12, "mi_empleado", $Language->MenuPhrase("12", "MenuText"), "empleadolist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}empleado'), FALSE);
$RootMenu->AddMenuItem(18, "mi_nacionalidad", $Language->MenuPhrase("18", "MenuText"), "nacionalidadlist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}nacionalidad'), FALSE);
$RootMenu->AddMenuItem(64, "mci_Vehiculos", $Language->MenuPhrase("64", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(27, "mi_vehiculo", $Language->MenuPhrase("27", "MenuText"), "vehiculolist.php", 64, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}vehiculo'), FALSE);
$RootMenu->AddMenuItem(24, "mi_tipo_vehiculo", $Language->MenuPhrase("24", "MenuText"), "tipo_vehiculolist.php", 64, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}tipo_vehiculo'), FALSE);
$RootMenu->AddMenuItem(23, "mi_tipo_sangre", $Language->MenuPhrase("23", "MenuText"), "tipo_sangrelist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}tipo_sangre'), FALSE);
$RootMenu->AddMenuItem(68, "mci_Bancos", $Language->MenuPhrase("68", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mi_banco", $Language->MenuPhrase("7", "MenuText"), "bancolist.php", 68, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}banco'), FALSE);
$RootMenu->AddMenuItem(9, "mi_cuenta_bancaria", $Language->MenuPhrase("9", "MenuText"), "cuenta_bancarialist.php", 68, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}cuenta_bancaria'), FALSE);
$RootMenu->AddMenuItem(71, "mci_Viviendas", $Language->MenuPhrase("71", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(25, "mi_tipo_vivienda", $Language->MenuPhrase("25", "MenuText"), "tipo_viviendalist.php", 71, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}tipo_vivienda'), FALSE);
$RootMenu->AddMenuItem(28, "mi_vivienda", $Language->MenuPhrase("28", "MenuText"), "viviendalist.php", 71, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}vivienda'), FALSE);
$RootMenu->AddMenuItem(74, "mci_Historiales", $Language->MenuPhrase("74", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(14, "mi_historial_laboral", $Language->MenuPhrase("14", "MenuText"), "historial_laborallist.php", 74, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}historial_laboral'), FALSE);
$RootMenu->AddMenuItem(13, "mi_historial_clinico", $Language->MenuPhrase("13", "MenuText"), "historial_clinicolist.php", 74, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}historial_clinico'), FALSE);
$RootMenu->AddMenuItem(77, "mci_Informacif3n", $Language->MenuPhrase("77", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mi_afinidad", $Language->MenuPhrase("5", "MenuText"), "afinidadlist.php", 77, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}afinidad'), FALSE);
$RootMenu->AddMenuItem(16, "mi_informacion_familiar", $Language->MenuPhrase("16", "MenuText"), "informacion_familiarlist.php", 77, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}informacion_familiar'), FALSE);
$RootMenu->AddMenuItem(15, "mi_informacion_academica", $Language->MenuPhrase("15", "MenuText"), "informacion_academicalist.php", 77, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}informacion_academica'), FALSE);
$RootMenu->AddMenuItem(108, "mi_sexo", $Language->MenuPhrase("108", "MenuText"), "sexolist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}sexo'), FALSE);
$RootMenu->AddMenuItem(107, "mi_estado_civil", $Language->MenuPhrase("107", "MenuText"), "estado_civillist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}estado_civil'), FALSE);
$RootMenu->AddMenuItem(4, "mci_Administrar_Usuarios", $Language->MenuPhrase("4", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(1, "mi_user", $Language->MenuPhrase("1", "MenuText"), "userlist.php", 4, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}user'), FALSE);
$RootMenu->AddMenuItem(2, "mi_userlevel", $Language->MenuPhrase("2", "MenuText"), "userlevellist.php", 4, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
