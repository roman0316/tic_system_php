<tr>
	<td><? echo $tutor_row['tutor_id']; ?></td>
	<td>
		<a href="tutor_detail.php?tutor_id=<? echo $tutor_row['tutor_id']; ?>">
			<? 
				$tutor_name_str = "";
				$tutor_name_str .= $tutor_row['tutor_lastname'];
				if (isset($tutor_row['tutor_second_lastname']) && $tutor_row['tutor_second_lastname'] !== "") {
					$tutor_name_str .= " ".$tutor_row['tutor_second_lastname'].", ";
				} else {
					$tutor_name_str .= ", ";
				}
				$tutor_name_str .= $tutor_row['tutor_name'];
				if (isset($tutor_row['tutor_second_name']) && $tutor_row['tutor_second_name'] !== "") {
					$tutor_name_str .= " ".$tutor_row['tutor_second_name'];
				}
				echo $tutor_name_str;
			?>
		</a>
	</td>
	<td>
		<?
			if ($tutor_row['tutor_gender'] == 'M') {
				echo 'H';
			} else {
				echo 'M';
			}
		?>
	</td>
	<td>
		<?
			echo $tutor_row['tutor_role'];
		?>
	</td>
	<td><? echo $tutor_row['tutor_date_added']; ?></td>
	<td>
		<?
			$tutor_user_query = "SELECT * FROM user WHERE user.user_id='".$tutor_row['tutor_user_id']."'";
			$tutor_user_result = mysqli_query($con, $tutor_user_query);
			if (mysqli_num_rows($tutor_user_result)==1) {
				$tutor_user_row = mysqli_fetch_array($tutor_user_result);
				if ($tutor_user_row['user_active']==1) {
					echo "Sí";
				} else {
					echo "No";
				}
			} else {
				echo "N/A";
			}
		?>
	</td>
	<td>
		<a href="#tutor_confirm_delete_<?=$tutor_row['tutor_id'];?>" class="btn btn-danger btn-xs" data-toggle="modal">BORRAR</a>
		<!-- Modal -->
		<div class="modal fade" id="tutor_confirm_delete_<?=$tutor_row['tutor_id'];?>" tabindex="-1" role="dialog" aria-labelledby="tutor_new_form_modal_label" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h3 class="modal-title text-danger" id="tutor_new_form_modal_label">¡Alerta!</h3>
		      </div> <!-- Close modal header -->
		      <div class="modal-body">
		      	<p class="lead">¿Realmente desea borrar al tutor <?= $tutor_name_str; ?>?</p>
		      	<br>
		      	<form class="form-inline" role="form" method="POST" action="<?php $_PHP_SELF ?>">
		      		<input type="text" id="tutor_delete" name="tutor_delete" value="tutor_delete" style="display: none;">
		        	<input type="text" id="tutor_id" name="tutor_id" value="<? echo $tutor_row['tutor_id']; ?>" style="display: none;">
		        	<button type="submit" class="btn btn-danger pull-left">Borrar Tutor</button>
		        	<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
		    	</form>
		    	<br>
		    	<br>
		      </div> <!-- Close modal body -->
		     
		    </div><!-- Close modal content -->
		  </div> <!-- Close modal dialog -->
		</div> <!-- Close modal -->
	</td>
</tr>