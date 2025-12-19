<?php
/**
 * Script de limpieza de sesión (Emergency Logout)
 */
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/'); // Borrar cookie de sesión
header("Location: login.php?msg=cleared");
exit;
?>
