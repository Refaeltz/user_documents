(function () {

	$(document).ready(function(){
		login.init();
	});

	var login = {
		init: function(){
			var loginForm =$('#login-form');
			loginForm.on('submit',function (e) {
				e.preventDefault();

				$.post('/api/doLogin?' + loginForm.serialize()).then(function(res){
					if(!!res.err){
						console.log(res);
						alert(res.err);
					}
					else{
						window.location.reload();
					}
				});
			});
		}
	};
})();