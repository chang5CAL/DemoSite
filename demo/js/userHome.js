var ref = firebase.database().ref('/Companies');
var deptRef = firebase.database().ref('/Departments');
var companies = {};
var companyList = [];
var reverseCompanyMapping = {};

var departments = {};
var departmentList = [];
firebase.auth().onAuthStateChanged(function(user) {
	if (user) {
		var deptComplete = function(ui) { 
			if (typeof departments[reverseCompanyMapping[ui]] !== 'undefined'){
				departmentList = [];

				var list = departments[reverseCompanyMapping[ui]];
				for (key in list) {
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
		})

		deptRef.once('value', function(snapshot) {
			departments = snapshot.val();

		})
		$(document).ready(function() {
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


			$('#update-btn').click(function() {
			  var updateEmail = $('#update-email').val();
			  var updateEmailRe = $('#update-email-re').val();
			  var name = $('#name').val();
			  var title = $('#title').val();
			  var companyName = $('#company').val();
			  var departmentName = $('#department').val();

			  if (!name || !title || !company || !department || !updateEmail || !update-email-re) {
			  	alert("Please fill out the form")
			  	return false;
			  }

			  if (updateEmailRe != updateEmail) {
			  	alert("Email must match");
			  	return false;
			  }

			  var list = departments[reverseCompanyMapping[ui]];
			  var depId = "";
			  for (key in list) {
			  	if (list[key] == departmentName) {
			  		depId = key;
			  		break;
			  	}
			  }

			  var obj = {
			  	companyId: reverseCompanyMapping[companyName], 
			  	companyName: companyName, 
			  	departmentId: depId, 
			  	departmentName: departmentName, 
			  	email: updateEmail, 
			  	title: title, 
			  	userName: name,
				}

				firebase.database().ref('/Users').child(user.uid).update(obj);
				window.location = "userHome.html"
			});

			$('#password-btn').click(function() {
				if ($('#email').val() != user.email) {
					alert("Please verify your email");
					return false;
				} 
				var password = $('#password').val();
				user.updatePassword(password).then(function() {
				  alert("Password updated successfully!");
				}, function(error) {
				  alert(error);
				});
			});

			$('#save-name').click(function() {
				//this.val();
				firebase.database().ref('/Users/admin').child(user.uid).update({
		    	userName: $('#name').val(),
		    });
		    alert("Name updated!");
				return false;
			});

			$('#save-title').click(function() {
				//this.val();
				firebase.database().ref('/Users/admin').child(user.uid).update({
		    	title: $('#title').val(),
		    });
		    alert("Title updated!");
				return false;
			});

			$('#save-company').click(function() {
				//this.val();
				firebase.database().ref('/Users/admin').child(user.uid).update({
		    	company: $('#companyName').val(),
		    });
		    alert("Company updated!");
				return false;
			});

			$('#save-department').click(function() {
				//this.val();
				firebase.database().ref('/Users/admin').child(user.uid).update({
		    	department: $('#department').val(),
		    });
		    alert("Department updated!");
				return false;
			});

			// listeners

		});
	} else {
		window.location = "/";
	}
});
