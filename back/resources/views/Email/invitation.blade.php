<head>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<style type="text/css">
		body {
			font-family: sans-serif;
			padding: 0;
			margin: 0;
		}
		h2 {
			color: #3396C4;
		}
		footer {
			background-image: url(http://www.lovethispic.com/uploaded_images/22471-Sun-Over-The-Ocean.jpg);
			background-repeat: no-repeat;
			background-attachment: local;
			background-position: center bottom;
			background-size: 100% auto;
			height: 15%;
			margin-top: 10%;
		}
		div.content {
			width: 50%;
			margin: 0 auto;
		}
		div.identifier-info {
			padding: 10%;
			width: 80%;
		}
		div.card {
			padding: 21px;
			box-shadow: 1px 3px 23px -6px grey;
		}
		.strong {
			color: #3396C4;
			font-weight: bold;
		}
		p.builts-icon {
			padding: 9%;
		}
		p.builts-icon i {
			font-size: 4em;
		}
		p.builts-text {
			color: white;
			padding: 9%;
		}
		div.builts {
			padding: 2% 10%;
			width: 80%;
		}
		div.inside-os {
			width: 30%;
			background-color: #3396C4;
			padding: 0 7px;
		}
		.header-img {
			height: 30%;
			background-image: url(http://www.lovethispic.com/uploaded_images/22471-Sun-Over-The-Ocean.jpg);
			background-repeat: no-repeat;
			background-attachment: local; 
			background-position: center center;
			background-size: 100% auto;
			padding: 10%;
		}
		.header-img img {
			width: 43%;
			margin: 0 auto;
		}
		.flex {
			display: flex;
			flex-wrap: nowrap;
			justify-content: space-between; 
		}
	</style>
</head>
<body>
	<div class="content">
		<div class="header-img">
			<div class="flex flex-vertical">
				<img src="http://lesdisquaires.com/wp-content/uploads/2012/07/Logo-blanc-fond-transparent.png">
			</div>
		</div>
		<div class="identifier-info">
			<h2>Someone invites you to join {{ $project->name }} !</h2>
			<div class="card">
				<p>Find your personnal data bellow :</p>
				<p><span class="strong">Your username </span>: {{ $project->temp_username }}</p>
				<p><span class="strong">Your temporary password </span>: {{ $project->temp_password }} </p>
			</div>
		</div>

		<div class="builts flex">
			<div class="inside-os flex">
				<a href="https://github.com/lucyjosef/HouraGANTT" target="_blank">
				<div class="icon-side">
						<p class="builts-icon"><i class="fab fa-windows"></i></p>
					</div>
					<div class="text-side">
						<p class="builts-text">Download<br>for Windows</p>
					</div>
				</a>
			</div>
			<div class="inside-os flex">
				<a href="https://github.com/lucyjosef/HouraGANTT" target="_blank">
					<div class="icon-side">
						<p class="builts-icon"><i class="fab fa-linux"></i></p>
					</div>
					<div class="text-side">
						<p class="builts-text">Download<br>for Linux</p>
					</div>
				</a>
			</div>
			<div class="inside-os flex">
				<a href="https://github.com/lucyjosef/HouraGANTT" target="_blank">
					<div class="icon-side">
						<p class="builts-icon"><i class="fab fa-apple"></i></p>
					</div>
					<div class="text-side">
						<p class="builts-text">Download<br>for Macintosh</p>
					</div>
				</a>
			</div>
		</div>

		<footer>
			<div>
				<p>See you soon !<br>
				Team {{ config('app.name') }}</p>
			</div>
		</footer>
	</div>
</body>
