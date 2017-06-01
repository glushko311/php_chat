function initChatReload(){
	if($('[type = hidden]').length != 0){
		setInterval(
			function(){
				$('.message_field').load('ajaxMesLoad.php');
			},
			5000
		);
	}
}

function initMain(){
	initChatReload();
}

initMain();