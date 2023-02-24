<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Keywords = $Notes = "";
$Page = $Book = "";
$keywords_err = $notes_err = "";
$page_err = $book_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Keywords
    $input_keywords = trim($_POST["Keywords"]);
    if(empty($input_keywords)){
        $keywords_err = "Please enter a Keywords.";
    } elseif(!filter_var($input_keywords, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\#\$\%\^\&\*\(\)\+\=\-\[\]\'\;\,\.\/\{\}\|\:\<\>\?\~\s]+$/")))){
        $keywords_err = "Please enter a valid keyword.";
    } else{
        $Keywords = $input_keywords;
    }
    
    // Validate Page
    $input_page = trim($_POST["Page"]);
    if(empty($input_page)){
        $page_err = "Please enter Page Number.";     
    } else{
        $Page = $input_page;
    }
    
    // Validate Book number
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
        $notes_err = "Please enter valid notes.";
    } elseif(!filter_var($input_notes, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\#\$\%\^\&\*\(\)\+\=\-\[\]\'\;\,\.\/\{\}\|\:\<\>\?\~\s]+$/")))){
        $notes_err = "Please enter valid notes.";
    } else{
        $Notes = $input_notes;
    }

    // Check input errors before inserting in database
    if(empty($keywords_err) && empty($page_err) && empty($book_err) && empty($notes_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO SEC275 (Keywords, Page, Book, Notes) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_keywords, $param_pages, $param_book, $param_notes);
            
            // Set parameters
            $param_keywords = $Keywords;
            $param_pages = $Page;
            $param_book = $Book;
            $param_notes = $Notes;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Entry to Index: SEC520</h2>
                    <p>Add new entry to Index.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="Form.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>

