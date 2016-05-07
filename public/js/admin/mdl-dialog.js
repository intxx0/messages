function mdlDialog(type, title, message, listener) {

	var overlay = $('<div></div>').addClass('mdl-dialog__overlay');
	var container = $('<div></div>').attr({
										  'id': 'confirm-dialog', 
										  'class': 'mdl-dialog mdl-shadow--2dp', 
										  });
	var content = $('<div></div>').addClass('mdl-dialog__content'); 
	var actions = $('<div></div>').addClass('mdl-dialog__actions');

	var cancelButton = $('<button></button>').attr({
		  									 	   	'id': 'confirm-dialog', 
		  									 	   	'class': 'mdl-button mdl-js-button mdl-button--colored', 
		  									 	   }).html('CANCEL');
	var confirmButton = $('<button></button>').attr({
		 											'id': 'confirm-dialog', 
		 											'class': 'mdl-button mdl-js-button', 
		 										   }).html('CONFIRM');
	var okButton = $('<button></button>').attr({
 	   	'id': 'confirm-dialog', 
 	   	'class': 'mdl-button mdl-js-button mdl-button--colored', 
 	   }).html('OK');
		 										   

	content.append($('<h5></h5>').html(title));
	content.append($('<p></p>').html(message));
	container.append(content);

	switch(type) {
		case 'confirm':
			actions.append(cancelButton);
			actions.append(confirmButton);
			container.append(actions);
			cancelButton.on('click', function(e) {
				$('#confirm-dialog').hide().remove();
				$('.mdl-dialog__overlay').hide().remove();
			});
			confirmButton.on('click', listener);
			break;
		case 'alert':
			actions.append(okButton);
			container.append(actions);
			okButton.on('click', function(e) {
				$('#confirm-dialog').hide().remove();;
				$('.mdl-dialog__overlay').hide().remove();
			});
			break;
	}

	overlay.on('click', function(e) {
		$('#confirm-dialog').hide();
		$(this).hide();
	});

	$('body').append(overlay).append(container);
	
}
