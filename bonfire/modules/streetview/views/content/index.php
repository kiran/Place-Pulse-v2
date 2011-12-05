<h1>Streetview Loader</h1>

<br/>
<?php echo form_open(current_url(), 'style="max-width: 700px"') ?>
	<input type="text" name="image_id" placeholder="What image do you want to load?" value="" />

	<div class="submits">
		<input type="submit" name="submit" value="Look up Image" />
	</div>
<?php echo form_close(); ?>

<?php if (isset($image_info) && is_array($image_info) && count($image_info)) : ?>
<h3>Image Information</h3>

<table>
	<tbody>
		<tr><td>Latitude</td><td>Longitude</td><td>Heading</td><td>Pitch</td></tr>
		<?php foreach ($image_info as $row) : ?>
		<tr><td><?php echo $row['lat'];?></td><td><?php echo $row['lng'];?></td>
		<td><?php echo $row['heading'];?></td><td><?php echo $row['pitch'];?></td></tr>
		<?php endforeach;?>
	</tbody>
</table>

<?php
$this->load->helper('html'); 
foreach ($image_info as $row) :
	$source = 'http://maps.googleapis.com/maps/api/streetview?'.'size=640x640'.'&sensor=false';
	$source .= '&location='.$row['lat'].','.$row['lng'];
	$source .= '&heading='.$row['heading'].'&pitch='.$row['pitch'];
	echo img($source, false);
endforeach;
?>

<?php endif; ?>
