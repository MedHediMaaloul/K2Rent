<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- login CSS -->
    <link href="assets/css/login.css" rel="stylesheet" />
    <!-- font CSS -->
    <link href='https://fonts.googleapis.com/css?family=Rajdhani' rel='stylesheet'>
    <title>K2 Location</title>
</head>

<body>
    <div id="oops" hidden>
        <img style="width: 95px;height: 53px; margin-top:5%;" src="assets/images/login/k2rent.png" alt="">
        <div style="text-align: center;font-weight: 700;">
            <div id="oopsText" > &nbsp;Oops !</div>
            <div id="descativerText">Votre compte est désactivé !
            </div>
        </div>
    </div>
    <div id="conatinerLogin">
        <div id="containerBody">
            <span id="loading" hidden>loading...</span>
            <div id="rightContainer">
                <div style="position: absolute;right: 191px; top:100px">
                    <img src="assets/images/login/k2.png"
                        style="width: 90px;height: 42px; margin-bottom: 3px; display:block;">
                    <img src="assets/images/login/RENT.png" style="width:88px;height: 9px;">
                </div>
                <form id="form" method="POST">
                    <h1>Salut</h1><img src="assets/images/login/salut.png" style="height: 22px;width: 22px;">
                    <h2>Bienvenue chez l’application web de k2 RENT.</h2>
                    <label id="pseudolabel"><b>Login</b></label>
                    <div style="margin-bottom: 20px;">
                        <input id="login" type="text" placeholder="&nbsp;Login" name="username" required>
                        <p1 class="obligatoire" id="obligatoireLogin" style="display:block;"></p1>
                    </div>

                    <label><b>Mot de passe</b></label>
                    <div style="margin-bottom: 20px;">
                        <input style="font-family: 'monospace' !important;font-size: 24px;" id="password"
                            type="password" name="password" placeholder="&nbsp;&#9679;&#9679;&#9679;&#9679;&#9679;"
                            required>
                        <p1 class="obligatoire" id="obligatoirePassword" style="display:block;"></p1>
                        <p1 class="erreur" id="erreur" style="display:block;"></p1>
                    </div>
                    <input type="submit" id='SubmitLogin' value='Connexion'>
                </form>
            </div>
        </div>
    </div>

    </div>
    <div id="VehiculeIcon">
        <img id="voiture" src="assets/images/login/VoitureLogin.png" alt="">
        <div id="vector">
            <img id="VecIcon" src="assets/images/login/vector.png" alt="">
            <img id="VecIcon" src="assets/images/login/vector.png" alt="">
            <img id="VecIcon" src="assets/images/login/vector.png" alt="">
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/myjs.js"></script>
</body>

</html>