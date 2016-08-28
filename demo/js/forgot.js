firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  if (user) {
  	console.log("signed in");
  	window.location = "adminCompany.html";
  } else {
  	console.log("not signed in");
		$(document).ready(function() {
			$('#forget-btn').click(function() {
				var auth = firebase.auth();
				var email = $('#email').val();
				auth.sendPasswordResetEmail(email).then(function() {
				  // Email sent.
				  alert("Please check your email");
				}, function(error) {
				  alert(error.message);
				});
			});
		});
	}
}); // end auth listener	