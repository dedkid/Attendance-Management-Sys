<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>AMS | Company</title>
</head>
<body>
    <div class="wrapper">
        <div class="container main">
            <div class="row">
                <div class="col-md-6 side-image">
                    <!-------Image-------->
                    <img src="/images/CSK Logo.png" alt="error" >
                </div>
                <div class="col-md-6 right">
                     <div class="input-box">
                        <header>ATTENDANCE MANAGEMENT SYSTEM</header>
                        <header>LOG IN</header>
                        <!--form action PHP-->
                        <form action="check_login.php" method="post">
                            <div class="input-field">
                                <input type="text" class="input" name="email" required autocomplete="off">
                                <label for="email">Email (hr@test.com / bocchi@test.com)</label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="input" name="password" required>
                                <label for="password">Password (hradmin / guitarhero)</label>
                            </div>
                            <div class="input-field">
                                <input type="submit" class="submit" value="Sign In">
                                
                            </div>
                        <div class="forgotPassword">
                            <span>Forgot Password? <a href="mailto:?subject=Password%20Recovery%20Request">Contact your admin</a></span>
                        </div>
                        </form>
                     </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>