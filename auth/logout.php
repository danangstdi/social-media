<?php

session_start();

session_unset();
session_destroy();

require_once '../apps/config.php';
header('Location: ' . BASE_URL . '/auth/login/');