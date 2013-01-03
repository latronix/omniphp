<?php

/**
 * Absolute Models (configuration, global variables, db abstraction layer)
 */
require_once("model_globals.php"); //always include first
require_once("model_framework_exception.php");
require_once("model_framework_sql.php");
require_once("model_framework_ui.php");

/**
 * Models for Entities
 */
require_once("model_container.php"); //entity for all columns in all claims tables
require_once("model_validations.php"); //model to handle all validations and exceptions

?>
