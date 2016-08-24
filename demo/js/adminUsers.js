var ref = firebase.database().ref('/Companies');
var deptRef = firebase.database().ref('/Departments');
var companies = {};
var companyList = [];
var reverseCompanyMapping = {};
var reverseDeptMapping = {};

var departments = {};
var departmentList = [];

var secondaryApp = firebase.initializeApp(config, "Secondary");

var toDoubleDigit = function(date) {
	if (date < 10) {
		return "0" + date;
	} else {
		return date;
	}
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
		secondaryApp.auth().createUserWithEmailAndPassword("testvalemail12@gmail.com", "pwd123").then(function(firebaseUser) {
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
			firebase.database().ref("/testUsers/" + firebaseUser.uid).set(obj).catch(function(error) {
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
	console.log(companyList);
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
	
}));