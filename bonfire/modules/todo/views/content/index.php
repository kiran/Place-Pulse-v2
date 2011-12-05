<html>

<body>
<h1>ToDo items</h1>

<br/>
<?php if (auth_errors() || validation_errors()) : ?>
<div class="notification error">
     <?php echo auth_errors() . validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open(current_url(), 'style="max-width: 700px"') ?>

      <input type="text" name="description" placeholder="What do you need to do?" value="" />

      <div class="submits">
      	   <input type="submit" name="submit" value="Create New Item" />
      </div>
<?php echo form_close(); ?>

<?php if (isset($items) && is_array($items) && count($items)) : ?>
<h3>ToDo Items</h3>

<?php echo form_open(current_url() .'/delete', 'class="ajax-form todo-form"'); ?>
<table>
  <tbody>
  <?php foreach ($items as $item) : ?>
      <tr>
          <td style="width: 1em; text-align: center">
              <input type="checkbox" name="items[]" value="1" data-id="<?php echo $item->todo_id ?>" />
          </td>
          <td><?php echo $item->description ?></td>
      </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php echo form_close(); ?>

<?php endif; ?>

<script>
head.ready(function() {
	$('.todo-form input[type=checkbox]').change(function() {
		var cid = $(this).attr('data-id');

		// Remove it from the display with a fade effect
		$(this).parents('tr').fadeOut(300);

		// Tell the server to remove
		$.post('<?php echo current_url();?>/delete/'+ cid);
	});
});
</script>
</body>
</html>
