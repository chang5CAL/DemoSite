
$(document).ready(function() {
	$('.nav-container').load('/templates/nav.html')
});

$(document).ready(function() {
	$('.nav-container-admin').load('/templates/adminNav.html')
});

$(document).ready(function() {
	$('.nav-container-admin-logout').load('/templates/adminNavLogout.html')
});

$(document).ready(function() {
	$('.nav-container-logout').load('/templates/navLogout.html')
});


// Initialize Firebase
var config = {
  apiKey: "AIzaSyAvZouBWZintdujYqXb8QVVRMnpiln1I5I",
  authDomain: "project-3193840779699781791.firebaseapp.com",
  databaseURL: "https://project-3193840779699781791.firebaseio.com",
  storageBucket: "project-3193840779699781791.appspot.com",
};


firebase.initializeApp(config);