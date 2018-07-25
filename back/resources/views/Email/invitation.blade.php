<head>
	<style>
		body {
			font-family: sans-serif;
			padding: 15px;
		}
		.header-img {
			height: 150px;
			width: 100%;
		}
	</style>
</head>
<body>
	<h2>Someone invites you to join {{ $project->name }} !</h2>
	<div class="header-img">
		<img src="https://img.maxisciences.com/article/480/gs-news/chasseur-d-ouragans_ed34d0bfb255d6b5bc42eb7f34324398242b199b.jpg">
	</div>
	<div>
		<p>Join them by clicking right here</p>
	</div>

	<button class="custom-btn">
		<a href="http://10.2.110.29:2080/api/login">WAIT FOR ME !</a>
	</button>

	<div>
		<p>See you soon !<br>
		Team {{ config('app.name') }}</p>
	</div>
</body>