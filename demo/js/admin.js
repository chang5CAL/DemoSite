var ref = firebase.database().ref('/Companies');
var deptRef = firebase.database().ref('/Departments');
var companies = {};
var companyList = [];
var reverseCompanyMapping = {};

var departments = {};
var departmentList = [];

var deptComplete = function(ui) { 
	if (typeof departments[reverseCompanyMapping[ui]] !== 'undefined'){
		departmentList = [];

		for (key in departments[reverseCompanyMapping[ui]]){
			var list = departments[reverseCompanyMapping[ui]];
			departmentList.push(list[key]);
		}
		console.log("enabled");
		$("#department").attr("disabled",false);
		$("#department").autocomplete({
			source: departmentList
		})
	} else {
		console.log("Disabled");
		$("#department").attr("disabled",true);
	}
}

ref.once('value', function(snapshot) {
	companies = snapshot.val();
	for (key in companies) {
		companyList.push(companies[key].companyName);
		reverseCompanyMapping[companies[key].companyName] = key;
	}
	console.log(companyList);
})

deptRef.once('value', function(snapshot) {
	departments = snapshot.val();

})

$(function() {
	$("#companyName").autocomplete({
		source: companyList,
		select: function(event, ui){
			deptComplete(ui.item.value);
		}
	})
})

$($("#companyName").change(function() {
	console.log("Running");
	deptComplete($("#companyName").val());
	
}));

/*firebase.auth().onAuthStateChanged(function(user) {
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

  		var adminRef = firebase.database().ref('/Users/admin/' + user.uid);
			adminRef.once('value', function(snapshot) {
			  admin = snapshot.val();
			  $('#name').val(admin.userName);
			  $('#companyName').val(admin.companyName);
			  $('#department').val(admin.departmentName);
			  $('#title').val(admin.title);
			});

			$('#update-btn').click(function() {
				console.log("button clicked");
				var email = $('#email').val();
				var password = $('#password').val();
				var userName = $('#name').val();
				var company = $('#companyName').val();
				var title = $('#title').val();
				var department = $('#department').val();

				// check to see if company already exists
				var exists = false;
				for (var key in companies) {
					if (companies[key].companyName == company) {
						exists = true;
					}
				}

				if (!exists) {
					var key = firebase.database().ref().child('/Companies').push().key;
				}

				var userObj = {
					companyName: company,
					companyId: key,
					department: department,
					title: title,
					userName: userName,
				};

				var updates = {};
				updates['/Users/admin/' + user.uid] = userObj;
				updates['/Companies/' + key] =    

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
}); // end auth listener*/