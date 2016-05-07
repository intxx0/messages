$(document).ready(function() {
	
	//$('#edit-action').on('click', function(e) {
	$('#edit-action').click(function(e) {
		e.preventDefault();
		var ids = [];
		var checkboxes = $('.mdl-checkbox__input');

		for (var i = 0; i < checkboxes.length; i++) {
			if(i>0) {
				var element = $(checkboxes[i]);
				if (element.parent().hasClass('is-checked'))
					ids.push($(element.parent().parent().parent().find('td')[1]).attr('data-id'));
			}
		}

		if(ids.length<1) {
			mdlDialog('alert', 'Edit User', 'Please select a user to edit.', null);
			return;
		}

		if(ids.length>1) {
			mdlDialog('alert', 'Edit User', 'Please select only one user to edit.', null);
			return;
		}

		document.location =  '<?php echo $this->url('users', array('action' => 'edit')) ?>/' + ids[0];
	});
	//$('#delete-action').on('click', function(e) {
	$('#delete-action').click(function(e) {
		e.preventDefault();
		var ids = [];
		var checkboxes = $('.mdl-checkbox__input');

		for (var i = 0; i < checkboxes.length; i++) {
			if(i>0) {
				var element = $(checkboxes[i]);
				if (element.parent().hasClass('is-checked'))
					ids.push($(element.parent().parent().parent().find('td')[1]).attr('data-id'));
			}
		}

		if(ids.length<1) {
			mdlDialog('alert', 'Delete User', 'Please select a user to delete.', null);
			return;
		}
				
		mdlDialog('confirm', 
				  '<?php echo $this->translate('Delete User') ?>', 
				  '<?php echo $this->translate('Are you sure to delete this user?') ?>', 
				  function() {
			   			document.location =  '<?php echo $this->url('users', array('action' => 'delete')) ?>/' + ids.join(',');
				  });
	});

});
