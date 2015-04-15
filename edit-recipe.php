<?php 
//CHECK IF USER IS OWNDER OF RECIPE 
    session_start();
    $user = $_SESSION["username"];
//get user id to into recipe table
?>



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
            $password = "";
            $dbname = "cookbooknetwork";

            //connect to db
            $conn = connectToDb($servername, $username, $password, $dbname);
            $userId = getAuthorId($conn, $_SESSION["username"]);

            //get recipe id
            $recipeId = $_GET['recipe_id'];

            //get recipe name
            $recipeName = getRecipeNameFromDB($conn, $recipeId);
            $author = getAuthorName($conn, $recipeId);
            if ($recipeName == '' || $author != $user)
            {
                header('Location: fail.php');
            }

            //get pic name
            $photoNamePrev = getImageNameFromDB($conn, $recipeId);

            //get number of ingredients 
            $numberIngredients = getNumberOfIngredientsFromDB($conn, $recipeId);

            //get each ingredient - format: ingredient1, ingredient2, ingredient3...
            $ingredientList = getAllIngredientsFromDB($conn, $recipeId);

            //get number of steps
            $numberSteps = getNumberOfStepsFromDB($conn, $recipeId);

            $stepList = getAllStepsFromDB($conn, $recipeId);
            //get tags
            $numberTags = getNumberOfTagsFromDB($conn, $recipeId);

            $tagList = getAllTagsFromDB($conn, $recipeId);

            $privacy = getPriv($conn, $recipeId);

            $friendList = getAllFriends($conn, $recipeId);

            closeDBConnection($conn);
            
        ?>
        
        
        
        
        <?php

        //if form submitted
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            //connect to db
            $conn = connectToDb($servername, $username, $password, $dbname);
            
            //clean db of old recipe
            cleanDbTables($recipeId, $conn);
            
            //if friend does not have account
            if (!checkPrivacy($conn))
            {
                exit("Sorry, your friend(s) is not a registered user.");
            }
            
            $recipeName = getRecipeName();
            $allSteps = getAllSteps();
            $privacy = getPrivacy();
            $arecipeId = insertRecipeIntoDB($recipeName, $userId, $allSteps, $privacy, $conn);
            
            //if error in inserting recipe into db
            if ($arecipeId < 0)
            {
               exit("Sorry, could not access database when adding recipe. Please try again.");
            }
            
            $photoPath = NULL;
            
            echo "I MADE IT HERE2";
            //check if image uploaded
            if (checkImageUploaded())
            {
                echo "I MADE IT HERE";
                unlink($photoNamePrev);
                $photo = getImageTmpName();
                $photoPath = getImagePath($arecipeId);

                if (!mkdir("images/" . $arecipeId, 0777, true)) 
                {
                    exit('Could not upload image to server.');
                }
                
                if(!move_uploaded_file($photo, "images/" . $photoPath))
                {
                    exit('Could not create space on server for image.');
                }
                
                if (!updateImagePathInDB($conn, "images/" . \
                                         $photoPath, $arecipeId))
                {
                    exit('Could not connect image to account.');
                }
            }
            else
            {
                if (!updateImagePathInDB($conn, $photoNamePrev, $arecipeId))
                {
                    exit('Could not connect image to account.');
                }
            }
            
            $numFriends = countFriends();
            $success = addFriendsToDB($conn, $numFriends, $arecipeId);
            
            //if error in inserting friends into db
            if (!$success)
            {
                exit("Sorry, could not access database when adding friends. Please try again.");
            }
            
            $success = addIngredientsToDB($conn, $arecipeId);
            
            //if error in inserting ingredients into db
            if (!$success)
            {
                exit("Sorry, could not access database when adding ingredients. Please try again.");
            }
            
            $success = addTagsToDB($conn, $arecipeId);
            
            //if error in inserting tags into db
            if (!$success)
            {
                exit("Sorry, could not access database when adding tags. Please try again.");
            }
            
            closeDBConnection($conn);
            redirectToViewRecipe($arecipeId);
        }




        ?>
        
        <title>Edit Recipe</title>
    </head>
    <!--<body onload="setUpInputForm(<?php echo "$numberIngredients" ?>, <?php echo "$numberSteps" ?>, <?php echo "$ingredientList" ?>);"
          >-->
    <body onload="setUpInputForm('<?php echo $recipeName;?>',
                                    '<?php echo $numberIngredients;?>', 
                                    '<?php echo $numberSteps;?>', 
                                    '<?php echo $ingredientList;?>', 
                                    '<?php echo $stepList;?>', 
                                    '<?php echo $tagList;?>', 
                                    '<?php echo $privacy;?>', 
                                    '<?php echo $friendList;?>'
          );">
        
        <img class="background-image" src="<?php 
                if ($photoNamePrev == '' || $photoNamePrev == NULL)
                {
                    echo "images/delicious-pizza-food-1440x900.jpg";
                }
                else
                {
                    echo $photoNamePrev;
                }?>
                                           
                                           
               " height="700"/>
		
		<div class="navigation-bar">				
			<?php include 'check-menu.php'; ?>
		</div>
		
		<div class="content">
			<h1>Edit Recipe</h1>
			
			<form method="post"  enctype="multipart/form-data" onsubmit="true">
			
			<table class="content-table">
				<!-- RECIPE NAME -->
				<tr class="content-table-row">
					<td class="content-table-left"><h3>Recipe Name:</h3></td>
					<td class="content-table-right"><input type="text" class="textbox nameInput" name="recipe-name" id="recipe-name"  placeholder="Enter Recipe Name Here..." required></td>
				</tr>
				<!-- PHOTO -->
				<tr class="content-table-row">
					<td class="content-table-left"><h3>Picture:</h3></td>
					<td class="content-table-right">
                        
                        <?php 
                            if ($photoNamePrev == "")
                            {
                                echo "<p>No photo uploaded, yet! Click below to add one!</p>";
                            }
                            else
                            {
                                echo "<p>If you'd like to change the recipe's picture, then please select a new one. Otherwise, <u><b>do not</b></u> upload a picture.</p>";
                                $imgArr = explode('/', $photoNamePrev);
                                $fileName = $imgArr[count($imgArr) - 1];
                                echo "<p>Current photo: <p style='color:red'>$fileName</p></p>";
                            }
                        ?>
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
                                    <input type="text" class="textbox ingredientInput" name="ingredient1" id="ingredient1"  placeholder="Enter Ingredient Here" required></td>
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
                                    <input type="text" class="textbox stepInput" id="step1" name="step1" placeholder="Enter Direction Here" required></td>
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
									<td><input type="checkbox" class="tag-value" id="american" name="american" value="American">American<br /></td>
									<td><input type="checkbox" class="tag-value" id="gluten-free" name="gluten-free" value="Gluten-free">Gluten-free<br /></td>
									<td><input type="checkbox" class="tag-value" id="beef" name="beef" value="Beef">Beef<br /></td>
									<td><input type="checkbox" class="tag-value" id="appetizer" name="appetizer" value="Appetizer">Appetizer<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" id="asian" name="asian" value="Asian">Asian<br /></td>
									<td><input type="checkbox" class="tag-value" id="paleo" name="paleo" value="Paleo">Paleo<br /></td>
									<td><input type="checkbox" class="tag-value" id="chicken" name="chicken" value="Chicken">Chicken<br /></td>
									<td><input type="checkbox" class="tag-value" id="beverages" name="beverages" value="Bevarages">Beverages<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" id="greek" name="greek" value="Greek">Greek<br /></td>
									<td><input type="checkbox" class="tag-value" id="vegan" name="vegan" value="Vegan">Vegan<br /></td>
									<td><input type="checkbox" class="tag-value" id="pork" name="pork" value="Pork">Pork<br /></td>
									<td><input type="checkbox" class="tag-value" id="breakfast & brunch" name="breakfast & brunch" value="Breakfast & Brunch">Breakfast & Brunch<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" id="italian" name="italian" value="Italian">Italian<br /></td>
									<td><input type="checkbox" class="tag-value" id="vegetarian" name="vegetarian" value="Vegitarian">Vegetarian<br /></td>
									<td><input type="checkbox" class="tag-value" id="poultry" name="poultry" value="Poultry">Poultry<br /></td>
									<td><input type="checkbox" class="tag-value" id="dessert" name="dessert" value="Dessert">Dessert<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" id="jamaican" name="jamaican" value="Jamaican">Jamaican<br /></td>
									<td><br /></td>
									<td><input type="checkbox" class="tag-value" id="seafood" name="seafood" value="Seafood">Seafood<br /></td>
									<td><input type="checkbox" class="tag-value" id="lunch" name="lunch" value="Lunch">Lunch<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" id="latin" name="latin" value="Latin">Latin<br /></td>
									<td><br /></td>
									<td><br /></td>
									<td><input type="checkbox" class="tag-value" id="salad" name="salad" value="Salad">Salad<br /></td>
							</tr>
							<tr>
									<td><input type="checkbox" class="tag-value" id="desi" name="desi" value="Desi">Desi<br /></td>
									<td><br /></td>
									<td><br /></td>
									<td><input type="checkbox" class="tag-value" id="soup" name="soup" value="Soup">Soup<br /></td>
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
				<div class="submit-button wrapper-button"><a href="javascript:void(0);" onclick="window.history.back();" class="addLink"><div class="button">Cancel</div></a></div>
			</form>
			
		</div>
		
		<div class="footer"><p>&#169; Cookbook Network, 2015. All Rights Reserved.</p></div>
        
        <script type="text/javascript">
            
            //set add friend button invisible
            document.getElementById("addFriend").style.visibility='hidden';
            
            function setUpInputForm(recipeName, numIngredients, numSteps, allIngredients, allSteps, allTags, privacy, allFriends)
            {
                setupRecipeName(recipeName);
                setupIngredientInputs(numIngredients, allIngredients);
                setupStepInputs(numSteps, allSteps);
                setupTagInputs(allTags);
                var privacyDropdown = document.getElementById("privacy");
                if (privacy.trim() == "PUBLIC")
                {
                    privacyDropdown.selectedIndex = 0;
                }
                else if  (privacy.trim() == "REGISTERED")
                {
                    privacyDropdown.selectedIndex = 1;
                }
                else if  (privacy.trim() == "FRIENDLY")
                {
                    privacyDropdown.selectedIndex = 2;
                    setupFriendInputs(allFriends);
                    document.getElementById("addFriend").style.visibility='visible';
                }
                else if (privacy.trim() == "PRIVATE")
                {
                    privacyDropdown.selectedIndex = 3;
                }
            }
            
            function setupRecipeName(recipeName)
            {
                document.getElementById("recipe-name").value = recipeName;
            }
            
            function setupIngredientInputs(numIngredients, allIngredients)
            {
                var ingredientArray = allIngredients.split(',');
                
                var i;
                for (i = 1; i <= ingredientArray.length; i++)
                {
                    var currIngredientInput = document.getElementById("ingredient" + i);
                    if (currIngredientInput == null)
                    {
                        addIngredientFieldWithValue(ingredientArray[i-1].trim());
                    }
                    else
                    {
                        currIngredientInput.value = ingredientArray[i-1].trim();
                    }
                }
            }
            
            function setupStepInputs(numSteps, allSteps)
            {
                var stepArray = allSteps.split(',');
                
                var i;
                for (i = 1; i <= stepArray.length; i++)
                {
                    var currStepInput = document.getElementById("step" + i);
                    if (currStepInput == null)
                    {
                        addStepFieldWithValue(stepArray[i-1].trim());
                    }
                    else
                    {
                        currStepInput.value = stepArray[i-1].trim();
                    }
                }
            }
            
            function setupTagInputs(allTags)
            {
                var tagArray = allTags.split(',');
                
                var i;
                for (i = 0; i < tagArray.length; i++)
                {
                    document.getElementById(tagArray[i].trim()).checked = "checked";
                }
            }
            
            function setupFriendInputs(allFriends)
            {
                var friendArray = allFriends.split(',');
                
                for (i = 0; i < friendArray.length; i++)
                {
                    addFriendInputWithVal(friendArray[i].trim());
                }
            }
            
            
            
        </script>
        
    </body>
</html>