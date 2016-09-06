var ref = firebase.database().ref('/Companies');
var deptRef = firebase.database().ref('/Departments');
var companies = {};
var companyList = [];
var reverseCompanyMapping = {};

var departments = {};
var departmentList = [];
firebase.auth().onAuthStateChanged(function(user) {
	if (user) {

	  	var userRef = firebase.database().ref('/Users/'+user.uid);
	  	//console.log(userRef);

		userRef.once('value', function(snapshot) {
			var idtypes = snapshot.val();
			console.log(idtypes);
			if(idtypes === null){
				//On right page, do nothing
				//window.location = "admin.html";
			}
			else{
				window.location = "userHome.html";
			}
		})

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
				console.log("button clicked");
				/*console.log("button clicked");
				var userName = $('#name').val();
				var company = $('#companyName').val();
				var title = $('#title').val();
				var department = $('#department').val();

				var userObj = {
					company: company,
					department: department,
					title: title,
					userName: userName,
				};

				firebase.database().ref('/testUsers/admin').child(user.uid).update(userObj); */  
				window.location = "adminCompoany.html"
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
				var isDisabled = $('#department').is(':disabled');
				if (isDisabled) {
					alert("Please enter a valid company");
					return false;
				}
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
