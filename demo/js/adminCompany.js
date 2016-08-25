firebase.auth().onAuthStateChanged(function(user) {

	var uploaded = false;
	var uploadedFile = "";
  // redirect if the user is signed in
  var finished = function	() {
  	location.reload();
  }

  File.prototype.convertToBase64 = function(callback){
    var reader = new FileReader();
    reader.onload = function(e) {
         callback(e.target.result)
    };
    reader.onerror = function(e) {
         callback(null);
    };        
    reader.readAsDataURL(this);
  };


  if (user) {
  	console.log("signed in");

  	var companyRef = firebase.database().ref('/Companies');
		var companies = {};
		companyRef.once('value', function(snapshot) {
			companies = snapshot.val();
		});

  	$(document).ready(function() {
			$('#email').val(user.email);

			$('#upload-logo-button').click(function() {
				console.log('test');
				$('#hi').click();
		  	return false;
		  });

		  $('#company-logo').change(function(e) {

				var selectedFile = this.files[0];
				// check if submitted valid file'
				console.log()
				if (file && (selectedFile.name.substring(selectedFile.name.length - 4) == '.png' || 
					selectedFile.name.substring(selectedFile.name.length - 4) == '.jpg')) {
			    	selectedFile.convertToBase64(function(base64){
			    		base64 = base64.replace(/^data:image\/(png|jpg);base64,/, "");
			        /*firebase.database().ref('/Users/admin').child(user.uid).update({
			        	logo: base64,
			        });*/
			        //alert("Added logo to your admin profile");
			        uploadedFile = base64;
							//$('#company-logo').val("");		
			    	}) 
				} else {
					alert("Please upload a valid .PNG or .JPG file");
				}
		  });

		  $('#csv-btn').click(function() {
		  	console.log("csv btn click")
		  	$('#file').click();
		  });

		  $('#file').change(function (e) {
		  	var file = this.files[0];
		  	if (!file && file.name.substring(file.name.length - 4) != ".csv") {
		  		alert("Please upload a valid csv file");
		  	} else {
		  		upload();
		  		//uploaded = true;
		  	}
		  });

			$('#add-btn').click(function() {
				if (uploaded) {
					//upload();
					uploaded = false;
				} else {
					// re-initialize so we get upto date information
					individualSubmit();
				}
			}); // end add button click
		}); // end document ready
  } else {
  	console.log("not signed in");
  	window.location = "/";
	}

	var individualSubmit = function() {
		console.log("button clicked");
		var products = $('#products').val().split('\n');
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
		if (uploadedFile != "") {
			companyObj['logo'] = uploadedFile;
		}

		for (var key in companies) {
			if (companies[key].companyName == company) {
				console.log("similar company found");
				exists = true;
				companyId = key;
				break;
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
			var productString = "";
			for (var i = 1; i <= products.length; i++) {
				if (products[i-1] != "") {
					var name = "product" + i;
					productObj[name] = products[i - 1];
					productString += products[i - 1] + ", ";
				}
			}
			updates['/Companies/' + companyId] = companyObj;
			updates['/Departments/' + companyId] = departmentObj;
			updates['/Products/' + departmentId] = productObj; 
			firebase.database().ref().update(updates);
			addProductToAdmin(productString);
			finished();
		} else {
			if (uploadedFile != "") {
				var companyObj = {
					logo: uploadedFile,
				}
				firebase.database().ref('/Companies').child(companyId).update(companyObj);
			}
			// need to check for existing departments and products
			var departmentRef = firebase.database().ref('/Departments/' + companyId);
			departmentRef.once('value', function(snapshot) {
				var departments = snapshot.val();
				var found = false;
				var departmentId = ""
				var productString = "";
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
						if (products[i-1] != "") {
							var name = "product" + i;
							productObj[name] = products[i - 1];
							productString += products[i - 1] + ", ";
						}
					}
					firebase.database().ref('/Departments').child(companyId).update(departmentObj);
					firebase.database().ref('/Products').child(departmentId).update(productObj);
					addProductToAdmin(productString);
					finished();
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
							if (products[i-1] != "") {
								var name = "product" + (count + i);
								productObj[name] = products[i - 1];
								productString += products[i - 1] + ", ";
							}
						}
						console.log("adding new products")
						firebase.database().ref('/Products').child(departmentId).update(productObj);
						addProductToAdmin(productString);
						finished();
					});
				}
			}); // end check for department
		}
	}

	var upload = function() {
		var company = $('#company').val();
		if (company == "") {
			alert("Please enter the company name to add csv file to");
			$('#file').val('');
			return false;
		}
		alert("Your departments/products have been added");
		var companyObj = {
			companyName: company,
			departments: "",
			products: "",
		}

		if (uploadedFile != "") {
			companyObj['logo'] = uploadedFile;
		}

		var file = $('#file')[0].files[0];
		console.log(file);
		// check if valid file
		if (file && file.name.substring(file.name.length - 4) == ".csv") {
			Papa.parse(file, {
				complete: function(results) {
					var data = results.data;
					console.log(results.data)
					var obj = {};
					var departmentList = [];
					for (var i = 0; i < data.length; i++) {
						// todo, what to do in the event if given iproper format		
						if (data[i].length == 2) {
							console.log(data[i][0], data[i][1]);
							if (typeof obj[data[i][0]] === 'undefined') {
								obj[data[i][0]] = [];
								departmentList.push(data[i][0]);
							}
							obj[data[i][0]].push(data[i][1]);
						}
					}
					console.log(obj);
					console.log(departmentList);
					var exists = false;
					var companyId = ""
					// note companies are from existing pre-load from other page
					for (var key in companies) {
						if (companies[key].companyName == company) {
							console.log("similar company found");
							exists = true;
							companyId = key;
							break;
						}
					}

					if (!exists) {
						console.log("company doesn't exist");
						// no need to check everything is new, just create everything
						companyId = firebase.database().ref().child('/Companies').push().key;
						var keyList = [];
						var departmentObj = {}

						for (var i = 0; i < departmentList.length; i++) {
							var departmentKey = firebase.database().ref().child('/Departments/' + companyId).push().key;
							// later used to allow easier mapping between department and products
							keyList.push(departmentKey);
							departmentObj[departmentKey] = departmentList[i];	
						}
						console.log(keyList);

						// need list of products with department key to object of products
						var updates = {};
						var productObj = {};

						// create our mapping for our department to product
						/**
						ex:
						productObj = {
							'department_id': {
								'product1': "fish",
								'product2': "grain",
							}
						}
						*/
						var productString = "";
						for (var i = 0; i < departmentList.length; i++) {
							// keyList has the same ordering as departmentList
							productObj[keyList[i]] = {};
							for (var j = 0; j < obj[departmentList[i]].length; j++) {
								var products = obj[departmentList[i]];
								if (products[j] != "") {
									var name = "product" + (j + 1);
									console.log(name, products)
									console.log(productObj);
									productObj[keyList[i]][name] = products[j];
									productString += products[j] + ", ";
								}
							}
						}

						updates['/Companies/' + companyId] = companyObj;
						updates['/Departments/' + companyId] = departmentObj;
						for (var key in productObj) {
							updates['/Products/' + key] = productObj[key]; 								
						}
						firebase.database().ref().update(updates);
						// productString not cleaned
						addProductToAdmin(productString);
						//finished();
					} else {
						console.log("company exists");
						if (uploadedFile != "") {
							var companyObj = {
								logo: uploadedFile,
							}
							firebase.database().ref('/Companies').child(companyId).update(companyObj);
						}
					
						// company exists, check if each department exists
						var departmentRef = firebase.database().ref('/Departments/' + companyId);
						departmentRef.once('value', function(snapshot) {
							var departments = snapshot.val();
							console.log("object", obj);
							var departmentIdNameMapping = {};
							for (var departmentName in obj) {		
								var found = false;
								var departmentId = ""
								var keyList = [];
								var departmentObj = {};
								var productObj = {};

								// check to see if there is an existing key for the department
								for (var key in departments) {
									if (departments[key] == departmentName) {
										found = true;
										departmentId = key;
										departmentIdNameMapping[departmentId] = departmentName;
										break;
									}
								}
								if (!found) {
									console.log(departmentName, "doesn't exist");
									departmentId = firebase.database().ref().child('/Departments/' + companyId).push().key;
									departmentObj[departmentId] = departmentName;
									console.log(key);
									// adding the product to the associated department
									//productObj[departmentId] = {};
									var productString = "";
									for (var j = 0; j < obj[departmentName].length; j++) {
										var products = obj[departmentName];
										if (products[j] != "") {
											var name = "product" + (j + 1);
											productObj[name] = products[j];
											productString += products[j] + ", ";
										}
									}
									firebase.database().ref('/Departments').child(companyId).update(departmentObj);
									firebase.database().ref('/Products').child(departmentId).update(productObj);
									addProductToAdmin(productString);
								} else {
									console.log(departmentName, "exist");
									var departmentRef = firebase.database().ref('/Products/' + departmentId);
									departmentRef.once('value', function(snapshot) {
										snapKey = snapshot.key;
										existingProducts = snapshot.val();
										var count = 0;
										for (var key in existingProducts) {
											count++;
										}
										console.log("products", existingProducts);
										console.log("key", snapKey);
										var mappedDepartmentName = departmentIdNameMapping[snapKey];
										var productString = "";
										for (var i = 0; i < obj[mappedDepartmentName].length; i++) {
											var products = obj[mappedDepartmentName];
											if (products[i] != "") {
												var name = "product" + (count + i + 1);
												productObj[name] = obj[mappedDepartmentName][i];
												productString += products[j] + ", ";
											}
										}
										firebase.database().ref('/Products').child(snapKey).update(productObj);
										addProductToAdmin(productString);
									});
								}
							} // end for loop forthrough all departments
						}); // end check for department
					}
				}
			});
		} else {
			alert("Please upload a valid CSV file.")
		}
	}

	var addProductToAdmin = function(productString) {
		/*productString = productString.substring(0, productString.length - 2);
		firebase.database().ref('/Users/admin').child(user.uid).update({
    	products: productString,
    });*/
	}
}); // end auth listener