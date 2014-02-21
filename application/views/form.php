<style type="text/css">
.btn-closable {
	margin: 0 2px;
	background: url('/public/img/icons/close.gif') no-repeat;
}

</style>
<?PHP
if(isset($validationFailed) || !empty($error)) { ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <? 
            echo validation_errors();
            foreach($errors as $e) {
            	echo "<p>{$e}</p>";
            }
        ?>
    </div>
<? } ?>
<form enctype="multipart/form-data" method="post" action="<?php echo base_url(); ?>index.php/<?= (isset($params['id']))?'edit':'create' ?>/f/<?php echo singular($table_name); ?>"
  onsubmit="return validateForm()">
    <legend><?= (isset($params['id']))?'Edit ' . singular($table_name):'New '. singular($table_name) ?> Form</legend>
    <ul style="list-style: none">
    	<?php
    	$loadCkEditor = "";
    	$fileFields = Array();
    	foreach($widgets as $widget) {
			$required_flag = ($widget['required'])?"<e style='color: red'>*</e>":"";
			$widgetHtml = "";
			switch($widget['widget']) {
				case "textfield":
					$widgetHtml = form_input(array(
					'name'=>$widget['name'],
					'value'=>(isset($params[$widget['name']]))?$params[$widget['name']]:""
					));
					break;
				case "textarea":
					$widgetHtml = form_textarea(
					array(
					'name'=>$widget['name'],
					'value'=>(isset($params[$widget['name']]))?$params[$widget['name']]:""
					));
					break;
				case "richtext":
					$widgetHtml = form_textarea(
					array(
					'name'=>$widget['name'],
					'value'=>(isset($params[$widget['name']]))?$params[$widget['name']]:"",
					'class'=>'richtext',
					'id'=>'richtext'
					));
					$loadCkEditor = $widget['name'];
					break;
				case "file":
					$widgetHtml = form_upload(array('name'=>$widget['name'], 'class'=>'file-field'));
					if(isset($params['id']))	$fileFields[$widget['name']] = $params[$widget['name']];
					break;
				case "combobox":
					$widgetHtml = form_dropdown($widget['name'], $widget['options'],'0', "mode='{$widget['mode']}' onChange='itemSelected(this)'");
					break;
				default:
					$widgetHtml = form_input($widget['name']);
					break;
			}
			echo "<li>
					<label>{$widget['label']} {$required_flag}</label>
					{$widgetHtml}
					<li>";
		}
		if(isset($params['id'])) echo form_hidden("id", $params['id']);
    	?>

        <li>
            <input type="submit" name="submit" value="<? echo isset($params['id'])?"Update":"Create" ?> <?php echo singular($table_name); ?>" class="btn">
        </li>
    </ul>
</form>


	<?php
	if(isset($params['id'])) {
		foreach($fileFields as $field=>$file) {
	// 	echo strtolower(get_mime_by_extension($file));
			$imgUrl = base_url() . "public/". $widgets[$field]['config']['url_path'] ."/icons/file.png";
			$imageExtensions = array('jpg','jpeg','gif','tif','png','svg');
			if(strstr(get_mime_by_extension($file), "image/")) {
				$farray = explode(".", $file);
				$thumb_ext = "_thumb." . array_pop($farray);
				$file = implode($farray) . $thumb_ext;
				$folder = $public_url . "/public/" . $config['configuration']['tables'][$table_name]['fields'][$field]['config']['url_path'];
				$imgUrl = $folder . "/" . $file;
			}
			
			echo "<div field='{$field}' style='display:none'>
			<img src='{$imgUrl}' style='height: 85px' class='img-rounded' style='display:none'>
			<div class='controls'>
				Keep: <input type='radio' name='fileOptions[]' value='0|{$field}' checked='checked' 
						onchange=\"updateWidget('{$field}',0)\">
				Change: <input type='radio' name='fileOptions[]' value='1|{$field}' 
						onchange=\"updateWidget('{$field}',1)\">
			</div>
			</div>";
		}
	}
		
	?>
<?php 
if($loadCkEditor != "") {
	echo link_tag("ckeditor/contents.css");
	echo "<script type='text/javascript' src='" . base_url() . "ckeditor/ckeditor.js'></script>";
?>
<script type="text/javascript">
	$(function(){
		CKEDITOR.replace('richtext',
    			{
    		filebrowserBrowseUrl : '<?= base_url() ?>ckfinder/ckfinder.html',
    		filebrowserImageBrowseUrl : '<?= base_url() ?>ckfinder/ckfinder.html?type=Images',
    		filebrowserFlashBrowseUrl : '<?= base_url() ?>ckfinder/ckfinder.html?type=Flash',
    		filebrowserUploadUrl : 
    	 	   '<?= base_url() ?>ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&currentFolder=/',
    		filebrowserImageUploadUrl : 
    		   '<?= base_url() ?>ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=/',
    		filebrowserFlashUploadUrl : '<?= base_url() ?>ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    			});
	});
</script>
<?php }?>

<script type="text/javascript">
	$(function(){
		//var fileFields = $("form input.file-field")
	<?php if(isset($params['id']))  {?>
		var fileFields = $("form li:has(input.file-field)")
		$.each(fileFields,function(index, value) {
			field_name = $(value).find("input[type=file]").css("display","none").attr("name")
			append_html = $("div[field="+ field_name +"]").html()
			$(value).append(append_html)
		});
	<? } ?>
	});
	function updateWidget(field,val) {
		if(val==0) {
			//keep image
			$("li input[name="+ field +"]").css("display","none");
			$("li:has(input[name=" + field + "])").find("img").css("display","block");
		} else {
			//display file input
			$("li input[name="+ field +"]").css("display","block");
			$("li:has(input[name=" + field + "])").find("img").css("display","none");
		}
	}

	
</script>