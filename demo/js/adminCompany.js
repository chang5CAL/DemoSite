firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  if (user) {
  	console.log("signed in");

  	var ref = firebase.database().ref('/Companies');
		var companies = {};
		ref.once('value', function(snapshot) {
			companies = snapshot.val();
		});

  	$(document).ready(function() {
			$('#email').val(user.email);

			$('#add-btn').click(function() {
				console.log("button clicked");
				var products = $('#product').val().split('\n');
				var company = $('#company').val();
				var department = $('#department').val();

				// check to see if company already exists
				var exists = false;
				var companyId = "";
				var companyObj = {
					companyName: company,
					departments: "",
					products: "",
				}
				for (var key in companies) {
					if (companies[key].companyName == company) {
						exists = true;
						companyId = key;
					}
				}

				if (!exists) {
					// no need to check, just create everything
					companyId = firebase.database().ref().child('/Companies').push().key;
					departmentId = firebase.database().ref().child('/Department/' + companyId).push().key;
					var updates = {};
					updates['/Companies/' + companyId] = companyObj;    
					firebase.database().ref().update(updates);
					if (updates) {

					}
				} else {
					// need to check for existing ones
				}


				console.log(res);

				firebase.auth().signInWithEmailAndPassword(email, password).catch(function(error) {
				  // Handle Errors here.
				  var errorCode = error.code;
				  var errorMessage = error.message;
					alert(errorMessage);
				});
			});
		});
  } else {
  	console.log("not signed in");
  	window.location = "/";
	}
}); // end auth listener