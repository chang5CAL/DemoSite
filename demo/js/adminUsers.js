var ref = firebase.database().ref('/Companies');
var deptRef = firebase.database().ref('/Departments');
var companies = {};
var companyList = [];
var reverseCompanyMapping = {};
var reverseDeptMapping = {};
var reverseMassInput = {};


var departments = {};
var departmentList = [];

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
			    //I don't know if the next statement is necessary 
			    secondaryApp.auth().signOut();
	  		})
			}
		}
		})
	}


var deptComplete = function(ui) { 
	if (typeof departments[reverseCompanyMapping[ui]] !== 'undefined'){
		departmentList = [];

		for (key in departments[reverseCompanyMapping[ui]]){
			var list = departments[reverseCompanyMapping[ui]];
			departmentList.push(list[key]);
			reverseDeptMapping[list[key]] = key;
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

firebase.auth().onAuthStateChanged(function(user) {
  // redirect if the user is signed in

  if (user) {
  	console.log(user.uid);
  	$("#signup-btn").click(function(){
		secondaryApp.auth().createUserWithEmailAndPassword($("#email").val(), $("#password").val()).then(function(firebaseUser) {
		    console.log("User " + firebaseUser.uid + " created successfully!");
			date = new Date();
			time = date.toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
			created = toDoubleDigit(date.getMonth() + 1) + "/" + toDoubleDigit(date.getDate()) + "/" + date.getFullYear() + " " + time;
		    obj = {
				companyId: reverseCompanyMapping[$("#company").val()],
		    	companyName: $("#company").val(),
				createdAt: created,
				departmentId: reverseDeptMapping[$("#department").val()],
				departmentName: $("#department").val(),
		    	email: $("#email").val(),
		    	title: $("#title").val(),
		    	userName: $("#name").val()
		    }
		    //"-KPttaHJDxKuh1Ny-_rU"
		    //"-KPttfEVY7aGL_kQSdvC"
		    //-KPttfEVY7aGL_kQSdvC
		    /*obj = {
		    	userName: "TestName",
		    	title: "TestTitle",
				departmentName: "TestDept",
		    	companyName: "TestCompany",
		    	email: "TestEmail"
		    }*/
			firebase.database().ref("/Users/" + firebaseUser.uid).set(obj).catch(function(error) {
				console.log(error.message)
			});
		    //I don't know if the next statement is necessary 
		    secondaryApp.auth().signOut();
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
	for (key in companies) {
		companyList.push(companies[key].companyName);
		reverseCompanyMapping[companies[key].companyName] = key;
	}
})

deptRef.once('value', function(snapshot) {
	departments = snapshot.val();

})

$(function() {
	$("#company").autocomplete({
		source: companyList,
		select: function(event, ui){
			deptComplete(ui.item.value);
		}
	})
})


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