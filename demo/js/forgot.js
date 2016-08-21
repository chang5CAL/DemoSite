firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  if (user) {
  	console.log("signed in");
  	firebase.auth().signOut().then(function() {
		  // Sign-out successful. 
		  console.log("signed out");
		}, function(error) {
		  // An error happened.
		});
  	//window.location = "/other";
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