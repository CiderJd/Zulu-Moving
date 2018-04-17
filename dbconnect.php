<?php
function pg_connection_string_from_database_url() {
    extract(parse_url($_ENV["DATABASE_URL"]));
    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
  }
 
function getData(){
    try{
        $pg_conn = pg_connect(pg_connection_string_from_database_url());
        $result = pg_query($pg_conn, "SELECT * FROM testimonials;");
        echo "<div class='row text-center pagination-centered m-1'><div class='text-center pagination-centered'><ul>";
        while ($row = pg_fetch_assoc($result)) {
            echo "<li class='text-info'>";
            echo "<div class='panel panel-info'><div class='panel-heading py-5'><h2>".$row["emailid"]."</hr></div>";
            echo "<div class='panel-body py-5'>".$row["review"]."</div>";
            echo "</li>";
        }
        echo "</ul></div></div><hr>";
    }
    catch(Exception $e){
        echo "Unable to access our Database.";
    }
}

?>
