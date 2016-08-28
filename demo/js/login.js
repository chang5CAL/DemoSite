firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  if (user) {

  	var userRef = firebase.database().ref('/Users/'+user.uid);
  	//console.log(userRef);

		userRef.once('value', function(snapshot) {
			var idtypes = snapshot.val();
			console.log(idtypes);
			if (idtypes === null) {
	  		window.location = "adminCompany.html";
			} else {
	  		window.location = "userHome.html";
			}
		});

  	console.log("signed in");
  	window.location = "admin.html";
  } else {
  	console.log("not signed in");
		$(document).ready(function() {
			$('#signin-btn').click(function() {
				console.log("button clicked");
				var email = $('#email').val();
				var password = $('#password').val();
				firebase.auth().signInWithEmailAndPassword(email, password).then(function() {
					var userRef = firebase.database().ref('/Users/'+user.uid);
			  	//console.log(userRef);

					userRef.once('value', function(snapshot) {
						var idtypes = snapshot.val();
						console.log(idtypes);
						if (idtypes === null) {
				  		window.location = "adminCompany.html";
						} else {
				  		window.location = "userHome.html";
						}
					});
				}).catch(function(error) {
				  // Handle Errors here.
				  var errorCode = error.code;
				  var errorMessage = error.message;
					alert(errorMessage);
				});
			});
		});
	}
}); // end auth listener