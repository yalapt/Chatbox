/*----- SCRIPT -----*/

function validationUsername(username)
{
    var usernameValid = /^[A-Za-z0-9-_s]+$/;
    return usernameValid.test(username);
}
function validationEmail(email)
{
    var emailValid = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return emailValid.test(email);
}

function generalAlert(message)
{
    $("#generalAlert").fadeOut(0);
    $("#generalAlert").fadeIn(500);
    document.getElementById("generalAlert").innerHTML = '<p class="alert alert-danger">'+message+'</p>';
    $("#generalAlert").delay(5000).fadeOut(500);
}

function loginValidation()
{
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    if(username == "" || password == "")
    {
        generalAlert("Remplissez tous les champs.");
        return false;
    }
    if(username.length < 3 || password.length < 3)
    {
        generalAlert("Il faut un minimum de 3 caractères par champ.");
        return false;
    }
    if(!validationUsername(username))
    {
        generalAlert("Nom d'utilisateur invalide.");
        return false;
    }
    else 
    {
        document.compteForm.submit();
        return true;
    }
}

function signinValidation()
{
    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var ville = document.signinForm.ville.value;

    if(username == "" || email == "" || password == "") 
    {
        generalAlert("Remplissez tous les champs.");
        return false;
    }
    if(ville == "0")
    {
        generalAlert("Il faut choisir une ville.");
        return false;
    }
    if(username.length < 3 || email.length < 3 || password.length < 3) 
    {
        generalAlert("Il faut un minimum de 3 caractères par champ.");
        return false;
    }
    if(!validationUsername(username))
    {
        generalAlert("Nom d'utilisateur invalide.");
        return false;
    }
    if(!validationEmail(email))
    {
        generalAlert("Email invalide.");
        return false;
    }
    if(document.getElementById("username").style.color == "red")
    {
        generalAlert("Nom d'utilisateur déjà utilisé.");
        return false;
    }
    if(document.getElementById("email").style.color == "red")
    {
        generalAlert("Email déjà utilisé.");
        return false;
    }
    else 
    {
        document.signinForm.submit();
        return true;
    }
}

function compteValidation()
{
    var email = document.getElementById("email").value;
    var userEmail = document.getElementById("userEmail").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    var ville = document.compteForm.ville.value;

    if(confirmPassword == "") 
    {
        generalAlert("Veuillez entrer votre ancien mot de passe pour appliquer les modifications.");
        return false;
    }
    if(ville == "0" && password == "" && email == userEmail)
    {
        generalAlert("Il n'y a rien à modifier, vous utilisez déjà cette email.");
        return false;
    }
    if(ville == "0" && password == "" && document.getElementById("email").style.color == "red")
    {
        generalAlert("Il n'y a rien à modifier, email invalide ou indisponible.");
        return false;
    }
    if(document.getElementById("email").style.color == "red")
    {
        generalAlert("Email invalide ou indisponible.");
        return false;
    }
    if(document.getElementById("email").value.length < 3)
    {
        generalAlert("Email invalide.");
        return false;
    }
    else 
    {
        document.compteForm.submit();
        return true;
    }
}

function confirmSupprimerCompte()
{
    if(confirm('Vous êtes sûr de vouloir supprimer votre compte ?'))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function confirmModifierCompte()
{
    if(confirm('Vous êtes sûr de vouloir modifier les informations de votre compte ?'))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function fermer(id)
{
    $("#"+id).fadeOut(500);
}

function cleanTextChat()
{
    document.getElementById("textChat").value = "";
}

/*----- AJAX -----*/

/*----- Logged Users -----*/

function ini()
{
    var timerLoggedIn=setInterval("loggedIn()", 1000);
    var timerLoggedUsers=setInterval("listLoggedUsers()", 1000);
    var timerChatMessages=setInterval("listChatMessages()", 20000);    
}

function loggedIn()
{
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/ajax/ajaxLoggedIn", true);
    xhr.send();
}

function listLoggedUsers()
{
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/ajax/ajaxLoggedUsers", true);
    xhr.send();
    xhr.onreadystatechange = function() 
    {
        if(xhr.readyState == 4)
        {
            var data = xhr.responseText;
            if(document.getElementById("showLoggedUsers").innerHTML != data)
            {
                document.getElementById("showLoggedUsers").innerHTML = data;
            }
        }
    };
}

/*----------*/

function call(callback, element, call) 
{
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/ajax/"+call, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(element+"="+document.getElementById(element).value.replace(/&/g, "[_#-AND-#_]").replace(/\+/g, "[_#-PLUS-#_]"));
    xhr.onreadystatechange = function() 
    {
        if(xhr.readyState == 4)
        {
            callback(xhr.responseText);
        }
    };
}

/*---- Chat ----*/

function chat()
{
    if(document.getElementById("textChat").value.replace(/ /g, "").replace(/(\n)+/g, "") != "")
    {
        call(readChatMessages, 'textChat', 'ajaxChatMessages');
        $("#chatAlert").fadeOut(0);
        $("#chatAlert").fadeIn(500);
        document.getElementById("chatAlert").innerHTML = '<p class="alert alert-success">Message envoyé.</p>';
        $("#chatAlert").delay(1000).fadeOut(500);
    }
    else
    {
        $("#chatAlert").fadeOut(0);
        $("#chatAlert").fadeIn(500);
        document.getElementById("chatAlert").innerHTML = '<p class="alert alert-danger">Aucun message n\'a été envoyé.</p>';
        $("#chatAlert").delay(1000).fadeOut(500);
    }
    cleanTextChat();
}

function listChatMessages()
{
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/ajax/ajaxListChatMessages", true);
    xhr.send();
    xhr.onreadystatechange = function() 
    {
        if(xhr.readyState == 4)
        {
            readChatMessages(xhr.responseText);
        }
    };
}

function readChatMessages(data)
{
    if(document.getElementById("showChatMessages").innerHTML != data)
    {
        document.getElementById("showChatMessages").innerHTML = data;
    }
}

/*---- Users ----*/

function callUserInfo(name)
{
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/ajax/ajaxUserInfo", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("username="+name);
    xhr.onreadystatechange = function() 
    {
        if(xhr.readyState == 4)
        {
            userInfo(xhr.responseText);
        }
    };
}

function userInfo(data)
{
    $("#userInfo").fadeOut(0);
    $("#userInfo").fadeIn(500);
    if(document.getElementById("userInfo").innerHTML != data)
    {
        document.getElementById("userInfo").innerHTML = data;
    }
}

function callUserHistorique(name)
{
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/ajax/ajaxUserHistorique", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("username="+name);
    xhr.onreadystatechange = function() 
    {
        if(xhr.readyState == 4)
        {
            userHistorique(xhr.responseText);
        }
    };
}

function userHistorique(data)
{
    $("#userHistorique").fadeOut(0);
    $("#userHistorique").fadeIn(500);
    if(document.getElementById("userHistorique").innerHTML != data)
    {
        document.getElementById("userHistorique").innerHTML = data;
    }
}

/*---- FormSigninVilles ----*/

function readVilles(data)
{
    document.getElementById("showVilles").innerHTML = data;
}

/*---- FormSigninName ----*/

function readName(data)
{
    if(!document.getElementById("username").value == "" && document.getElementById("username").value.length > 2)
    {
        document.getElementById("usernameAlert").style.textAlign = "center";

        if(data == 'disponible')
        {
            document.getElementById("username").style.color = 'green';
        }
        else
        {
            document.getElementById("username").style.color = 'red';
        }

        if(!validationUsername(document.getElementById("username").value))
        {
            document.getElementById("usernameAlert").innerHTML = "Le nom d'utilisateur est invalide";
            document.getElementById("username").style.color = 'red';
        }
        else
        {
            document.getElementById("usernameAlert").innerHTML = "Le nom d'utilisateur est "+data;
        }
    }
    else
    {
        document.getElementById("usernameAlert").innerHTML = "";
        document.getElementById("username").style.color = 'black';
    }
}

/*---- FormSigninEmail ----*/

function readEmail(data)
{
    if(document.getElementById("email").value != "" && document.getElementById("email").value.length > 2)
    {
        if(data == 'disponible')
        {
            document.getElementById("email").style.color = 'green';
        }
        else
        {
            document.getElementById("email").style.color = 'red';
            if(document.getElementById("email").value == document.getElementById("userEmail").value)
            {
                document.getElementById("email").style.color = 'black';
            }
        }

        if(!validationEmail(document.getElementById("email").value))
        {
            document.getElementById("emailAlert").innerHTML = "L'email est invalide.";
            document.getElementById("email").style.color = 'red';
        }
        else
        {
            document.getElementById("emailAlert").innerHTML = "L'email est "+data+".";
            if(document.getElementById("email").value == document.getElementById("userEmail").value)
            {
                document.getElementById("emailAlert").innerHTML = "Vous utilisez déjà cette email.";
            }
        }
    }
    else
    {
        document.getElementById("emailAlert").innerHTML = "";
        document.getElementById("email").style.color = 'black';
    }
}