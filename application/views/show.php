<?php
$permissions = explode("|",$config['configuration']['tables'][$table_name]['permission']);
?>
<script type="text/javascript">
    function confirmDelete() {
	
	return false;
    }
</script>
<table class="table table-striped">
    <thead>
        <tr>
        <?php 
	        foreach($data['map'] as $key => $val) {
				echo "<th>{$val}</th>";
			}
        ?>
        <th>
        	<?php 
        	if(in_array("insert", $permissions)) echo anchor("create/f/{$table_name}/","New",array('class'=>'btn btn-primary')); 
			?>
        </a></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    foreach($data['list'] as $list) {
		$delete_link = ""; $update_link = "";
		if(in_array("update", $permissions)) $update_link = anchor('edit/f/' . $table_name.'/'.$list['id'],'_', array('class'=>'icon-pencil'));
		if(in_array("delete", $permissions)) $delete_link = anchor('delete/f/' . $table_name.'/'.$list['id'],'_', array('class'=>'icon-trash',"onclick"=>"return confirm('Are you sure you want to delete this listing?')"));
		echo "<tr>";
			foreach($data['map'] as $k=>$v) {
				$charLength = $config['configuration']['tables'][$table_name]['fields'][$k]['list-length'];
				echo (strlen($list[$k]) > $charLength)? "<td>" . substr(strip_tags($list[$k]), 0, $charLength) . "...</td>":
							"<td>" . $list[$k] . "</td>";
			}
			echo "<td>$update_link | $delete_link<td></tr>";
	}    
    ?>
    </tbody></table>
