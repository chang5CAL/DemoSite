firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  if (user) {

  	var userRef = firebase.database().ref('/Users'+user.uid);
  	//console.log(userRef);

	userRef.once('value', function(snapshot) {
		var idtypes = snapshot.val();
		console.log(idtypes);
		if(idtypes === null){
  			window.location = "adminCompany.html";
		}
		else{
  			window.location = "adminCompany.html";
		}

  	console.log("signed in");
<<<<<<< HEAD
=======
  	window.location = "admin.html";
>>>>>>> ff9c0636ee90f33683340dc0071cad08d391ffb0
  } else {
  	console.log("not signed in");
		$(document).ready(function() {
			$('#signin-btn').click(function() {
				console.log("button clicked");
				var email = $('#email').val();
				var password = $('#password').val();
				firebase.auth().signInWithEmailAndPassword(email, password).then(function() {
					window.location = "admin.html";
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