var ref = firebase.database().ref('/Companies');
var deptRef = firebase.database().ref('/Departments');
var companies = {};
var companyList = [];
var reverseCompanyMapping = {};

var departments = {};
//var departmentList = [];
firebase.auth().onAuthStateChanged(function(user) {
	if (user) {

  	var userRef = firebase.database().ref('/Users/admin/' + user.uid);

		var deptComplete = function(ui) { 
			if (typeof departments[reverseCompanyMapping[ui]] !== 'undefined'){


				//departmentList = [];
				
				for (key in departments[reverseCompanyMapping[ui]]){
					var list = departments[reverseCompanyMapping[ui]];
					//departmentList.push(list[key]);
					$("#department").append(new Option(list[key]));
				}
				console.log("enabled");
				/*$("#department").autocomplete({
					source: departmentList
				})*/
			} else {
				console.log("Disabled");
			}
		}

		ref.once('value', function(companySnapshot) {
			companies = companySnapshot.val();
			for (key in companies) {
				companyList.push(companies[key].companyName);
				reverseCompanyMapping[companies[key].companyName] = key;
			}
			deptRef.once('value', function(departmentSnapshot) {
				departments = departmentSnapshot.val();
				userRef.once('value', function(userSnapshot) {
					var idtypes = userSnapshot.val();
					console.log(idtypes);
					console.log(user.uid);
					if(idtypes === null){
						window.location = "userHome.html";
					} else {
						$('#email').val(user.email);
						$('#name').val(idtypes.userName);
						$('#companyName').val(idtypes.company);
						$('#title').val(idtypes.title);
						deptComplete(idtypes.company);
						$('#department').val(idtypes.department);
					}
				});

			});
			console.log(companyList);
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
				window.location = "adminCompany.html"
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
				/*var isDisabled = $('#department').is(':disabled');
				if (isDisabled) {
					alert("Please enter a valid company");
					return false;
				}*/
				var departmentName = $("#department option:selected").val();
				firebase.database().ref('/Users/admin').child(user.uid).update({
		    	department: departmentName,
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
