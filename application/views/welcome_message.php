<script type="text/javascript" src="<?= base_url() ?>/public/bootstrap/jquery.jqdock.min.js"></script>
<style type="text/stylesheet">
	#description {margin-left:40px; width:490px;}
	#page {padding:160px 0 20px; width:100%;}
	#dock {position:absolute; top:0; left:0; width:100%; display:none;}
	/*...set the cursor...*/
	/*label styling...*/
	div.jqDockLabel {font-weight:bold; font-style:italic; white-space:nowrap; color:#ffffff; cursor:pointer; padding:0 1px;}
	#menu1 div.jqDockLabel {padding:0 8px 5px 1px;}
</style>
<script type="text/javascript">
	var dockOption = {
		align:'top',
		size: 80,
		labels: true 
	}
	$(function(){
		//$("#dock").children().click(function(){alert("quack")});
		$('#dock').jqDock(dockOption);
	});
</script>
<div id="page">
	<div id="dock">
	<?php
	foreach($config['configuration']['tables'] as $name=>$table) {
		$img_url = base_url() . $table['icon'];
		$img_html =  "<img src='{$img_url}' title='{$table['tip']}' />";
		echo anchor("show/f/{$name}", $img_html);
	}
	?>
	</div>
</div>