<!DOCTYPE html>
<html>
	
	<head>
		 <meta charset="UTF-8">
		<meta name="description" content="A virtual cookbook that allows user's to view, create and share recipes.">
		<meta name="keywords" content="recipe, cookbook, food, ingredients">
		<meta name="author" content="Cookbook Network Inc.">
		<link rel="stylesheet" type="text/css" href="page_style.css">
		<link href='http://fonts.googleapis.com/css?family=Tangerine:700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=IM+Fell+Double+Pica' rel='stylesheet' type='text/css'>
	</head>
	
	<body>
		
		<div class="background-image"></div>
		
		<div class="navigation-bar">
			<table  class="navigation-bar-table">
				<tr>
					<td class="navigation-bar-table-left"><h1 class="navigation-bar-table-left-header">Cookbook Network</h1></td>
					<td class="navigation-bar-table-right">
						<ul class="upper-level-ul">
							<?php include 'guest_nav.php'?>
						</ul>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="content">
			
			<h1>Go-To Quick Lunches</h1>
			
			<!-- ENTER CONTENT HERE" -->
			<div class="recipe-preview-row">
				<a href="">
				<div class="recipe-preview-row-icon">
					<img class="thumbnal" src="thumbnail1.png">
					<p>Yellow Rice and Green Peppers</p>
				</div>
				</a>
				
				<a href="">
				<div class="recipe-preview-row-icon">
					<img class="thumbnal" src="thumbnail2.png">
					<p>Chunky Beef in Tomato Pasta</p>
				</div>
				</a>
				
				<a href="">
				<div class="recipe-preview-row-icon">
					<img class="thumbnal" src="thumbnail3.png">
					<p>Heavy Chicken n' Tomato Soup</p>
				</div>
				</a>
			</div>
			
			<div class="recipe-preview-row">
				<a href="">
				<div class="recipe-preview-row-icon">
					<img class="thumbnal" src="thumbnail4.png">
					<p>Low-Sodium Penne with Ham</p>
				</div>
				</a>
				
				<a href="">
				<div class="recipe-preview-row-icon">
					<img class="thumbnal" src="thumbnail5.png">
					<p>Sunday's Sloppy Joe Sandwich</p>
				</div>
				</a>
			</div>
		</div>
		
		<div class="footer"><p>&#169; Cookbook Network, 2015. All Rights Reserved.</p></div>
		
	</body>
</html>