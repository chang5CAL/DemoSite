<html>
    
    <head>
<title>Kaseify Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        
 <link href="style.css" rel="stylesheet">
    </head>

<body>
    
    
    
    <div class="background-wrap">
    
    
    <video id="video-bg-elem" preload="auto" autoplay="true" loop="loop" muted="muted">
        
<source src="kaseify-bg.mp4" type="video/mp4">
        
        
        
        
    </video>
    </div>
    
    
    
    
 

<div class="container">

	<form class="form-signin" action="logreg.php" method="post" accept-charset="utf-8">
		<h1 class="form-signin-heading text-muted">Kaseify</h1>
		<input name="email" type="text" class="form-control" placeholder="Email address" required="yes" >
		<input name="password" type="password" class="form-control" placeholder="Password" required="yes">
        
        <div class="g-recaptcha" data-size="normal" data-sitekey="6LfdfSQTAAAAAHJi-y20nRu8h0vNXa-HeNtSKpow"></div>
		<button class="btn btn-lg btn-primary btn-block" name="login" type="submit">
			Sign In
		</button>
        <div class="registerhere" > <a href="acch.php">  - Or Register Here - </a> </div>
	</form>

    <button id="aaaa">WORDS</button>
    
    
    
    
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
   <script src="https://www.google.com/recaptcha/api.js"></script> 
   <script type="text/javascript">
       $(document).ready(function() {
            $('#aaaa').click(function() {
                $.ajax({
                    url: "logreg.php",
                    type: "POST",
                    success: function(data, status, jq) {
                        console.log(data);
                        console.log(status);
                    },
                    error: function(js, textStatus, err) {
                        console.log(textStatus);
                        console.log(err);
                    },
                    data: {
                        email: "test1@test1.com",
                        password: "test",
                        username: "test1",
                        login: true
                    }
                });
            });
       });
   </script>
    </body>
</html>
