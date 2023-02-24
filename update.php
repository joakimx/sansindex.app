<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Keywords = $Notes = "";
$Book = $Page = "";
$keywords_err = $notes_error = "";
$page_err = $book_err = "";
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate Keywords
    $input_keywords = trim($_POST["Keywords"]);
    if(empty($input_keywords)){
        $keywords_err = "Please enter keywords.";
    } elseif(!filter_var($input_keywords, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\#\$\%\^\&\*\(\)\+\=\-\[\]\'\;\,\.\/\{\}\|\:\<\>\?\~\s]+$/")))){
        $keywords_err = "Please enter a valid valid.";
    } else{
        $Keywords = $input_keywords;
    }
    
    // Validate Page
    $input_page = trim($_POST["Page"]);
    if(empty($input_page)){
        $page_err = "Please enter Page number.";     
    } else{
        $Page = $input_page;
    }
    
    // Validate Book
    $input_book = trim($_POST["Book"]);
    if(empty($input_book)){
        $book_err = "Please enter the Book Number.";     
    } elseif(!ctype_digit($input_book)){
        $book_err = "Please enter a positive integer value.";
    } else{
        $Book = $input_book;
    }
    // Validate Notes
    $input_notes = trim($_POST["Notes"]);
    if(empty($input_notes)){
        $notes_err = "Please enter notes.";
    } elseif(!filter_var($input_notes, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\#\$\%\^\&\*\(\)\+\=\-\[\]\'\;\,\.\/\{\}\|\:\<\>\?\~\s]+$/")))){
        $notes_err = "Please enter notes.";
    } else{
        $Notes = $input_notes;
    }


    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err) && empty($notes_err)){
        // Prepare an update statement
        $sql = "UPDATE SEC275 SET Keywords=?, Page=?, Book=?, Notes=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $param_keywords, $param_page, $param_book, $param_notes, $param_id);
            
            // Set parameters
            $param_keywords = $Keywords;
            $param_page = $Page;
	    $param_book = $Book;
	    $param_notes = $Notes;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: Form.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM SEC275 WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $Keywords = $row["Keywords"];
                    $Page = $row["Page"];
		    $Book = $row["Book"];
		    $Notes = $row["Notes"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Keywords</label>
                            <input type="text" name="Keywords" class="form-control <?php echo (!empty($keywords_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Keywords; ?>">
                            <span class="invalid-feedback"><?php echo $keywords_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Page</label>
                            <textarea name="Page" class="form-control <?php echo (!empty($page_err)) ? 'is-invalid' : ''; ?>"><?php echo $Page; ?></textarea>
                            <span class="invalid-feedback"><?php echo $page_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Book</label>
                            <input type="text" name="Book" class="form-control <?php echo (!empty($book_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Book; ?>">
                            <span class="invalid-feedback"><?php echo $book_err;?></span>
			</div>
                        <div class="form-group">
                            <label>Notes</label>
                            <input type="text" name="Notes" class="form-control <?php echo (!empty($notes_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Notes; ?>">
                            <span class="invalid-feedback"><?php echo $notes_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
