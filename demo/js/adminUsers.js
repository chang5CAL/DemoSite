var ref = firebase.database().ref('/Companies');
var deptRef = firebase.database().ref('/Departments');
var companies = {};
var companyList = [];
var reverseCompanyMapping = {};
var reverseDeptMapping = {};
var reverseMassInput = {};


var departments = {};
//var departmentList = [];

var secondaryApp = firebase.initializeApp(config, "Secondary");

var data = [];

var toDoubleDigit = function(date) {
	if (date < 10) {
		return "0" + date;
	} else {
		return date;
	}
}

var upload = function() {
	console.log("Running Upload");
  	var file = $("#file")[0].files[0];
	var company = $('#company').val();
/*	if (company == "") {
		alert("Please enter the company name to add csv file to");
		$('#file').val('');
		return false;
	}*/
	console.log("Company present");
	if (file && file.name.substring(file.name.length - 4) == ".csv") {
		console.log("Is valid file");
		Papa.parse(file, {
			complete: function(results) {
				data = results.data;
				console.log(data);
				massCreate();
			}
		})
	}
}

var massCreate = function(){firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in
  var emailToIdMapping = {};
  for(var i = 0; i < data.length; i++) {
  	emailToIdMapping[data[i][0]] = i;
  	if(data[i].length == 6){
			secondaryApp.auth().createUserWithEmailAndPassword(data[i][0],data[i][1]).then(function(firebaseUser) {
		    var index = emailToIdMapping[firebaseUser.email];

		    console.log("User " + firebaseUser.uid + " created successfully!");
				console.log(i);
				date = new Date();
				time = date.toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
				created = toDoubleDigit(date.getMonth() + 1) + "/" + toDoubleDigit(date.getDate()) + "/" + date.getFullYear() + " " + time;
			    obj = {
						companyId: reverseCompanyMapping[data[index][4]],
			    	companyName: data[index][4],
						createdAt: created,
						departmentId: reverseDeptMapping[data[index][5]],
						departmentName: data[index][5],
			    	email: data[index][0],
			    	title: data[index][2],
			    	userName: data[index][3]
			    }

				firebase.database().ref("/Users/" + firebaseUser.uid).set(obj).catch(function(error) {
					console.log(error.message)
				});

			    secondaryApp.auth().signOut();
	  		})
			}
		}
		alert("Users have been added!");
		})
	}


var deptComplete = function(ui) { 
		console.log(typeof departments[reverseCompanyMapping[ui]] );
		console.log(departments);
		console.log(reverseCompanyMapping );
		console.log(ui);
	if (typeof departments[reverseCompanyMapping[ui]] !== 'undefined'){
		departmentList = [];

		for (key in departments[reverseCompanyMapping[ui]]){
			var list = departments[reverseCompanyMapping[ui]];
			//departmentList.push(list[key]);
			$("#department").append(new Option(list[key]));
			reverseDeptMapping[list[key]] = key;
		}
		console.log("enabled");
		$("#department").attr("disabled",false);
		/*$("#department").autocomplete({
			source: departmentList
		})*/
	}
}

firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in

  if (user) {
  	console.log("UserID:"+user.uid);
  	var userRef = firebase.database().ref('/Users/'+user.uid);

		userRef.once('value', function(snapshot) {
			var idtypes = snapshot.val();
			console.log(idtypes);
			if(idtypes === null){

				console.log("An admin");
				//On right page, do nothing
  			//window.location = "admin.html";
			}
			else{
				console.log("Not an admin");
  			window.location = "userHome.html";
			}
		})

  	$("#signup-btn,#add-btn").click(function(){
		secondaryApp.auth().createUserWithEmailAndPassword($("#email").val(), $("#password").val()).then(function(firebaseUser) {
		    console.log("User " + firebaseUser.uid + " created successfully!");
			date = new Date();
			time = date.toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
			created = toDoubleDigit(date.getMonth() + 1) + "/" + toDoubleDigit(date.getDate()) + "/" + date.getFullYear() + " " + time;
			var departmentName = $("#department option:selected").val();
		    obj = {
				companyId: reverseCompanyMapping[$("#company").val()],
		    	companyName: $("#company").val(),
				createdAt: created,
				departmentId: reverseDeptMapping[departmentName],
				departmentName: departmentName,
		    	email: $("#email").val(),
		    	title: $("#title").val(),
		    	userName: $("#name").val()
		    }
			firebase.database().ref("/Users/" + firebaseUser.uid).set(obj).catch(function(error) {
				console.log(error.message)
			});
		    //I don't know if the next statement is necessary 
		    secondaryApp.auth().signOut();
				alert("User has been added!");
	})
})

  	/*firebase.auth().createUserWithEmailAndPassword("email@gmail.com", "Password12").catch(function(error) {
  		var errorCode = error.code;
  		var errorMessage = error.message;
  	})*/

  } else {
  	window.location = "/";
  }
})


ref.once('value', function(snapshot) {
	companies = snapshot.val();
	console.log("Companies:");
	console.log(companies);
	for (key in companies) {
		companyList.push(companies[key].companyName);
		reverseCompanyMapping[companies[key].companyName] = key;
	}
	console.log("companyList");
	console.log(companyList);
	console.log("reverseCompanyMapping: ");
	console.log(reverseCompanyMapping);
})


deptRef.once('value', function(snapshot) {
	departments = snapshot.val();
	console.log("Departments: ");
	console.log(departments);
	uid = snapshot.val();

})

$(function() {
	$("#company").autocomplete({
		source: companyList,
		select: function(event, ui){
			deptComplete(ui.item.value);
		}
	})
})

$('#csv-btn').click(function() {
	console.log("csv btn click")
	$('#file').click();
});

$($("#company").change(function() {
	console.log("Running");
	deptComplete($("#company").val());
	
}))

$(document).ready(function(){
	$('#file').change(function (e) {
		console.log("File changed");
	  	var file = this.files[0];
	  	if (!file && file.name.substring(file.name.length - 4) != ".csv") {
	  		alert("Please upload a valid csv file");
	  	} else {
	  		upload();
	  		//uploaded = true;
	  	}
	})
});