<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env( 'APP_NAME' ) }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body style="background-color:#F4F6F8">

   <div class="container">
       <div class="jumbotron" style="margin-top:3em;">
           <h1 class="display-4">Oops!</h1>
           <p class="lead">
                That action is not available.
           </p>
           <hr class="my-4">
           <p>If you would like to get back to the app please navigate away from this page.</p>
           <p class="lead">
               <a id="link" class="btn btn-primary btn-lg" href="#" role="button">Go Back</a>
           </p>
       </div>
   </div>

   <script>
       let link = document.getElementById( 'link' ) ;
       if( link !== null ) link.href = window.location.href;
   </script>
</body>
</html>