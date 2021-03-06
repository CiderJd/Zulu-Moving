<?php
function pg_connection_string_from_database_url() {
    extract(parse_url($_ENV["DATABASE_URL"]));
    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
  }
 
function getData(){
    try{
        $pg_conn = pg_connect(pg_connection_string_from_database_url());
        $result = pg_query($pg_conn, "SELECT * FROM testimonials;");
        echo "<div class='col'><ul>";
        while ($row = pg_fetch_assoc($result)) {
            echo "<li class='text-info'>";
            echo "<div class='panel panel-default'><div class='panel-heading py-2'><h2><i class='fa fa-user'></i>".$row["emailid"]."</hr></div>";
            echo "<div class='panel-body py-2'>".$row["review"]."</div>";
            echo "</li>";
        }
        echo "</ul></div></div><hr>";
    }
    catch(Exception $e){
        echo "<i class='fa fa-exclamation-triangle'></i>Unable to access our Database.";
    }
}

function dispForm(){
    echo '
    <div class="col">
    <div class="panel panel-default">
        <div class="panel panel-heading">
            Leave a Review!
        </div>
        <div class="panel-content">
            <form action="" method="POST" style="color: black !important;">
                <div class="form-group">
                <label> EmailID </label>
                <input required class="form-control" type="email" name="emailid" placeholder="Enter your email address!"/>
                </div>
                <div class="form-group">
                
                <label> Testimonial </label>
                <input required class="form-control" name="review" maxlength="30" placeholder="Great people! or Good service!"/> 
                
                </div>
                <input type="submit" value="Submit"/>
            </form>
        </div>
    </div>
    </div>
    
    ';
    if (isset($success)){
        echo '<script>alert("Successfully added");</script>';
    }
}

if(isset($_POST["emailid"]) && isset($_POST["review"])){
    try{
        $pg_conn = pg_connect(pg_connection_string_from_database_url());
        $res = pg_query("INSERT INTO testimonials (emailid, review) VALUES ('".$_POST['emailid']."' ,'".$_POST['review']."')");
        if($res != false){
            $success = "success";
            header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/#testimonials");
        }
        else{
            $success = "fail";
        }
    }
    catch(Exception $e){
        echo "Unable to access our Database.";
    }
}
?>
