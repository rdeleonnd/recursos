<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(32, "mmci_Actividades", $Language->MenuPhrase("32", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(8, "mmi_comisiones", $Language->MenuPhrase("8", "MenuText"), "comisioneslist.php", 32, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}comisiones'), FALSE);
$RootMenu->AddMenuItem(19, "mmi_permisos", $Language->MenuPhrase("19", "MenuText"), "permisoslist.php", 32, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}permisos'), FALSE);
$RootMenu->AddMenuItem(26, "mmi_vacaciones", $Language->MenuPhrase("26", "MenuText"), "vacacioneslist.php", 32, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}vacaciones'), FALSE);
$RootMenu->AddMenuItem(106, "mmci_Geografia", $Language->MenuPhrase("106", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(11, "mmi_departamento_origen", $Language->MenuPhrase("11", "MenuText"), "departamento_origenlist.php", 106, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}departamento_origen'), FALSE);
$RootMenu->AddMenuItem(17, "mmi_municipio", $Language->MenuPhrase("17", "MenuText"), "municipiolist.php", 106, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}municipio'), FALSE);
$RootMenu->AddMenuItem(22, "mmi_sede", $Language->MenuPhrase("22", "MenuText"), "sedelist.php", 106, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}sede'), FALSE);
$RootMenu->AddMenuItem(78, "mmci_Empresa", $Language->MenuPhrase("78", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(79, "mmci_Organizacif3n", $Language->MenuPhrase("79", "MenuText"), "", 78, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mmi_area", $Language->MenuPhrase("6", "MenuText"), "arealist.php", 79, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}area'), FALSE);
$RootMenu->AddMenuItem(10, "mmi_departamento", $Language->MenuPhrase("10", "MenuText"), "departamentolist.php", 79, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}departamento'), FALSE);
$RootMenu->AddMenuItem(21, "mmi_seccion", $Language->MenuPhrase("21", "MenuText"), "seccionlist.php", 79, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}seccion'), FALSE);
$RootMenu->AddMenuItem(20, "mmi_puesto", $Language->MenuPhrase("20", "MenuText"), "puestolist.php", 79, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}puesto'), FALSE);
$RootMenu->AddMenuItem(63, "mmci_Empleado", $Language->MenuPhrase("63", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(12, "mmi_empleado", $Language->MenuPhrase("12", "MenuText"), "empleadolist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}empleado'), FALSE);
$RootMenu->AddMenuItem(18, "mmi_nacionalidad", $Language->MenuPhrase("18", "MenuText"), "nacionalidadlist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}nacionalidad'), FALSE);
$RootMenu->AddMenuItem(64, "mmci_Vehiculos", $Language->MenuPhrase("64", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(27, "mmi_vehiculo", $Language->MenuPhrase("27", "MenuText"), "vehiculolist.php", 64, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}vehiculo'), FALSE);
$RootMenu->AddMenuItem(24, "mmi_tipo_vehiculo", $Language->MenuPhrase("24", "MenuText"), "tipo_vehiculolist.php", 64, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}tipo_vehiculo'), FALSE);
$RootMenu->AddMenuItem(23, "mmi_tipo_sangre", $Language->MenuPhrase("23", "MenuText"), "tipo_sangrelist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}tipo_sangre'), FALSE);
$RootMenu->AddMenuItem(68, "mmci_Bancos", $Language->MenuPhrase("68", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mmi_banco", $Language->MenuPhrase("7", "MenuText"), "bancolist.php", 68, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}banco'), FALSE);
$RootMenu->AddMenuItem(9, "mmi_cuenta_bancaria", $Language->MenuPhrase("9", "MenuText"), "cuenta_bancarialist.php", 68, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}cuenta_bancaria'), FALSE);
$RootMenu->AddMenuItem(71, "mmci_Viviendas", $Language->MenuPhrase("71", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(25, "mmi_tipo_vivienda", $Language->MenuPhrase("25", "MenuText"), "tipo_viviendalist.php", 71, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}tipo_vivienda'), FALSE);
$RootMenu->AddMenuItem(28, "mmi_vivienda", $Language->MenuPhrase("28", "MenuText"), "viviendalist.php", 71, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}vivienda'), FALSE);
$RootMenu->AddMenuItem(74, "mmci_Historiales", $Language->MenuPhrase("74", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(14, "mmi_historial_laboral", $Language->MenuPhrase("14", "MenuText"), "historial_laborallist.php", 74, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}historial_laboral'), FALSE);
$RootMenu->AddMenuItem(13, "mmi_historial_clinico", $Language->MenuPhrase("13", "MenuText"), "historial_clinicolist.php", 74, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}historial_clinico'), FALSE);
$RootMenu->AddMenuItem(77, "mmci_Informacif3n", $Language->MenuPhrase("77", "MenuText"), "", 63, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mmi_afinidad", $Language->MenuPhrase("5", "MenuText"), "afinidadlist.php", 77, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}afinidad'), FALSE);
$RootMenu->AddMenuItem(16, "mmi_informacion_familiar", $Language->MenuPhrase("16", "MenuText"), "informacion_familiarlist.php", 77, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}informacion_familiar'), FALSE);
$RootMenu->AddMenuItem(15, "mmi_informacion_academica", $Language->MenuPhrase("15", "MenuText"), "informacion_academicalist.php", 77, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}informacion_academica'), FALSE);
$RootMenu->AddMenuItem(108, "mmi_sexo", $Language->MenuPhrase("108", "MenuText"), "sexolist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}sexo'), FALSE);
$RootMenu->AddMenuItem(107, "mmi_estado_civil", $Language->MenuPhrase("107", "MenuText"), "estado_civillist.php", 63, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}estado_civil'), FALSE);
$RootMenu->AddMenuItem(4, "mmci_Administrar_Usuarios", $Language->MenuPhrase("4", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(1, "mmi_user", $Language->MenuPhrase("1", "MenuText"), "userlist.php", 4, "", AllowListMenu('{8127A4B8-77E3-4A91-B475-0A98E2BB4762}user'), FALSE);
$RootMenu->AddMenuItem(2, "mmi_userlevel", $Language->MenuPhrase("2", "MenuText"), "userlevellist.php", 4, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
