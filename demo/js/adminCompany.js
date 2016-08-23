firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  if (user) {
  	console.log("signed in");

  	var companyRef = firebase.database().ref('/Companies');
		var companies = {};
		companyRef.once('value', function(snapshot) {
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
					// no need to check everything is new, just create everything
					companyId = firebase.database().ref().child('/Companies').push().key;
					var departmentId = firebase.database().ref().child('/Departments/' + companyId).push().key;
					var departmentObj = {}
					departmentObj[departmentId] = department;
					var updates = {};
					var productObj = {};
					for (var i = 1; i <= products.length; i++) {
						var name = "product" + i;
						productObj[name] = products[i - 1];
					}
					updates['/Companies/' + companyId] = companyObj;
					updates['/Departments/' + companyId] = departmentObj;
					updates['/Products/' + departmentId] = productObj; 
					firebase.database().ref().update(updates);
				} else {
					// need to check for existing departments and products
					var departmentRef = firebase.database().ref('/Departments/' + companyId);
					departmentRef.once('value', function(snapshot) {
						var departments = snapshot.val();
						var found = false;
						var departmentId = ""
						// check to see if there is an existing key for the department
						for (var key in departments) {
							if (departments[key] == department) {
								found = true;
								departmentId = key;
								break;
							}
						}

						var updates = {};
						var departmentObj = {};
						var productObj = {};

						if (!found) {
							// if department doesn't exist, make it and add it to Companies
							// Departments and it's Products
							departmentId = firebase.database().ref().child('/Departments/' + companyId).push().key;
							departmentObj[departmentId] = department;
							for (var i = 1; i <= products.length; i++) {
								var name = "product" + i;
								productObj[name] = products[i - 1];
							}
							updates['/Departments/' + companyId] = departmentObj;
							updates['/Products/' + departmentId] = productObj; 
							firebase.database().ref().update(updates);
						} else {
							// department exists, we have to check existing products 
							var departmentRef = firebase.database().ref('/Products/' + departmentId);
							departmentRef.once('value', function(snapshot) {
								existingProducts = snapshot.val();
								var count = 0;
								for (var key in existingProducts) {
									count++;
								}
								for (var i = 1; i <= products.length; i++) {
									var name = "product" + (count + i);
									productObj[name] = products[i - 1];
								}
								updates['/Products/' + departmentId] = productObj; 
								firebase.database().ref().update(updates);

							});
						}

					}); // end check for department
				}
			}); // end add button click
		}); // end document ready
  } else {
  	console.log("not signed in");
  	window.location = "/";
	}
}); // end auth listener