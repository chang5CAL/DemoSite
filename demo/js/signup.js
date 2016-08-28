var makeId = function() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 11; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

var toDoubleDigit = function(date) {
		if (date < 10) {
			return "0" + date;
		} else {
			return date;
		}
	}

var createAccount = function(isAdmin, email, password) {
	firebase.auth().createUserWithEmailAndPassword(email, password).then(function() {
		var user = firebase.auth().currentUser;
		var url = "/Users";
		var obj = {};
		date = new Date();
		time = date.toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
		created = toDoubleDigit(date.getMonth() + 1) + "/" + toDoubleDigit(date.getDate()) + "/" + date.getFullYear() + " " + time;
		if (!isAdmin) {
			obj = {
				companyId: "",
				companyName: "",
				createdAt: created,
				departmentId: "",
				departmentName: "",
				email: email,
				title: "",
				userName: ""
			}
		} else {
			url += "/admin";
			obj = {
				city: "",
				company: "",
				date: created,
				department: "",
				eventName: "",
				logo: "",
				mealCost: "",
				mealType: "",
				merchantName: "",
				practice: "",
				products: "",
				receiptImage: "",
				state: "",
				title: "",
				userName: ""
			}
		}
		console.log(user.uid);
		firebase.database().ref(url + "/" + user.uid).set(obj).catch(function(error) {
			console.log(error.message);
		});
		if (isAdmin) {
			window.location = "adminCompany.html";
		} else {
			window.location = "user.html";
		}
	}).catch(function(error) {
	  alert(error.message);
	});
}

var database = firebase.database();
// check the state of the user
firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  if (user) {
  	var userRef = firebase.database().ref('/Users/'+user.uid);

	userRef.once('value', function(snapshot) {
		var idtypes = snapshot.val();
		console.log(idtypes);
		if(idtypes === null){
  			window.location = "adminCompany.html";
		} else{
  			window.location = "user.html";
		}
	});
  } else {
  	$(document).ready(function() {
		$("#signup-btn").click(function() {
			var email = $("#email").val();
	  		var emailRe = $("#email-re").val();
	  		var password = $("#password").val();
	  		var passwordRe = $("#password-re").val();
	  		var accountType = $("#account-type option:selected").val();
	  		var code = $("#admin-code").val();
	  		if (!email.includes("@")) {
	  			alert("Please Enter a valid email.");
	  			return false;
	  		}

	  		if (email != emailRe) {
	  			alert("Make sure your email are the same.")
	  			return false;
	  		}

	  		if (password.length < 8) {
	  			alert("Password must be at least 8 digits long");
	  			return false;
	  		}

	  		if (password != passwordRe) {
	  			alert("Make sure your password matches");
	  			return false;
	  		}

	  		if (!accountType) {
	  			alert("Please select your account type");
	  			return false;
	  		}
	  		// check valid code
	  		if (accountType == "admin") {
	  			console.log("admin twice");
	  			if (code.length != 11) {
	  				alert("Invalid code, not long please try again.");
	  			} else {
	  				var ref = firebase.database().ref('/Code/' + code);
	  				ref.once('value', function(snapshot) {
	  					if (snapshot.val()) {
	  						// remove old code add new code to database
	  						var newCode = makeId();
	  						console.log(newCode);
	  						firebase.database().ref('Code/' + code).set(null);
	  						firebase.database().ref('Code/' + newCode).set({
							    code: newCode,
							  });
								createAccount(true, email, password);
	  					} else {
	  						if (!snapshot.val()) {
		  						alert("Invalid code, please try again.")
		  						console.log("shouldn't happen")
		  						return false;
	  						}
	  					}
					});
	  			}
	  		} else {
	  			// normal user
	  			createAccount(false, email, password);
	  		}
		}); // end signup-btn click

		  $('.show-password').hover(
		  	function() {
		  		$("#password").attr("type", "text");
		  	},
		  	function() {
		  		$("#password").attr("type", "password");
		  	}
		  );
		});
  }
});