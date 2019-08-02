(function () {

	$(document).ready(function(){
		login.init();
	});

	var login = {
		init: function(){
			var loginForm =$('#login-form');
			loginForm.on('submit',function (e) {
				e.preventDefault();

				$.post('/login/api?' + loginForm.serialize()).then(function(res){
					if(!res.err){
						console.log('rafi');
						console.log(res);
					}
				});
			});
		}
	};
})();