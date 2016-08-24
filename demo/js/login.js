firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  if (user) {
  	console.log("signed in");
  	window.location = "adminCompany.html";
  } else {
  	console.log("not signed in");
		$(document).ready(function() {
			$('#signin-btn').click(function() {
				console.log("button clicked");
				var email = $('#email').val();
				var password = $('#password').val();
				firebase.auth().signInWithEmailAndPassword(email, password).catch(function(error) {
				  // Handle Errors here.
				  var errorCode = error.code;
				  var errorMessage = error.message;
					alert(errorMessage);
				});
			});
		});
	}
}); // end auth listener