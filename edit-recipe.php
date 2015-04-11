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
		<link href='http://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet' type='text/css'>
        
         <script src="createRecipeFormHandler.js" type="text/javascript"></script> 
        
        <?php

            include 'edit-recipe-form-handler.php';
            include 'create-recipe-form.php';

            //credentials
            $servername = "localhost";
            $username = "root";
            $password = "x";
            $dbname = "cookbooknetwork";

            //connect to db
            $conn = connectToDb($servername, $username, $password, $dbname);

            //get recipe id
            $recipeId = $_GET['recipe_id'];
            echo "Recipe Id: $recipeId <br>";

            //get recipe name
            $recipeName = getRecipeNameFromDB($conn, $recipeId);
            echo "Recipe name: $recipeName <br>";

            //get pic name
            $photoName = getImageNameFromDB($conn, $recipeId);
            echo "Photo name: $photoName <br>";

            //get number of ingredients 
            $numberIngredients = getNumberOfIngredientsFromDB($conn, $recipeId);
            echo "Number of ingredients: $numberIngredients <br>";

            //get each ingredient - format: ingredient1, ingredient2, ingredient3...
            $ingredientList= getAllIngredientsFromDB($conn, $recipeId);
            echo "All ingredients: $ingredientList <br>";

            //get number of steps
            $numberSteps = getNumberOfStepsFromDB($conn, $recipeId);
            echo "Number of steps: $numberSteps <br>";

            //get tags
            $numberTags = getNumberOfTagsFromDB($conn, $recipeId);
            echo "Number of tags: $numberTags <br>";
            
        ?>
        
        <title>Edit Recipe</title>
    </head>
    <body onload="setUpInputForm(<?php echo "$numberIngredients" ?>, <?php echo "$numberSteps" ?>);"
          >
        
        <div class="background-image"></div>
		
		<div class="navigation-bar">
			<table  class="navigation-bar-table">
				<tr>
					<td class="navigation-bar-table-left"><h1 class="navigation-bar-table-left-header">Cookbook Network</h1></td>
					<td class="navigation-bar-table-right">
						<ul class="upper-level-ul">
							<li>Account
								<ul>
									<li><a href="">Account Info</a></li>
									<li><a href="">Log Out</a></li>
								</ul>
							</li>
							
							<li>Recipe
								<ul>
									<li><a href="">Create Recipe</a></li>
									<li><a href="">Edit Recipe</a></li>
									<li><a href="">MyRecipes</a></li>
								</ul>
							</li>
							
							<li>Cookbook
								<ul>
									<li><a href="">Create Cookbook</a></li>
									<li><a href="">Edit Cookbook</a></li>
									<li><a href="">MyCookbooks</a></li>
								</ul>
							</li>
							
							<li>Search
								<ul>
									<li><a href="">Search Recipe</a></li>
									<li><a href="">Search Cookbook</a></li>
								</ul>
							</li>
							
						</ul>
						
					</td>
				</tr>
			</table>
		</div>
		
		<div class="content">
			<h1>Edit Recipe</h1>
			
			<form method="post"  enctype="multipart/form-data" onsubmit="return validateForm()">
			
			<table class="content-table">
				<!-- RECIPE NAME -->
				<tr class="content-table-row">
					<td class="content-table-left"><h3>Recipe Name:</h3></td>
					<td class="content-table-right"><input type="text" class="textbox" name="recipe-name" id="recipe-name"  placeholder="Enter Recipe Name Here..."></td>
				</tr>
				<!-- PHOTO -->
				<tr class="content-table-row">
					<td class="content-table-left"><h3>Picture:</h3></td>
					<td class="content-table-right">
                        <p>Please select a picutre (only PNG and JPEG files is accepted):</p>
                        <input type="file" name="photo" size="400" accept="image/png, image/jpeg" /> </td>
				</tr>
				<!-- INGREDIENTS -->
				<tr class="content-table-row">
					<td class="content-table-left"><h3>Ingredients:</h3></td>
					<td class="content-table-right">
						<table class="content-table-ingredients-table">
							<tr class="ingredient" id="1">
								<td class="content-table-ingredients-table-left">Ingredient:</td>
								<td class="content-table-ingredients-table-right">
                                    <input type="text" class="textbox ingredientInput" name="ingredient1" id="ingredient1"  placeholder="Enter Ingredient Here"></td>
							</tr>
							<tr class="ingredient" id="2">
								<td class="content-table-ingredients-table-left">Ingredient:</td>
								<td class="content-table-ingredients-table-right">
                                    <input type="text" class="textbox ingredientInput" name="ingredient2" id="ingredient2"  placeholder="Enter Ingredient Here"></td>
							</tr>
							<tr class="ingredient" id="3">
								<td class="content-table-ingredients-table-left">Ingredient:</td>
								<td class="content-table-ingredients-table-right">
                                    <input type="text" class="textbox ingredientInput" name="ingredient3" id="ingredient3"  placeholder="Enter Ingredient Here"></td>
							</tr>
							<tr class="ingredient" id="4">
								<td class="content-table-ingredients-table-left">Ingredient:</td>
								<td class="content-table-ingredients-table-right">
                                    <input type="text" class="textbox ingredientInput" name="ingredient4" id="ingredient4"  placeholder="Enter Ingredient Here"></td>
							</tr>
						</table>
						<div class="wrapper-button">
                            <a href="javascript:void(0);" class="addLink" onclick="addIngredientField();"><div class="button">+ Add More Ingredients</div></a></div>
					</td>
				</tr>
				<!-- DIRECTIONS -->
				<tr class="content-table-row">
					<td class="content-table-left"><h3>Directions:</h3></td>
					<td class="content-table-right">
						<table class="content-table-ingredients-table">
							<tr class="step" id="1">
								<td class="content-table-ingredients-table-left">Step:</td>
								<td class="content-table-ingredients-table-right">
                                    <input type="text" class="textbox stepInput" id="step1" name="step1" placeholder="Enter Direction Here"></td>
							</tr>
							<tr class="step" id="2">
								<td class="content-table-ingredients-table-left">Step:</td>
								<td class="content-table-ingredients-table-right">
                                    <input type="text" class="textbox stepInput" id="step2" name="step2" placeholder="Enter Direction Here"></td>
							</tr>
							<tr class="step" id="3">
								<td class="content-table-ingredients-table-left">Step:</td>
								<td class="content-table-ingredients-table-right">
                                    <input type="text" class="textbox stepInput" id="step3" name="step3" placeholder="Enter Direction Here"></td>
							</tr>
							<tr class="step" id="4">
								<td class="content-table-ingredients-table-left">Step:</td>
								<td class="content-table-ingredients-table-right">
                                    <input type="text" class="textbox stepInput" id="step4" name="step4" placeholder="Enter Direction Here"></td>
							</tr>
						</table>
						<div class="wrapper-button">
                            <a href="javascript:void(0);" class="addLink" onclick="addStepField();"><div class="button">+ Add More Steps</div></a></div>
					</td>
				</tr>
				<!-- TAGS -->
				<tr class="content-table-row">
					<td class="content-table-left"><h3>Tags:</h3></td>
					<td class="content-table-right">
						<table class="content-table-tags-table">
							<tr>
									<td><b>Ethnicity</b><br /></td>
									<td><b>Diet</b><br /></td>
									<td><b>Meat/Main</b><br /></td>
									<td><b>Other</b><br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" name="american" value="American">American<br /></td>
									<td><input type="checkbox" class="tag-value" name="gluten-free" value="Gluten-free">Gluten-free<br /></td>
									<td><input type="checkbox" class="tag-value" name="beef" value="Beef">Beef<br /></td>
									<td><input type="checkbox" class="tag-value" name="appetizer" value="Appetizer">Appetizer<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" name="asian" value="Asian">Asian<br /></td>
									<td><input type="checkbox" class="tag-value" name="paleo" value="Paleo">Paleo<br /></td>
									<td><input type="checkbox" class="tag-value" name="chicken" value="Chicken">Chicken<br /></td>
									<td><input type="checkbox" class="tag-value" name="beverages" value="Bevarages">Beverages<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" name="greek" value="Greek">Greek<br /></td>
									<td><input type="checkbox" class="tag-value" name="vegan" value="Vegan">Vegan<br /></td>
									<td><input type="checkbox" class="tag-value" name="pork" value="Pork">Pork<br /></td>
									<td><input type="checkbox" class="tag-value" name="breakfast & brunch" value="Breakfast & Brunch">Breakfast & Brunch<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" name="italian" value="Italian">Italian<br /></td>
									<td><input type="checkbox" class="tag-value" name="vegetarian" value="Vegitarian">Vegetarian<br /></td>
									<td><input type="checkbox" class="tag-value" name="poultry" value="Poultry">Poultry<br /></td>
									<td><input type="checkbox" class="tag-value" name="dessert" value="Dessert">Dessert<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" name="jamaican" value="Jamaican">Jamaican<br /></td>
									<td><br /></td>
									<td><input type="checkbox" class="tag-value" name="seafood" value="Seafood">Seafood<br /></td>
									<td><input type="checkbox" class="tag-value" name="lunch" value="Lunch">Lunch<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" name="latin" value="Latin">Latin<br /></td>
									<td><br /></td>
									<td><br /></td>
									<td><input type="checkbox" class="tag-value" name="salad" value="Salad">Salad<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" name="desi" value="Desi">Desi<br /></td>
									<td><br /></td>
									<td><br /></td>
									<td><input type="checkbox" class="tag-value" name="soup" value="Soup">Soup<br /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="content-table-row">
					<td class="content-table-left"><h3>Privacy:</h3></td>
					<td class="content-table-right">
						<select name="privacy-setting" id="privacy" onchange="checkFriendly();">
  							<option name="public" value="public">Public</option>
  							<option name="registered" value="registered">Registered</option>
 			 				<option name="friendly" value="friendly">Friendly</option>
 	 						<option name="private" value="private">Private</option>
						</select>
                        
                        <div id="privacy-input"></div>
                        
                        <div class="wrapper-button" id="addFriend">
                            <a href="javascript:void(0);" class="addLink" onclick="addFriendInput();"><div class="button">+ Add More Friends</div></a></div>
                        
					</td>
                    
                    
                    
				</tr>
			</table>
			
			<!-- 
			Here are all of the tags:
			
			Ethnicity			Diet				Meat/Main Dish			Other	
			American			Gluten-free			Beef					Appetizer	
			Asian				Paleo				Chicken					Beverages	
			Greek				Vegan				Pork					Breakfast & Brunch	
			Italian				Vegeterian			Poultry					Desserts	
			Jamaican								Seafood					Lunch	
			Latin															Salad	
			Desi															Soup					
			#9b59b6				#2ecc71				#e74c3c					#1abc9c	
			-->
			
			
			<br />
			
				<div class="submit-button"><input type="submit" value="Submit"></div>
				<div class="submit-button wrapper-button"><a href=""><div class="button">Cancel</div></a></div>
			</form>
			
		</div>
		
		<div class="footer"><p>&#169; Cookbook Network, 2015. All Rights Reserved.</p></div>
        
        <script type="text/javascript">
            
            //set add friend button invisible
            document.getElementById("addFriend").style.visibility='hidden';
            
            function setUpInputForm(numIngredients, numSteps)
            {
                
                setupIngredientInputs(numIngredients);
                setupStepInputs(numSteps);
            }
            
            function setupIngredientInputs(numIngredients)
            {
                var ingredientsFieldToAdd = numIngredients - 4;
                
                var i;
                for (i = 0; i < ingredientsFieldToAdd; i++)
                {
                    addIngredientField();
                }
            }
            
            function setupStepInputs(numSteps)
            {
                var stepsFieldToAdd = numSteps - 4;
                
                var i;
                for (i = 0; i < stepsFieldToAdd; i++)
                {
                    addStepField();
                }
            }
            
        </script>
        
    </body>
</html>