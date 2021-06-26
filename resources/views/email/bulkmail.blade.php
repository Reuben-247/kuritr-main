<!DOCTYPE html>
<head>
<title>bulk mail</title>
</head>
<body style="font-size:1.1em; align:center; background:#EEE;">
<br/>
<center>
	<div style="width:600px; background:#fff; ">
		<h2 style="background:#3299CC; color:#FFF; height:38px; padding: 0 0 0 10px">{{$subject}}</h2>
		<div style="padding: 0 10px 10px 10px;">
		<p style="text-align:justify; font-size:1.2em;">
		Hello, {{$username}} !!! <br>
		{!! $content !!}
			</p>
			
			<a style="text-decoration: none;" href="http://www.bido.com.ng" title="Go to Bido">
			<h3 style="font-style:italic; font-size:1.4em; text-align:right; margin-right:20px; color:#000; font-family: Arial, Helvetica, sans-serif;
  font-weight: bold;">Bido</h3>
		</a>
		</div>
	</div>
	</center>
	<br/>
</body>
</html>
	               
	    