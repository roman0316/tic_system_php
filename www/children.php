<?php

/*
 * CHILDREN.PHP
 * 
 * DESC: THIS IS THE WEBAPP CHILDREN PAGE
 * (ONLY AVAILABLE TO  ADMIN)
 * AUTHOR: RICARDO ROMAN (C) 2014
 * 
 * CHANGE LOG
 * 20140228 - RICARDO ROMAN - FIRST RELEASE
 * 
 */
require 'includes/constants.php';

// Check if user is logged in
if (!isset($_COOKIE['user_id'])) {
	require 'includes/sign_in_functions.php';
	redirect_user('sign_in.php');
} 
// If the user is logged in, display the children page

// Require connection
require '../connection.php';

// Utilities
require 'includes/semester_functions.php';



// Include child specific functions
require 'includes/_user_functions.php';
require 'includes/_child_functions.php';

// Prepare alert array in case of alerts
$alerts = array();

/*
* Form functions
*/ 
// Add child
if (isset($_POST['child_new'])) {

	// Prepare data for insert query
	$new_child_name = $_POST['child_new_name'];
	$new_child_second_name = $_POST['child_new_second_name'];
	$new_child_lastname = $_POST['child_new_lastname'];
	$new_child_second_lastname = $_POST['child_new_second_lastname'];
	$new_child_gender = $_POST['child_new_gender'];
	$new_child_birthdate = $_POST['child_new_birthdate'];
	$new_child_allergies = $_POST['child_new_allergies'];
	$new_child_medical_notes = $_POST['child_new_medical_notes'];
	$new_child_general_notes = $_POST['child_new_general_notes'];
	$new_child_date_added = date("Y-m-d H:i:s", time());

	// With data ready, add child
	$continue = true;

	$results_alerts = add_child($con, $new_child_name, $new_child_second_name, $new_child_lastname,  $new_child_second_lastname, $new_child_gender, $new_child_birthdate, $new_child_allergies, $child_new_medical_notes, $child_new_general_notes, $new_child_date_added);

	$new_child_index = mysqli_insert_id($con);

	if (!empty($results_alerts)) {
		foreach ($results_alerts as $ra) {
			$alerts[] = $ra;	
		}
		$continue = false;
	}

	if($continue) {
		$alerts[] = array(
			"status" => "success",
			"subject" => "¡Enhorabuena!",
			"message" => "Se ha inscrito un nuevo niño."
		);

		// If successful, then assiciate to the corresponding tutor
		$tutors = $_POST['child_new_tutor'];

		foreach ($tutors as $tutor) {
			$pieces_tutor = explode(" ", $tutor);
			$results_alerts = assign_child_to_tutor($con, $new_child_index, $pieces_tutor[0]);
			if (!empty($results_alerts)) {
				foreach ($results_alerts as $ra) {
					$alerts[] = $ra;	
				}
			}
		}

	}

}

// Edit child -- This function will be in the detail page.

// Delete child
if (isset($_GET['child_delete'])) {

	/*
	 * This will delete the child in the following sequence:
	 *   First delete contact information (emails and phones)
	 *   Delete the child
	 *   Delete associated users
	 */

	$child_id = $_GET['child_id'];

	$child_row = select_child_with_id($con, $child_id);

	if (!$child_row) {
		$alerts[] = array(
			"status" => "danger",
			"subject" => "¡Error!",
			"message" => "Niño no encontrado."
		);
	} else {

		// Delete tutor associations
		$results_alerts = delete_childtutors_of_child($con, $child_row['child_id']);
		if (!empty($results_alerts)) {
			foreach ($results_alerts as $ra) {
				$alerts[] = $ra;	
			}	
		}

		// Delete the child
		$results_alerts = delete_child($con, $child_row['child_id']);
		if (!empty($results_alerts)) {
			foreach ($results_alerts as $ra) {
				$alerts[] = $ra;	
			}	
		}

	}

	if (empty($alerts)) {
		$alerts[] = array(
			"status" => "success",
			"subject" => "¡Enhorabuena!",
			"message" => "Se ha borrado del sistema al niño seleccionado."
		);
	}
}

// Start with header and title
$page_title = 'Niños';
$page_active = 'children';
include 'includes/_header.php';

// Display the menu according to user type
include 'includes/_menu.php';

// Main contents of page
?>
<div class='container'>
	<div class="row">
		<div class="col-sm-2">
			<br>	
			<? include 'includes/_sidebar.php'; ?>
		</div>
		<div class="tic-sidebar col-sm-10">
			<h2>Administracion de niños inscritos</h2>
			<a class="btn btn-success btn-sm pull-right" href="#child_new_form_modal" data-toggle="modal">Inscribir niño</a>
			<br>
			<br>
			<div id="children_alerts_div">
				<? if (isset($alerts) && !empty($alerts)) { ?>
					<? foreach ($alerts as $alert) { ?>
						<br>
						<div class="alert alert-<? echo $alert['status']; ?>">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<strong><? echo $alert['subject']; ?></strong> <? echo $alert['message']; ?>
						</div>
					<? } ?>
				<? } ?>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="child_new_form_modal" tabindex="-1" role="dialog" aria-labelledby="child_new_form_modal_label" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        <h3 class="modal-title" id="child_new_form_modal_label">Inscribir niño</h3>
			      </div> <!-- Close modal header -->
			      <div class="modal-body">
			      	<br>
			        <? include 'includes/_child_new_form.php'; ?>
			      </div> <!-- Close modal body -->
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			      </div> <!-- Close modal footer -->
			    </div><!-- Close modal content -->
			  </div> <!-- Close modal dialog -->
			</div> <!-- Close modal -->
			<br>
			<div id="child_table_div">
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<th>ID</th>
						<th width="35%">Nombre</th>
						<th>Género</th>
						<th>Fecha de ingreso</th>
						<th>¿Inscrito?</th>
						<th width="5%" class="text-danger">BORRAR</th>
					</thead>
					<tbody>
						<?
							if ($_COOKIE['user_type'] == TUTOR_STRING) {
								$current_tutor = select_tutor_with_user_id($con, $_COOKIE['user_id']);
								$query = "SELECT * FROM child, childtutor WHERE childtutor.childtutor_tutor_id ='".$current_tutor['tutor_id']."' AND childtutor.childtutor_child_id = child_id ORDER BY child_lastname";
								$child_result = mysqli_query($con, $query);
								while($child_row = mysqli_fetch_array($child_result)) {
									include 'includes/_child_table_row.php';
								}
							} else {
								$query = "SELECT * FROM child ORDER BY child_lastname";
								$child_result = mysqli_query($con, $query);
								while($child_row = mysqli_fetch_array($child_result)) {
									include 'includes/_child_table_row.php';
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div> <? // Close the column ?>
	</div> <? // Close the row ?>
</div><? // Close the container ?>
<? 
// Close any open connections
mysqli_close($con);

// Footer
include 'includes/_footer.php';
?>