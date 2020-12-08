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
            $_SESSION['userRole_id'] = $row['userRole_id'];
            $confirm_user_msg = "The current user has an id of " . $_SESSION['user_id'];
        }
    }
}

$user_results = mysqli_query($link, 'SELECT * FROM user_T');

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
            <div class="column is-2">
                <div class="container">
                    <?php include "./vertical-menu.php"; ?>
                </div>
            </div>
            <div class="column is-1"></div>
            <div class="column is-8">
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
                                <?php while ($user = mysqli_fetch_object($user_results) ) { ?>
                                    <option value="<?php echo $user->user_id; ?>"><?php echo $user->user_fullname; ?></option>
                                <?php } ?>
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
            <div class="column is-1"></div>
        </div>
    </section>
    <footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
                This page is a placeholder for Unit A. 
                There is a dropdown option based on a SQL statement of user_T.
                Upon form submission, a session variable is set.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>