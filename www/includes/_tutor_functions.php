<?php

/* 
 * Functions for managing tutors
 *
 *
 */

// Tutor user functions
require 'includes/_user_functions.php';

// Add tutor
function add_tutor($con, $new_tutor_name, $new_tutor_second_name, $new_tutor_lastname,  $new_tutor_second_lastname, $new_tutor_gender, $new_tutor_role, $new_tutor_notes, $new_tutor_date_added, $new_tutor_user_id) {

	$alerts_local = array();

	// Prepare the query with the new data
	$new_tutor_query = "INSERT INTO tutor (tutor_name, tutor_second_name, tutor_lastname, tutor_second_lastname, tutor_gender, tutor_role, tutor_notes, tutor_picture, tutor_date_added, tutor_user_id) VALUES (
		'".$new_tutor_name."',
		'".$new_tutor_second_name."',
		'".$new_tutor_lastname."',
		'".$new_tutor_second_lastname."',
		'".$new_tutor_gender."',
		'".$new_tutor_role."',
		'".$new_tutor_notes."',
		'',
		'".$new_tutor_date_added."',
		".$new_tutor_user_id.")";

	if (!@mysqli_query($con, $new_tutor_query)) {
		$alerts[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "No se pudo agregar tutor." . $new_tutor_query.@mysqli_error($con)
		);
	}

	return (empty($alerts_local) ? null : $alerts_local);
}

// Edit tutor information

// Delete tutor
function delete_tutor($con, $tutor_id) {

	$alerts_local = array();

	$delete_tutor_query = "DELETE FROM tutor WHERE tutor_id = '".$tutor_id."' "; 
	
	if(!@mysqli_query($con, $delete_tutor_query)){
		$alerts[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "No se pudo borrar al tutor seleccionado."
		);
	}	

	return (empty($alerts_local) ? null : $alerts_local);
}

// Select tutor
function select_tutor_with_id($con, $tutor_id) {

	$result = null;

	$select_tutor_query = "SELECT * FROM tutor WHERE tutor_id='".$tutor_id."'";

	$r = @mysqli_query($con, $select_tutor_query);

	if (!@mysqli_num_rows($r)==1) {
		$result = false;
	} else {
		$result = mysqli_fetch_array($r);
	}

	return $result;
}

// Add tutor email
function add_emailtutor($con, $email_address, $email_type, $tutor_id) {

	$alerts_local = array();

	$new_emailtutor_query = "INSERT INTO emailtutor (emailtutor_address, emailtutor_type, emailtutor_primary, emailtutor_tutor_id) VALUES (
		'".$email_address."',
		'".$email_type."',
		null,
		'".$tutor_id."')";
	
	if (!@mysqli_query($con, $new_emailtutor_query)) {
		$alerts_local[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "No se pudo agregar correo ".$email
		);
	}

	return (empty($alerts_local) ? null : $alerts_local);
}

// Edit tutor email

// Delete tutor email
function delete_emailtutor($con, $emailtutor_id) {

	$alerts_local = array();

	$delete_emailtutor_query = "DELETE FROM emailtutor WHERE emailtutor_id='".$emailtutor_id."'";

	if (!@mysqli_query($con, $delete_emailtutor_query)) {
		$alerts_local[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "No se pudo borrar correo electrónico de tutor."
			);
	}

	return (empty($alerts_local) ? null : $alerts_local);
}

// Add tutor phone
function add_phonetutor($con, $phone_number, $phone_type, $tutor_id) {

	$alerts_local = array();

	$new_phonetutor_query = "INSERT INTO phonetutor (phonetutor_number, phonetutor_type, phonetutor_primary, phonetutor_tutor_id) VALUES (
		'".$phone_number."',
		'".$phone_type."',
		null,
		'".$tutor_id."')";

	if (!@mysqli_query($con, $new_phonetutor_query)) {
		$alerts_local[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "No se pudo agregar teléfono ".$phone
		);
	}

	return (empty($alerts_local) ? null : $alerts_local);
}

// Edit tutor phone

// Delete tutor phone
function delete_phonetutor($con, $phonetutor_id) {

	$alerts_local = array();

	$delete_phonetutor_query = "DELETE FROM phonetutor WHERE phonetutor_id='".$phonetutor_id."'";

	if (!@mysqli_query($con, $delete_phonetutor_query)) {
		$alerts_local[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "No se pudo borrar teléfono de tutor."
			);
	}

	return (empty($alerts_local) ? null : $alerts_local);
}

// Delete tutor contact information
function delete_tutor_contact_information($con, $tutor_id) {

	$alerts_local = array();

	$delete_emailtutor_query = "DELETE FROM emailtutor WHERE emailtutor_tutor_id = '".$tutor_id."'";
	if(!@mysqli_query($con, $delete_emailtutor_query)){
		$alerts_local[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "No se pudieron borrar los correos electrónicos asociados al tutor."
		);
	}

	$delete_phonetutor_query = "DELETE FROM phonetutor WHERE phonetutor_tutor_id = '".$tutor_id."'";
	if(!@mysqli_query($con, $delete_phonetutor_query)){
		$alerts_local[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "No se pudieron borrar los números telefónicos asociados al tutor."
		);
	}

	return (empty($alerts_local) ? null : $alerts_local);
}