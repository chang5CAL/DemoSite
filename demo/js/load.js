$(document).ready(function() {
	$('.nav-container').load('/templates/nav.html')
});

$(document).ready(function() {
	$('.nav-container-admin').load('/templates/adminNav.html')
});

$(document).ready(function() {
	$('.nav-container-admin-logout').load('/templates/adminNavLogout.html')
});

// Initialize Firebase


firebase.initializeApp(config);