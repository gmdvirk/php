<?php include_once 'header.php'; ?>
	<div class="container">
		<table id="datatable" class="table table-striped">
			<thead>
				<th>User ID</th>
				<th>Query</th>
				<th>Offset</th>
				<th>Pages Searched</th>
				<th>Search Type</th>
				<th>No. of Results Fetched</th>
				<th>No. of stored Results</th>
				<th>Date</th>
				<th>Results</th>
			</thead>
		<tbody>
	<?php
	if(isset($data)){
		// echo "<pre>";print_r($data);die;
		if(is_array($data) && !empty($data))
		{
			foreach($data as $key=>$val){
				?>
				<tr>
				<td><?= $val->id; ?></td>
				<td><?= $val->query; ?></td>
				<td><?= $val->offset; ?></td>
				<td><?= $val->pages_searched; ?></td>
				<td><?= $val->search_type; ?></td>
				<td><?= $val->no_of_results; ?></td>
				<td><?= $val->Stored; ?></td>
				<td><?= $val->date; ?></td>
				<?php if(!empty($val->Stored)): ?>
					<td><a href="<?= base_url()."log/get/" .$val->id; ?>/"<?=$val->search_type;?>>Click Here</a></td>
				<?php else: ?>
					<td>No Result</td>
				<?php endif; ?>
				</tr>
				<?php

			}
		}
	}
	else{
		if($this->session->flashdata('norecord')){
			?>
			<tr>
				<td colspan="5"><?= $this->session->flashdata('norecord'); ?></td>
			</tr>
			<?php
		}
	}	
	?>
		</tbody>
		</table>
		<?php
			// if(!empty($data['links'] && is_array($data['links']))){
			// 	echo '<ul class="pagination">';
			// 	foreach ($data['links'] as $key => $link) {
			// 		echo "<li  class='page-item'>".$link."</li>";
			// 	}
			// 	echo "</ul>";
			// }
		?>
		</div>
		<script>
			$(document).ready(function(){
		$('#datatable').DataTable({
			dom: "<'row'<'col-sm-3'l><'col-sm-2'B><'col-sm-3'i><'col-sm-4'f>>" +
     "<'row'<'col-sm-12'tr>>" +
     "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			 buttons: [
			 {
            		extend: 'csv',
            		filename: 'Results',
            		text: 'Download CSV',
            		className: 'btn btn-outline-secondary',
            		exportOptions: {
			            columns: 'th:not(:last-child)'
			        },
			 }],
			 'sPaginationType': "full_numbers",
			"language":{
				"searchPlaceholder":"Search In Results"
			}
		});
		// $("#datatable_info").clone().prependTo('#datatable_wrapper');
	});
		</script>
<?php include_once 'footer.php';
