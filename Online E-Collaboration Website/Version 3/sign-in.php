<?php
// Initialize the session
session_start();
require 'db.php';
$confirm_user_msg = '';

// set session with user_id
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_user_id = $_POST['user_id'];
    $user_sql = mysqli_query($link, 'SELECT * FROM user_T WHERE user_id = ' . $current_user_id);
    if ($user_sql->num_rows > 0) {
        while($row = $user_sql->fetch_assoc()) {
            $user = $row['user_id'];
            $_SESSION['user_id'] = $user;
            $confirm_user_msg = "The current user has an id of " . $_SESSION['user_id'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Sign In</title>
</head>
<body>
    <?php include "./navbar.php"; ?>
    <section class="hero is-dark is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                Log In
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box has-text-centered">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                        <!--Select Option-->
                        <h1 class="title">Log In</h1>
                        <h2 class="subtitle"><?php echo $confirm_user_msg; ?></h2>
                        <hr>
                        <label for="user_id">Select A User</label>
                        <br>
                        <div class="control">
                            <div class="select">
                            <select name="user_id" id="user_id">
                                <option value="001">001</option>
                                <option value="002">002</option>
                                <option value="003">003</option>
                                <option value="004">004</option>
                                <option value="005">005</option>
                                <option value="006">006</option>
                                <option value="007">007</option>
                                <option value="008">008</option>
                            </select>
                            </div>
                        </div>
                        <br>

                        <!-- Submit Button -->
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-info">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>