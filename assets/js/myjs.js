$(document).ready(function () {
  ReloadButtonExit();
  ReloadButtonExitX();
  // login
  login();
  //  Notification
  notification_controle_assurance();
  remove_notification_controle_assurance();
  notification_controle_visite();
  remove_notification_controle_visite();
  notification_fin_contrat();
  remove_notification_fin_contrat();
  notification_create_contrat();
  remove_notification_create_contrat();
  view_notification_record();
  view_notification_controle_papier_record();
  //  Agence
  insertAgenceRecord();
  insertAgenceRecordHeur();
  view_agence_record();
  searchAgence();
  get_agence_record();
  update_agence_record();
  delete_agence_record();
  delete_agence_heur_record();
  // Utilisateur
  view_user_record();
  searchUser();
  insertUserRecord();
  get_user_record();
  update_user_record();
  delete_user_record();
  // Client
  view_client_record();
  searchClient();
  insertClientRecord();
  get_papierclient_record();
  get_client_record();
  update_client_record();
  delete_client_record();
  // Voiture
  view_voiture_record();
  searchVoiture();
  insert_voiture_Record();
  get_voiture_record();
  update_voiture_record();
  delete_voiture_record();
  // Choice papier MAJ
  update_visite_assurance();
  update_voiture_visite();
  update_voiture_assurance();
  // Papier Voiture
  view_papier_voiture_record();
  searchPapierVoiture();
  update_voiture_papier();
  view_papier_archivage_voiture_record();
  // Marque Voiture
  view_marquevoiture_record();
  searchMarqueVoiture();
  insert_marquevoiture_Record();
  get_prix_marquevoiture_record();
  update_prix_marquevoiture_record();
  get_marquevoiture_record();
  update_marquevoiture_record();
  delete_marquevoiture_record();
  // Stock
  view_stockvoiture_record();
  searchStockVoiture();
  get_agence_voiture();
  transfert_voiture_agence_record();
  export_stock_voiture();
  // Contrat
  view_contrat_record();
  searchContrat();
  insert_contrat_Record();
  afficher_voiture_dispo();
  delete_contrat_record();
  // Contrat Archive
  view_contrat_archive_record();
  searchContratArchive();
  // Contrat Historique
  view_contrat_historique_record();
  searchContratHistorique();
  // Download Contrat
  get_contrat_pdf();
  // Entretien
  view_entretien_record();
  searchEntretien();
  afficher_voiture_agence();
  insert_entretien_Record();
  get_entretien_record();
  update_entretien_record();
  delete_entretien_record();
  // Entretien Archive
  view_entretien_archive_record();
  searchEntretienArchive();
  // Entretien Historique
  view_entretien_historique_record();
  searchEntretienHistorique();
  // Planing
  view_planing_contrat_record();
});

function ReloadButtonExit() {
  $(document).on("click", "#btn-close", function () {
    window.location.reload();
  });
}
function ReloadButtonExitX() {
  $(document).on("click", "#btn-close-x", function () {
    window.location.reload();
  });
}

function isValidEmailAddress(emailAddress) {
  var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  return pattern.test(emailAddress);
}

$(function () {
  $('#voitureMarqueModele').select2({
    dropdownParent: $('#voitureMarqueModele').parent()
  });
  $('#up_voitureMarqueModel').select2({
    dropdownParent: $('#up_voitureMarqueModel').parent()
  });
  $('#ClientContrat').select2({
    dropdownParent: $('#ClientContrat').parent()
  });
});

$(function () {
  var table = $("#tablefix");
  $(window).scroll(function () {
    var windowTop = $(window).scrollTop();
    if (windowTop > table.offset().top) {
      $("thead", table).addClass("Fixed").css("top", windowTop);
    }
    else {
      $("thead", table).removeClass("Fixed");
    }
  });
});

function selectrole(data) {
  if (data == "2") {
    $("#cont_UserAgence").show();
  } else {
    $("#cont_UserAgence").hide();
  }
}

function refreshImage(imgElement){    
  // create a new timestamp 
  var timestamp = new Date().getTime();
  var img = document.getElementById(imgElement);    
  var imgURL = img.src; 
  var queryString = "?t=" + timestamp;    
  img.src = imgURL + queryString;    
}      
 
function selectpiecejointe(data) {
  if (data == "1") {
    $("#cin").show();
    $("#passport").hide();
  } else {
    $("#passport").show();
    $("#cin").hide();
  }
}

function updatepiecejointe(data) {
  if (data == "1") {
    $("#up_cin").show();
    $("#up_passport").hide();
  } else {
    $("#up_passport").show();
    $("#up_cin").hide();
  }
}
// Login

function login(){
  $('#SubmitLogin').click(function(){
    var username=$("#login").val();
    var password=$("#password").val();
    var dataString = 'login='+username+'&password='+password;
    $("#erreur").empty();
    $("#obligatoireLogin").empty();
    $("#obligatoirePassword").empty();
    if (username == "" && password == "" ) {
      $("#obligatoireLogin").html("Login est obligatoire!");
      $("#obligatoirePassword").html("Password est obligatoire!");
    } else if (username == "") {
      $("#obligatoireLogin").html("Login est obligatoire!");
    } else if (password == "") {
      $("#obligatoirePassword").html("Mot de passe est obligatoire!");
    } else {
      $.ajax({
      type: "POST",
      url: "loginUser.php",
      data: dataString,
      cache: false,
      success: function(data){
        data = $.parseJSON(data);
        if(data.status=="success"){
          $('#rightContainer').css({'transform':'scaleX(0)','opacity':'0'});
          $('#VehiculeIcon').css({'transform':'translate(240%, 0px)','opacity':'0'});
          const loading = setTimeout(loadd, 1000);
          function loadd() {
            $('#loading').removeAttr('hidden');
          }
          const myTimeout = setTimeout(load, 1200);
          function load() {
            window.location.href = "dashboard.php";
          }
        } else if(data.status=="disable"){
          $('#oops').removeAttr('hidden');
          const loading = setTimeout(oppsError, 2000);
          function oppsError() {
            $('#oops').css({'transform':'scaleX(0)'});
          }
          $('#oops').removeAttr('hidden');
          const myTimeout = setTimeout(closeError, 3000);
          function closeError() {
            $('#oops').attr("hidden",true);
            $('#oops').css({'transform':'scaleX(1)'});
          }
        } else {
          $("#erreur").html("<img src='assets/images/login/Subtract.png' > <span style='font-weight: 700;font-size: 15px;line-height: 15px;color: #BF1616;'>Login ou mot de passe incorrect !</span> <a href='' id='reessayer' style='font-weight: 600;font-size: 15px;line-height: 15px;color: #0071C4;'>Veuillez essayer à nouveau.</a>");
          $(document).on("click", "#reessayer", function () {
            $("#login").val('');
            $("#password").val('');
            $("#erreur").empty();
          });
        }
      }
      });
      return false;
    }
  });
}

// Notification

function notification_controle_assurance(view = "") {
  const str2 = 'Non Lus';
  $.ajax({
    url: "controleassurancenotification.php",
    method: "POST",
    data: {
      view: view
    },
    dataType: "json",
    success: function (data) {
      $("#controle_assurance").html(data.notification_controle_assurance);
      if (data.count_fin_assurance > 0) {
        $("#count_assurance_fin").html(data.count_fin_assurance);
        $("#count_assurance_fin_not_vue").html(data.count_fin_assurance.concat(' ', str2));
      } else {
        $("#count_assurance_fin").css("display", "none");
      }
    },
  });
}

function remove_notification_controle_assurance() {
  $(document).on("click", "#toggle-controle-assurance", function () {
    $("#count_assurance_fin").html("0").css("display", "none");
    notification_controle_assurance("yes");
  });
}

function notification_controle_visite(view = "") {
  const str2 = 'Non Lus';
  $.ajax({
    url: "controlevisitenotification.php",
    method: "POST",
    data: {
      view: view
    },
    dataType: "json",
    success: function (data) {
      $("#controle_visite").html(data.notification_controle_visite);
      if (data.count_fin_visite > 0) {
        $("#count_visite_fin").html(data.count_fin_visite);
        $("#count_visite_fin_not_vue").html(data.count_fin_visite.concat(' ', str2));
      } else {
        $("#count_visite_fin").css("display", "none");
      }
    },
  });
}

function remove_notification_controle_visite() {
  $(document).on("click", "#toggle-controle-visite-technique", function () {
    $("#count_visite_fin").html("0").css("display", "none");
    notification_controle_visite("yes");
  });
}

function notification_fin_contrat(view = "") {
  const str2 = 'Non Lus';
  $.ajax({
    url: "contratnotification.php",
    method: "POST",
    data: {
      view: view
    },
    dataType: "json",
    success: function (data) {
      $("#contrat_prendront_fin").html(data.notification_fin_contrat);
      if (data.count_fin_contrat > 0) {
        $("#count_contrat_fin").html(data.count_fin_contrat);
        $("#count_contrat_fin_not_vue").html(data.count_fin_contrat.concat(' ', str2));
      } else {
        $("#count_contrat_fin").css("display", "none");
      }
    },
  });
}

function remove_notification_fin_contrat() {
  $(document).on("click", "#toggle-contrat-fin", function () {
    $("#count_contrat_fin").html("0").css("display", "none");
    notification_fin_contrat("yes");
  });
}

var bong = document.createElement('audio');
bong.setAttribute('src', 'sounds/notification.mp3');

function notification_create_contrat(view = "") {
  const str2 = 'Non Lus';
  $.ajax({
    url: "notification_create_contrat.php",
    method: "POST",
    data: {
      view: view
    },
    dataType: "json",
    success: function (data) {
      $("#contrat_crée").html(data.notification_create_contrat);
      if (data.count_create_contrat > 0) {
        $("#count_contrat_crée").html(data.count_create_contrat);
        $("#count_contrat_crée_not_vue").html(data.count_create_contrat.concat(' ', str2));
        bong.play();
      } else {
        $("#count_contrat_crée").css("display", "none");
      }
    },
  });
}

function remove_notification_create_contrat() {
  $(document).on("click", "#toggle-contrat-create", function () {
    $("#count_contrat_crée").html("0").css("display", "none");
    notification_create_contrat("yes");
  });
}

function view_notification_record() {
  var entretien_search = $("#search_entretien").val();
  $.ajax({
    url: "viewnotification.php",
    method: "post",
    data: {
      querytype: entretien_search,
    },
    success: function (response) {
      $("#notification-list").html(response);
    },
  });
}

function view_notification_controle_papier_record() {
  var search_controle_papier = $("#search_entretien_controle_papier").val();
  $.ajax({
    url: "viewnotificationcontrolepapier.php",
    method: "post",
    data: {
      search_controle_papier: search_controle_papier,
    },
    success: function (response) {
      $("#notification-controle-papier-list").html(response);
    },
  });
}

//  Agence
function view_agence_record() {
  $.ajax({
    url: "viewagence.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#agence-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchAgence() {
  $("#searchAgence").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchAgence.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#agence-list").html(response);
      },
    });
  });
}

function insertAgenceRecord() {
  $(document).on("click", "#show_form_agence", function () {
    $("#message")
    .removeClass("alert alert-danger")
    .html("");
    $("#Registration-Agence").modal("show");
  });
  $(document).on("click", "#btn-register-agence", function () {
    $("#Registration-Agence").scrollTop(0);
    const selects_jour = Array.from(document.querySelectorAll(".jour"));
    const selects_date_debut = Array.from(document.querySelectorAll(".heur-list-debut"));
    const selects_date_fin = Array.from(document.querySelectorAll(".heur-list-fin"));
    var JourListe = selects_jour.map((select) => select.value);
    var DateDebutListe = selects_date_debut.map((select) => select.value);
    var DateFinListe = selects_date_fin.map((select) => select.value);

    var agenceLieu = $("#agenceLieu").val();
    var agenceEmail = $("#agenceEmail").val();
    var agenceTel = $("#agenceTel").val();

    if (agenceLieu == "" || agenceEmail == "" || agenceTel == "") {
      $("#message").addClass("alert alert-danger").html("Veuillez remplir tous les champs obligatoires !");
    } else if (!isValidEmailAddress(agenceEmail)) {
      $("#message").addClass("alert alert-danger").html("Email est invalide !");
    } else {
      var form_data = new FormData();
      form_data.append("agenceLieu", agenceLieu);
      form_data.append("agenceEmail", agenceEmail);
      form_data.append("agenceTel", agenceTel);
      form_data.append("JourListe", JourListe);
      form_data.append("DateDebutListe", DateDebutListe);
      form_data.append("DateFinListe", DateFinListe);
      $.ajax({
        url: "AjoutAgence.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#Registration-Agence").modal("hide");
            $("#addagence_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecAddAgence").modal("show");
            setTimeout(function () {
              if ($("#EchecAddAgence").length > 0) {
                $("#EchecAddAgence").modal("hide");
              }
            }, 3000);
            view_agence_record();
          } else {
            $("#Registration-Agence").modal("hide");
            $("#addagence_success").addClass("text-checked").html(data);
            $("#SuccessAddAgence").modal("show");
            $("#addagence_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddAgence").length > 0) {
                $("#SuccessAddAgence").modal("hide");
              }
            }, 3000);
            view_agence_record();
          }
        },
      });
    }
  });
  $('#Registration-Agence').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
}

function insertAgenceRecordHeur() {
  $(document).on("click", "#show_form_horaire_agence", function () {
    $("#message_heure")
    .removeClass("alert alert-danger")
    .html("");
    $("#Registration-Agence-Heur").modal("show");
  });
  $(document).on("click", "#btn-register-agence-heur", function () {
    $("#Registration-Agence-Heur").scrollTop(0);
    var IdAgence = $("#IdAgence").val();
    var jourH = $("#jourH").val();
    var heurdebutH = $("#fetch-heurdebutH").val();
    var heurfinH = $("#fetch-heurfinH").val();
    if (IdAgence == null || jourH == null || heurdebutH == "" || heurfinH == "") {
      $("#message_heure").addClass("alert alert-danger").html("Veuillez remplir tous les champs obligatoires !");
    } 
    else if(heurfinH <= heurdebutH) {
      $("#message_heure").addClass("alert alert-danger").html("Heure de fin doit etre superieur à heure de debut !");
    }
    else {
      var form_data = new FormData();
      form_data.append("IdAgence", IdAgence);
      form_data.append("jourH", jourH);
      form_data.append("heurdebutH", heurdebutH);
      form_data.append("heurfinH", heurfinH);
      $.ajax({
        url: "AjoutAgenceHeur.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#Registration-Agence-Heur").modal("hide");
            $("#addagencehoraire_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecAddAgence-Heur").modal("show");
            setTimeout(function () {
              if ($("#EchecAddAgence-Heur").length > 0) {
                $("#EchecAddAgence-Heur").modal("hide");
              }
            }, 3000);
            view_agence_record();
          } else {
            $("#Registration-Agence-Heur").modal("hide");
            $("#addagencehoraire_success").addClass("text-checked").html(data);
            $("#SuccessAddAgence-Heur").modal("show");
            $("#addagencehoraire_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddAgence-Heur").length > 0) {
                $("#SuccessAddAgence-Heur").modal("hide");
              }
            }, 3000);
            view_agence_record();
          }
        },
      });
    }
  });
  $('#Registration-Agence-Heur').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
}

function get_agence_record() {
  $(document).on("click", "#btn-edit-agence", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_agence_data.php",
      method: "post",
      data: { ClientID: ID },
      dataType: "JSON",
      success: function (data) {
        $("#up_idagence").val(data[0]);
        $("#up_agenceLieu").val(data[1]);
        $("#up_agenceEmail").val(data[2]);
        $("#up_agenceTel").val(data[3]);
        $("#updateAgence").modal("show");
      },
    });
  });
}

function update_agence_record() {
  $(document).on("click", "#btn_update_agence", function () {
    $("#up_message")
    .removeClass("alert alert-danger")
    .html("");
    $("#updateAgence").modal("show");
    $("#updateAgence").scrollTop(0);
    var up_idagence = $("#up_idagence").val();
    var up_agenceLieu = $("#up_agenceLieu").val();
    var up_agenceEmail = $("#up_agenceEmail").val();
    var up_agenceTel = $("#up_agenceTel").val();

    if (up_agenceLieu == "" || up_agenceEmail == "" || up_agenceTel == "") {
      $("#up_message").addClass("alert alert-danger").html("Les champs obligatoires ne peuvent pas être nuls !");
    } else {
      var form_data = new FormData();
      form_data.append("up_idagence", up_idagence);
      form_data.append("up_agenceLieu", up_agenceLieu);
      form_data.append("up_agenceEmail", up_agenceEmail);
      form_data.append("up_agenceTel", up_agenceTel);
      $.ajax({
        url: "update_agence.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updateAgence").modal("hide");
            $("#upagence_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecUpAgence").modal("show");
            setTimeout(function () {
              if ($("#EchecUpAgence").length > 0) {
                $("#EchecUpAgence").modal("hide");
              }
            }, 3000);
            view_agence_record();
          } else {
            $("#updateAgence").modal("hide");
            $("#upagence_success").addClass("text-checked").html(data);
            $("#SuccessUpAgence").modal("show");
            $("#upagence_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpAgence").length > 0) {
                $("#SuccessUpAgence").modal("hide");
              }
            }, 3000);
            view_agence_record();
          }
        },
      });
    }
  });
}

function delete_agence_record() {
  $(document).on("click", "#btn-delete-agence", function () {
    var Delete_ID = $(this).attr("data-id1");
    $("#deleteAgence").modal("show");
    $(document).on("click", "#btn_delete_agence", function () {
      $.ajax({
        url: "delete_agence.php",
        method: "post",
        data: { Delete_AgenceID: Delete_ID },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#deleteAgence").modal("hide");
            $("#deleteagence_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecDeleteAgence").modal("show");
            setTimeout(function () {
              if ($("#EchecDeleteAgence").length > 0) {
                $("#EchecDeleteAgence").modal("hide");
              }
            }, 3000);
            view_agence_record();
          } else {
            $("#deleteAgence").modal("hide");
            $("#deleteagence_success").addClass("text-checked").html(data);
            $("#SuccessDeleteAgence").modal("show");
            $("#deleteagence_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessDeleteAgence").length > 0) {
                $("#SuccessDeleteAgence").modal("hide");
              }
            }, 3000);
            view_agence_record();
          }
        },
      });
    });
  });
}

function delete_agence_heur_record() {
  $(document).on("click", "#btn-delete-agence-heur", function () {
    var Delete_ID = $(this).attr("data-id4");
    $("#deleteAgenceHeur").modal("show");
    $(document).on("click", "#btn-delete-confirm-agence-heur", function () {
      $.ajax({
        url: "delete_agence_heur.php",
        method: "post",
        data: { Delete_AgenceID: Delete_ID },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#deleteAgenceHeur").modal("hide");
            $("#deletehoragence_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecDeleteHorAgence").modal("show");
            setTimeout(function () {
              if ($("#EchecDeleteHorAgence").length > 0) {
                $("#EchecDeleteHorAgence").modal("hide");
              }
            }, 3000);
            view_agence_record();
          } else {
            $("#deleteAgenceHeur").modal("hide");
            $("#deletehoragence_success").addClass("text-checked").html(data);
            $("#SuccessDeleteHorAgence").modal("show");
            $("#deletehoragence_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessDeleteHorAgence").length > 0) {
                $("#SuccessDeleteHorAgence").modal("hide");
              }
            }, 3000);
            view_agence_record();
          }
        },
      });
    });
  });
}

//Utilisateur

function view_user_record() {
  $.ajax({
    url: "viewuser.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#user-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchUser() {
  $("#searchUser").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchUser.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#user-list").html(response);
      },
    });
  });
}

function insertUserRecord() {
  $(document).on("click", "#show_form_user", function () {
    $("#message")
    .removeClass("alert alert-danger")
    .html("");
    $("#Registration-User").modal("show");
  });
  $(document).on("click", "#btn-register-user", function () {
    $("#Registration-User").scrollTop(0);
    var typeuser = $("#roletype").val();
    var nom = $("#userName").val();
    var login = $("#userLogin").val();
    var email = $("#userEmail").val();
    var password = $("#userPassword").val();
    var id_user_agence = $("#UserAgence").val();

    if (typeuser == null || nom == "" || login == "" || password == "") {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else {
      var form_data = new FormData();
      form_data.append("typeuser", typeuser);
      form_data.append("nom", nom);
      form_data.append("login", login);
      form_data.append("email", email);
      form_data.append("password", password);
      form_data.append("id_user_agence", id_user_agence);
      $.ajax({
        url: "AjoutUser.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#Registration-User").modal("hide");
            $("#adduser_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecAddUser").modal("show");
            setTimeout(function () {
              if ($("#EchecAddUser").length > 0) {
                $("#EchecAddUser").modal("hide");
              }
            }, 3000);
            view_user_record();
          } else {
            $("#Registration-User").modal("hide");
            $("#adduser_success").addClass("text-checked").html(data);
            $("#SuccessAddUser").modal("show");
            $("#adduser_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddUser").length > 0) {
                $("#SuccessAddUser").modal("hide");
              }
            }, 3000);
            view_user_record();
          }
        },
      });
    }
  });
  $('#Registration-User').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
}

function get_user_record() {
  $(document).on("click", "#btn-edit-user", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_user_data.php",
      method: "post",
      data: {
        ClientID: ID
      },
      dataType: "JSON",
      success: function (data) {
        $("#up_iduser").val(data[0]);
        $("#up_userName").val(data[1]);
        $("#up_userLogin").val(data[2]);
        $("#updateuseretat").val(data[3]);
        $("#up_userEmail").val(data[4]);
        $("#updateUser").modal("show");

      },
    });
  });
}

function update_user_record() {
  $(document).on("click", "#btn_update_user", function () {
    $("#up_message")
    .removeClass("alert alert-danger")
    .html("");
    $("#updateUser").scrollTop(0);
    var updateuserID = $("#up_iduser").val();
    var updateuserName = $("#up_userName").val();
    var updateuserLogin = $("#up_userLogin").val();
    var updateuserPassword = $("#up_userPassword").val();
    var updateuseretat = $("#updateuseretat").val();
    var updateuserEmail = $("#up_userEmail").val();
    if (updateuserName == "" || updateuserLogin == "" || updateuserPassword == "") {
      $("#up_message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
      $("#updateUser").modal("show");
    } else {
      var form_data = new FormData();
      form_data.append("_id", updateuserID);
      form_data.append("nom", updateuserName);
      form_data.append("login", updateuserLogin);
      form_data.append("password", updateuserPassword);
      form_data.append("updateuseretat", updateuseretat);
      form_data.append("email", updateuserEmail);
      $.ajax({
        url: "update_user.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updateUser").modal("hide");
            $("#upuser_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecUpUser").modal("show");
            setTimeout(function () {
              if ($("#EchecUpUser").length > 0) {
                $("#EchecUpUser").modal("hide");
              }
            }, 3000);
            view_user_record();
          } else {
            $("#updateUser").modal("hide");
            $("#upuser_success").addClass("text-checked").html(data);
            $("#SuccessUpUser").modal("show");
            $("#upuser_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpUser").length > 0) {
                $("#SuccessUpUser").modal("hide");
              }
            }, 3000);
            view_user_record();
          }
        },
      });
    }
  });
}

function delete_user_record() {
  $(document).on("click", "#btn-delete-user", function () {
    var Delete_ID = $(this).attr("data-id1");
    $("#deleteUser").modal("show");
    $(document).on("click", "#btn_delete_user", function () {
      $.ajax({
        url: "delete_user.php",
        method: "post",
        data: {
          Delete_UserID: Delete_ID
        },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#deleteUser").modal("hide");
            $("#deleteuser_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecDeleteUser").modal("show");
            setTimeout(function () {
              if ($("#EchecDeleteUser").length > 0) {
                $("#EchecDeleteUser").modal("hide");
              }
            }, 3000);
            view_user_record();
          } else {
            $("#deleteUser").modal("hide");
            $("#deleteuser_success").addClass("text-checked").html(data);
            $("#SuccessDeleteUser").modal("show");
            $("#deleteuser_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessDeleteUser").length > 0) {
                $("#SuccessDeleteUser").modal("hide");
              }
            }, 3000);
            view_user_record();
          }
        },
      });
    });
  });
}

// Client

function view_client_record() {
  $.ajax({
    url: "viewclient.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#client-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchClient() {
  $("#searchClient").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchClient.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#client-list").html(response);
      },
    });
  });
}

function insertClientRecord() {
  $(document).on("click", "#show_form_client", function () {
    $("#message_client")
    .removeClass("alert alert-danger")
    .html("");
    $("#Registration-Client").modal("show");
  });
  $(document).on("click", "#btn-register-client", function () {
    $("#Registration-Client").scrollTop(0);
    var clientNom = $("#clientNom").val();
    var clientPrenom = $("#clientPrenom").val();
    var ClientDateNaissance = $("#clientDateNaissance").val();
    var ClientLieuNaissance = $("#clientLieuNaissance").val();
    var ClientEmail = $("#clientEmail").val();
    var ClientPhone = $("#clientPhone").val();
    var ClientAdresse = $("#clientAdresse").val();
    // informations papiers
    // CIN
    var ClientNumCin = $("#clientNumCin").val();
    var ClientDateCin = $("#clientDateCin").val();
    var ClientRectoCin = $("#clientRectoCin").prop("files")[0];
    var ClientVersoCin = $("#clientVersoCin").prop("files")[0];
    // Passport
    var ClientNumPassport = $("#clientNumPassport").val();
    var ClientDatePassport = $("#clientDatePassport").val();
    var ClientPassport = $("#clientPassport").prop("files")[0];
    // Permis
    var ClientNumPermis = $("#clientNumPermis").val();
    var ClientDatePermis = $("#clientDatePermis").val();
    var clientLieuPermis = $("#clientLieuPermis").val();
    var ClientPermis = $("#clientPermis").prop("files")[0];
    
    // Choix du papier
    const radioPieces = document.querySelectorAll('input[name="Piece"]');
    let Piece;
    for (const radioPiece of radioPieces) {
        if (radioPiece.checked) {
            Piece = radioPiece.value;
            break;
        }
    }

    if (clientNom == "" || clientPrenom == "" || ClientDateNaissance == "" || ClientLieuNaissance == "" || ClientEmail == "" || ClientPhone == "" || ClientAdresse == "") {
      $("#message_client")
        .addClass("alert alert-danger")
        .html("Veuillez remplir les champs des informations personnelles !");
    } else if (Piece == undefined) {
      $("#message_client")
        .addClass("alert alert-danger")
        .html("Veuillez choisir la pièce d'identité !");
    } else if (Piece == 1 && (ClientNumCin == "" || ClientDateCin == "" || ClientRectoCin == null || ClientVersoCin == null)) {
      $("#message_client")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier les champs du CIN !");
    } else if (Piece == 0 && (ClientNumPassport == "" || ClientDatePassport == "" || ClientPassport == null)) {
      $("#message_client")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier les champs du passport !");
    } else if (ClientNumPermis == "" || ClientDatePermis == "" || clientLieuPermis == "" || ClientPermis == null) {
      $("#message_client")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier les champs du permis !");
    } else if (!isValidEmailAddress(ClientEmail)) {
      $("#message_client")
        .addClass("alert alert-danger")
        .html("le champ « email » est invalide");
    } else {
      var form_data = new FormData();
      form_data.append("clientNom", clientNom);
      form_data.append("clientPrenom", clientPrenom);
      form_data.append("ClientDateNaissance", ClientDateNaissance);
      form_data.append("ClientLieuNaissance", ClientLieuNaissance);
      form_data.append("ClientEmail", ClientEmail);
      form_data.append("ClientPhone", ClientPhone);
      form_data.append("ClientAdresse", ClientAdresse);
      form_data.append("ClientNumCin", ClientNumCin);
      form_data.append("ClientDateCin", ClientDateCin);
      form_data.append("ClientRectoCin", ClientRectoCin);
      form_data.append("ClientVersoCin", ClientVersoCin);
      form_data.append("ClientNumPassport", ClientNumPassport);
      form_data.append("ClientDatePassport", ClientDatePassport);
      form_data.append("ClientPassport", ClientPassport);
      form_data.append("ClientNumPermis", ClientNumPermis);
      form_data.append("ClientDatePermis", ClientDatePermis);
      form_data.append("clientLieuPermis", clientLieuPermis);
      form_data.append("ClientPermis", ClientPermis);
      $.ajax({
        url: "AjoutClient.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#Registration-Client").modal("hide");
            $("#addclient_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecAddClient").modal("show");
            setTimeout(function () {
              if ($("#EchecAddClient").length > 0) {
                $("#EchecAddClient").modal("hide");
              }
            }, 2000);
            setTimeout(function () {
              if ($("#EchecAddClient").length > 0) {
                location.reload();
              }
            }, 2000);
          } else {
            $("#Registration-Client").modal("hide");
            $("#addclient_success").addClass("text-checked").html(data);
            $("#SuccessAddClient").modal("show");
            $("#addclient_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddClient").length > 0) {
                $("#SuccessAddClient").modal("hide");
              }
            }, 2000);
            setTimeout(function () {
              if ($("#SuccessAddClient").length > 0) {
                location.reload();
              }
            }, 2000);
          }
        },
      });
    }
  });
  $('#Registration-Client').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
}

function get_papierclient_record() {
  $(document).on("click", "#btn-show-papierclient", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_papierclient_data.php",
      method: "post",
      data: {
        ClientID: ID
      },
      dataType: "JSON",
      success: function (data) {     
        $("#cin_recto").html('<a href="uploadfile/client/cin/'+data[0]+'" target="_blank"><img width="100%" height="150px" id="id_cin_recto" src="uploadfile/client/cin/'+data[0]+'">Recto</a>');
        $("#cin_verso").html('<a href="uploadfile/client/cin/'+data[1]+'" target="_blank"><img width="100%" height="150px" id="id_cin_verso" src="uploadfile/client/cin/'+data[1]+'">Verso</a>');
        $("#file_passport").html('<a href="uploadfile/client/passport/'+data[2]+'" target="_blank"><img width="100%" height="150px" id="id_passport" src="uploadfile/client/passport/'+data[2]+'"></a>');
        $("#permis").html('<a href="uploadfile/client/permis/'+data[3]+'" target="_blank"><img width="100%" height="150px" id="id_permis" src="uploadfile/client/permis/'+data[3]+'"></a>');
        refreshImage("id_cin_recto");
        refreshImage("id_cin_verso");
        refreshImage("id_passport");
        refreshImage("id_permis");
        $("#papierClient").modal("show");
      },
    });
  });
}

function get_client_record() {
  $(document).on("click", "#btn-edit-client", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_client_data.php",
      method: "post",
      data: {
        ClientID: ID
      },
      dataType: "JSON",
      success: function (data) {
        $("#up_idclient").val(data[0]);
        $("#up_clientNom").val(data[1]);
        $("#up_clientPrenom").val(data[2]);
        $("#up_clientDateNaissance").val(data[3]);
        $("#up_clientLieuNaissance").val(data[4]);
        $("#up_clientEmail").val(data[5]);
        $("#up_clientPhone").val(data[6]);
        $("#up_clientAdresse").val(data[7]);
        $("#up_clientNumCin").val(data[8]);
        $("#up_clientDateCin").val(data[9]);
        $("#up_clientNumPassport").val(data[10]);
        $("#up_clientDatePassport").val(data[11]);
        $("#up_clientNumPermis").val(data[12]);
        $("#up_clientDatePermis").val(data[13]);
        $("#up_clientLieuPermis").val(data[14]);
        $("#up_clientRectoCin").val();
        $("#up_clientVersoCin").val();
        $("#up_clientPassport").val();
        $("#up_clientPermis").val();
        $("#updateClient").modal("show");
      },
    });
  });
}

function update_client_record() {
  $(document).on("click", "#btn_update", function () {
    $("#up_message").removeClass("alert alert-danger").html("");
    $("#updateClient").scrollTop(0);
    var up_idclient = $("#up_idclient").val();
    var up_clientNom = $("#up_clientNom").val();
    var up_clientPrenom = $("#up_clientPrenom").val();
    var up_clientDateNaissance = $("#up_clientDateNaissance").val();
    var up_clientLieuNaissance = $("#up_clientLieuNaissance").val();
    var up_clientEmail = $("#up_clientEmail").val();
    var up_clientPhone = $("#up_clientPhone").val();
    var up_clientAdresse = $("#up_clientAdresse").val();
    var up_clientNumCin = $("#up_clientNumCin").val();
    var up_clientDateCin = $("#up_clientDateCin").val();
    var up_clientNumPassport = $("#up_clientNumPassport").val();
    var up_clientDatePassport = $("#up_clientDatePassport").val();
    var up_clientNumPermis = $("#up_clientNumPermis").val();
    var up_clientDatePermis = $("#up_clientDatePermis").val();
    var up_clientLieuPermis = $("#up_clientLieuPermis").val();
    var up_clientRectoCin = $("#up_clientRectoCin").prop("files")[0];
    var up_clientVersoCin = $("#up_clientVersoCin").prop("files")[0];
    var up_clientPassport = $("#up_clientPassport").prop("files")[0];
    var up_clientPermis = $("#up_clientPermis").prop("files")[0];

    if (up_clientNom == "" || up_clientPrenom == "" || up_clientDateNaissance == "" || up_clientLieuNaissance == "" || up_clientEmail == "" || up_clientPhone == "" || up_clientAdresse == ""
        || up_clientNumPermis == "" || up_clientDatePermis == "" || up_clientLieuPermis == "") {
      $("#up_message").addClass("alert alert-danger").html("Veuillez remplir tous les champs obligatoires !");
    } else if (!isValidEmailAddress(up_clientEmail)) {
      $("#up_message").addClass("alert alert-danger").html("le champ « email » est invalide");
    } else {
      var form_data = new FormData();
      form_data.append("up_idclient", up_idclient);
      form_data.append("up_clientNom", up_clientNom);
      form_data.append("up_clientPrenom", up_clientPrenom);
      form_data.append("up_clientDateNaissance", up_clientDateNaissance);
      form_data.append("up_clientLieuNaissance", up_clientLieuNaissance);
      form_data.append("up_clientEmail", up_clientEmail);
      form_data.append("up_clientPhone", up_clientPhone);
      form_data.append("up_clientAdresse", up_clientAdresse);
      form_data.append("up_clientNumCin", up_clientNumCin);
      form_data.append("up_clientDateCin", up_clientDateCin);
      form_data.append("up_clientNumPassport", up_clientNumPassport);
      form_data.append("up_clientDatePassport", up_clientDatePassport);
      form_data.append("up_clientNumPermis", up_clientNumPermis);
      form_data.append("up_clientDatePermis", up_clientDatePermis);
      form_data.append("up_clientLieuPermis", up_clientLieuPermis);
      form_data.append("up_clientRectoCin", up_clientRectoCin);
      form_data.append("up_clientVersoCin", up_clientVersoCin);
      form_data.append("up_clientPassport", up_clientPassport);
      form_data.append("up_clientPermis", up_clientPermis);
      $.ajax({
        url: "update_client.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updateClient").modal("hide");
            $("#upclient_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecUpClient").modal("show");
            setTimeout(function () {
              if ($("#EchecUpClient").length > 0) {
                $("#EchecUpClient").modal("hide");
              }
            }, 2000);
            view_client_record();
          } else {
            $("#updateClient").modal("hide");
            $("#upclient_success").addClass("text-checked").html(data);
            $("#SuccessUpClient").modal("show");
            $("#upclient_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpClient").length > 0) {
                $("#SuccessUpClient").modal("hide");
              }
            }, 2000);
            view_client_record();
          }
        },
      });
    }
  });
}

function delete_client_record() {
  $(document).on("click", "#btn-delete-client", function () {
    var Delete_ID = $(this).attr("data-id1");
    $("#deleteClient").modal("show");
    $(document).on("click", "#btn_delete", function () {
      $.ajax({
        url: "delete_client.php",
        method: "post",
        data: {
          Delete_ClientID: Delete_ID
        },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#deleteClient").modal("hide");
            $("#deleteclient_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecDeleteClient").modal("show");
            setTimeout(function () {
              if ($("#EchecDeleteClient").length > 0) {
                $("#EchecDeleteClient").modal("hide");
              }
            }, 2000);
            view_client_record();
          } else {
            $("#deleteClient").modal("hide");
            $("#deleteclient_success").addClass("text-checked").html(data);
            $("#SuccessDeleteClient").modal("show");
            $("#deleteclient_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessDeleteClient").length > 0) {
                $("#SuccessDeleteClient").modal("hide");
              }
            }, 2000);
            view_client_record();
          }
        },
      });
    });
  });
}

// Voiture 

function view_voiture_record() {
  $.ajax({
    url: "viewvoiture.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#voiture-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchVoiture() {
  $("#searchVoiture").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchVoiture.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#voiture-list").html(response);
      },
    });
  });
}

function insert_voiture_Record() {
  $(document).on("click", "#show_form_voiture", function () {
    $("#message")
    .removeClass("alert alert-danger")
    .html("");  
    $("#Registration-Voiture").modal("show");
  });
  $(document).on("click", "#btn-register-voiture", function () {
    $("#Registration-Voiture").scrollTop(0);
    var voiturepimm1 = $("#voiturepimm1").val();
    var voiturepimm2 = $("#voiturepimm2").val();
    var voitureMarqueModel = $("#voitureMarqueModele").val();
    var voituretypecarburant = $("#voituretypecarburant").val();
    var voitureboitevitesse = $("#voitureboitevitesse").val();
    var voiturenbreplace = $("#voiturenbreplace").val();
    var voiturenbrevalise = $("#voiturenbrevalise").val();
    var voiturepuissance = $("#voiturepuissance").val();
    var voiturecartegrise = $("#voiturecartegrise").prop("files")[0];
    var voitureassurance = $("#voitureassurance").prop("files")[0];
    var voiturevignette = $("#voiturevignette").prop("files")[0];
    var prixassurance = $("#prixassurance").val();
    var datefinassurance = $("#datefinassurance").val();
    var visitetechniquevoiture = $("#visitetechniquevoiture").prop("files")[0];
    var prixvisitetechnique = $("#prixvisitetechnique").val();
    var datefinvisitetechnique = $("#datefinvisitetechnique").val();
    var voitureagence = $("#voitureagence").val();
    // Checkbox
    let checkboxes = document.querySelectorAll('input[name="voitureclim"]:checked');
    let voitureclimatisation = [];
    checkboxes.forEach((checkbox) => {
      voitureclimatisation.push(checkbox.value);
    });
    if (voiturevignette == null || prixassurance == "" || datefinassurance == "" || visitetechniquevoiture == null || prixvisitetechnique == "" || datefinvisitetechnique == ""  || voiturepimm1 == "" || voiturepimm2 == "" || voiturenbreplace == "" || voiturepuissance == "" || voitureagence == "" ||
      voitureMarqueModel == null || voituretypecarburant == null || voitureboitevitesse == null || voiturenbrevalise == null || voiturecartegrise == null || voitureassurance == null) {
      $("#message").addClass("alert alert-danger").html("Veuillez remplir tous les champs obligatoires !");
    } else if (voiturepimm1 > 999 || voiturepimm1 < 10 || voiturepimm2 > 9999 || voiturepimm2 < 0) {
      $("#message").addClass("alert alert-danger").html("Veuillez vérifier l'immatriculation du voiture !");
    } else if (voiturepuissance > 20 || voiturepuissance < 1 ) {
      $("#message").addClass("alert alert-danger").html("Veuillez vérifier la puissance du voiture !");
    } else if (voiturenbreplace > 10 || voiturenbreplace < 1 ) {
      $("#message").addClass("alert alert-danger").html("Veuillez vérifier le nombre de place du voiture !");
      } else if (datefinassurance <= Date.now()  ) {
      $("#message").addClass("alert alert-danger").html("Veuillez vérifier Date fin assurance !");
        } else if (datefinvisitetechnique <= Date.now()  ) {
      $("#message").addClass("alert alert-danger").html("Veuillez vérifier Date fin visite technique !");
      } else {
      if (voitureagence == null) {
        voitureagence = 0;
      }
      var form_data = new FormData();
      form_data.append("voiturepimm1", voiturepimm1);
      form_data.append("voiturepimm2", voiturepimm2);
      form_data.append("voitureMarqueModel", voitureMarqueModel);
      form_data.append("voituretypecarburant", voituretypecarburant);
      form_data.append("voitureboitevitesse", voitureboitevitesse);
      form_data.append("voiturenbreplace", voiturenbreplace);
      form_data.append("voiturenbrevalise", voiturenbrevalise);
      form_data.append("voiturepuissance", voiturepuissance);
      form_data.append("voitureclimatisation", voitureclimatisation);
      form_data.append("voiturecartegrise", voiturecartegrise);
      form_data.append("voitureassurance", voitureassurance);
      form_data.append("voitureagence", voitureagence);
      form_data.append("voiturevignette", voiturevignette);
      form_data.append("prixassurance", prixassurance);
      form_data.append("datefinassurance", datefinassurance);
      form_data.append("visitetechniquevoiture", visitetechniquevoiture);
      form_data.append("prixvisitetechnique", prixvisitetechnique);
      form_data.append("datefinvisitetechnique", datefinvisitetechnique);
      $.ajax({
        url: "AjoutVoiture.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
              $("#Registration-Voiture").modal("hide");
              $("#addvoiture_echec").removeClass("text-checked").addClass("text-echec").html(data);
              $("#EchecAddVoiture").modal("show");
            setTimeout(function () {
                $("#EchecAddVoiture").modal("hide");
            }, 2000);

          } else {
            $("#Registration-Voiture").modal("hide");
            $("#addvoiture_success").removeClass("text-echec").addClass("text-checked").html(data); 
            $("#SuccessAddVoiture").modal("show");
          setTimeout(function () {
              $("#SuccessAddVoiture").modal("hide");
              view_voiture_record();
          }, 2000);
          }
        },
      });
    }
  });
  $('#Registration-Voiture').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
}

function get_voiture_record() {
  $(document).on("click", "#btn-edit-voiture", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_voiture_data.php",
      method: "post",
      data: {
        id_voiture: ID
      },
      dataType: "JSON",
      success: function (data) {
        $("#up_voitureid").val(data[0]);
        $("#up_voiturepimm1").val(data[1]);
        $("#up_voiturepimm2").val(data[2]);
        $("#up_voitureMarqueModel").val(data[3]);
        $("#up_voituretypecarburant").val(data[4]);
        $("#up_voitureboitevitesse").val(data[5]);
        $("#up_voitureagence").val(data[6]);
        $("#up_voiturenbreplace").val(data[7]);
        $("#up_voiturenbrevalise").val(data[8]);
        $("#up_voiturepuissance").val(data[9]);
        $("#up_voiturecartegrise").val();
        // get value checkbox
        if (data[10] == "1") {
          document.getElementById('up_voitureavecclim').setAttribute('checked', 'checked');
        } else {
          document.getElementById('up_voituresansclim').setAttribute('checked', 'checked');
        }
        $("#updateVoiture").modal("show");
      },
    });
  });
}

function update_voiture_record() {
  $(document).on("click", "#btn_update_voiture", function () {
    $("#up_message")
    .removeClass("alert alert-danger")
    .html("");  
    $("#updateVoiture").scrollTop(0);
    var up_voitureid = $("#up_voitureid").val();
    var up_voiturepimm1 = $("#up_voiturepimm1").val();
    var up_voiturepimm2 = $("#up_voiturepimm2").val();
    var up_voitureMarqueModel = $("#up_voitureMarqueModel").val();
    var up_voituretypecarburant = $("#up_voituretypecarburant").val();
    var up_voitureboitevitesse = $("#up_voitureboitevitesse").val();
    var up_voiturenbreplace = $("#up_voiturenbreplace").val();
    var up_voiturenbrevalise = $("#up_voiturenbrevalise").val();
    var up_voiturepuissance = $("#up_voiturepuissance").val();
    var up_voiturecartegrise = $("#up_voiturecartegrise").prop("files")[0];
    // Checkbox
    let checkboxes = document.querySelectorAll('input[name="voitureclim"]:checked');
    let up_voitureclimatisation = [];
    checkboxes.forEach((checkbox) => {
      up_voitureclimatisation.push(checkbox.value);
    });
    if (up_voiturepimm1 == "" || up_voiturepimm2 == "" || up_voiturenbreplace == "" || up_voiturepuissance == "" || up_voitureclimatisation == "" ||
      up_voitureMarqueModel == null || up_voituretypecarburant == null || up_voitureboitevitesse == null || up_voiturenbrevalise == null) {
      $("#up_message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else if (up_voiturepimm1 > 999 || up_voiturepimm1 < 10 || up_voiturepimm2 > 9999 || up_voiturepimm2 < 0) {
      $("#up_message")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier l'immatriculation du voiture !");
    } else if (up_voiturepuissance > 20 || up_voiturepuissance < 1 ) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier la puissance du voiture !");
    } else if (up_voiturenbreplace > 10 || up_voiturenbreplace < 1 ) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier le nombre de place du voiture !");
    } else {
      var form_data = new FormData();
      form_data.append("up_voitureid", up_voitureid);
      form_data.append("up_voiturepimm1", up_voiturepimm1);
      form_data.append("up_voiturepimm2", up_voiturepimm2);
      form_data.append("up_voitureMarqueModel", up_voitureMarqueModel);
      form_data.append("up_voituretypecarburant", up_voituretypecarburant);
      form_data.append("up_voitureboitevitesse", up_voitureboitevitesse);
      form_data.append("up_voiturenbreplace", up_voiturenbreplace);
      form_data.append("up_voiturenbrevalise", up_voiturenbrevalise);
      form_data.append("up_voiturepuissance", up_voiturepuissance);
      form_data.append("up_voitureclimatisation", up_voitureclimatisation);
      form_data.append("up_voiturecartegrise", up_voiturecartegrise);

      $.ajax({
        url: "update_voiture.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updateVoiture").modal("hide");
            $("#upvoiture_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecUpVoiture").modal("show");
            setTimeout(function () {
              if ($("#EchecUpVoiture").length > 0) {
                $("#EchecUpVoiture").modal("hide");
              }
            }, 2000);
            view_voiture_record();
          } else {
            $("#updateVoiture").modal("hide");
            $("#upvoiture_success").addClass("text-checked").html(data);
            $("#SuccessUpVoiture").modal("show");
            $("#upvoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpVoiture").length > 0) {
                var imgGrise = "grise-"+up_voitureid; 
                refreshImage(imgGrise);  
                $("#SuccessUpVoiture").modal("hide"); 
                location.reload(); 
              }
            }, 3000);
            view_voiture_record();
          }
        },
      });
    }
  });
}

function delete_voiture_record() {
  $(document).on("click", "#btn-delete-voiture", function () {
    var Delete_ID = $(this).attr("data-id1");
    $("#deleteVoiture").modal("show");
    $(document).on("click", "#btn_delete", function () {
      $.ajax({
        url: "delete_voiture.php",
        method: "post",
        data: {
          id_voiture: Delete_ID
        },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#deleteVoiture").modal("hide");
            $("#deletevoiture_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecDeleteVoiture").modal("show");
            setTimeout(function () {
              if ($("#EchecDeleteVoiture").length > 0) {
                $("#EchecDeleteVoiture").modal("hide");
              }
            }, 2000);
            view_voiture_record();
          } else {
            $("#deleteVoiture").modal("hide");
            $("#deletevoiture_success").addClass("text-checked").html(data);
            $("#SuccessDeleteVoiture").modal("show");
            $("#deletevoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessDeleteVoiture").length > 0) {
                $("#SuccessDeleteVoiture").modal("hide");
              }
            }, 2000);
            view_voiture_record();
          }
        },
      });
    });
  });
}

// Choice Model Assurance & Visite Technique

function update_visite_assurance() {
  $(document).on("click", "#btn-edit-papier", function () {
    var voiture_id = $(this).attr("papier-id");
    var modal_numb = $(this).attr("modal-Numb");
    $("#btn-edit-assurance").removeAttr('disabled');
    $("#btn-edit-visit").removeAttr('disabled');
    $("#message_visite").removeClass("alert alert-danger").html("");  
    $("#message_assurance").removeClass("alert alert-danger").html("");  

    $("#AssuranceVisite").find('form').eq(1).trigger('reset');
    $("#AssuranceVisite").find('form').eq(0).trigger('reset');

    $('#visite_form').attr('hidden',true);
    $('#assurance_form').attr('hidden',true);
    if(modal_numb==2){
      $("#btn-edit-assurance").prop('disabled', true);
      $("#btn-edit-visit").prop('disabled', true).css("opacity","1");
      $("#btn-edit-visit").css("border","transparent");
      $("#btn-edit-visit:disabled").css('background', "#BF1616");
      $("#btn-edit-assurance").css("color","#FFAEAE");
      $("#btn-edit-assurance").css("background","#FCEFEF");
      $("#btn-edit-assurance").css("border","transparent");
      $("#btn-edit-visit").css("color","#FFFFFF");
      $("#btn-edit-visit").css("background","#BF1616");
      $("#btn-edit-visite").attr('visit-id', voiture_id);
      $('#assurance_form').attr('hidden',true);
      $('#visite_form').removeAttr("hidden");    
    $("#AssuranceVisite").modal("show");
    }
    else if(modal_numb==1){
      $("#btn-edit-visit").prop('disabled', true);
      $("#btn-edit-assurance").prop('disabled', true).css("opacity","1");;
      $("#btn-edit-visit").css("color","#FFAEAE");
      $("#btn-edit-visit").css("border","transparent");
      $("#btn-edit-visit").css("background","#FCEFEF");
      $("#btn-edit-assurance").css("color","#FFFFFF");
      $("#btn-edit-assurance").css("background","#BF1616");
      $("#btn-edit-assurance").css("border","transparent");
      $("#btn-edit-assur").attr('assur-id', voiture_id);
      $('#visite_form').attr('hidden',true);
      $('#assurance_form').removeAttr("hidden");  
    $("#AssuranceVisite").modal("show");
    }
    else{
      $("#btn-edit-visite").attr('visit-id', voiture_id);
      $("#btn-edit-assur").attr('assur-id', voiture_id);
      $('#visite_form').attr('hidden',true);
      $("#btn-edit-visit").css("color","#6C6C6C");
      $("#btn-edit-visit").css("background","#F1F1F1");  
      $('#assurance_form').removeAttr("hidden");  
      $("#btn-edit-assurance").css("color","#FFFFFF");
      $("#btn-edit-assurance").css("background","#BF1616");
      $("#AssuranceVisite").modal("show");
    }

  });
}

// Choice Assurance

function update_voiture_assurance() {
  $(document).on("click", "#btn-edit-assurance", function () {
    $("#AssuranceVisite").find('form').eq(0).trigger('reset');
    $("#message_assurance")
    .removeClass("alert alert-danger")
    .html("");  
    $("#btn-edit-visit").css("color","#6C6C6C");
    $("#btn-edit-visit").css("background","#F1F1F1");
    $("#btn-edit-assurance").css("color","#FFFFFF");
    $("#btn-edit-assurance").css("background","#BF1616");
    $('#visite_form').attr('hidden',true);
    $('#assurance_form').removeAttr("hidden");
  });
  
  $(document).on("click", "#btn-edit-assur", function () {
    $(this).find('form').trigger('reset');
    $("#edit-assurance").scrollTop(0);
    var assurance_voiture_id = $(this).attr("assur-id");
    var up_DateFinAssurance = $("#date-fin-assurance").val();
    var up_prixAssurance = $("#prix-assurance").val();
    var up_assurancephoto = $("#edit-photo-assurance").prop("files")[0];
    datemonthplus1 = new Date();
    datemonthplus1.setMonth(datemonthplus1.getMonth()+1); 
    var mydate = new Date(up_DateFinAssurance);
        if (up_DateFinAssurance == "" || up_prixAssurance == "" || up_assurancephoto == null) {
      $("#message_assurance")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    }
    else if(mydate < datemonthplus1){
      $("#message_assurance")
      .addClass("alert alert-danger")
      .html("Veuillez verifier la date fin assurance !");
    }
    else { 
      var form_data = new FormData();
      form_data.append("assurance_voiture_id", assurance_voiture_id);
      form_data.append("up_DateFinAssurance", up_DateFinAssurance);
      form_data.append("up_prixAssurance", up_prixAssurance);
      form_data.append("up_assurancephoto", up_assurancephoto);
      $.ajax({
        url: "update_assurance_voiture.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#AssuranceVisite").modal("hide");
            $("#upassurance_echec").html(data);
          $("#EchecUpAssurance").modal("show");
          setTimeout(function () {
              $("#EchecUpAssurance").modal("hide");
          }, 2000);
          } else {
            $("#AssuranceVisite").modal("hide");
              $("#upassurance_success").html(data);
            $("#SuccessUpAssurance").modal("show");
            setTimeout(function () {
                $("#SuccessUpAssurance").modal("hide");
              }, 2000);
              view_voiture_record();
              notification_controle_assurance();
          }
        },
      });
    }
  });
}

// Choice Viste Technique

function update_voiture_visite() {
$(document).on("click", "#btn-edit-visit", function () {
  $("#message_visite").removeClass("alert alert-danger").html("");  
  $("#AssuranceVisite").find('form').eq(1).trigger('reset');
  $("#btn-edit-assurance").css("color","#6C6C6C");
  $("#btn-edit-assurance").css("background","#F1F1F1");
  $("#btn-edit-visit").css("color","#FFFFFF");
  $("#btn-edit-visit").css("background","#BF1616");
$('#assurance_form').attr('hidden',true);
  $('#visite_form').removeAttr("hidden");
});
  $(document).on("click", "#btn-edit-visite", function () {
    $("#edit-visite").scrollTop(0);
    var visite_voiture_id = $(this).attr("visit-id");
    var up_DateFinVisite = $("#date-fin-visite").val();
    var up_prixVisite = $("#prix-visite").val();
    var up_visitephoto = $("#edit-photo-visite").prop("files")[0];
    datemonthplus1 = new Date();
    datemonthplus1.setMonth(datemonthplus1.getMonth()+1); 
    var mydate = new Date(up_DateFinVisite);
    if (up_DateFinVisite == "" || up_prixVisite == "" || up_visitephoto == null) {
      $("#message_visite")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    }
    else if(mydate < datemonthplus1){
      $("#message_visite")
      .addClass("alert alert-danger")
      .html("Veuillez verifier la date fin visite technique !");
    }
    else{
      var form_data = new FormData();
      form_data.append("visite_voiture_id", visite_voiture_id);
      form_data.append("up_DateFinVisite", up_DateFinVisite);
      form_data.append("up_prixVisite", up_prixVisite);
      form_data.append("up_visitephoto", up_visitephoto);
      $.ajax({
        url: "update_visite_voiture.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#AssuranceVisite").modal("hide");
            $("#upavisite_echec").html(data);
            $("#EchecUpVisit").modal("show");
          setTimeout(function () {
            $("#EchecUpVisit").modal("hide");
          }, 2000);
          } else {
             $("#AssuranceVisite").modal("hide");
              $("#upvisite_success").html(data);
            $("#SuccessUpVisite").modal("show");
            setTimeout(function () {
              $("#SuccessUpVisite").modal("hide");
              }, 2000); }
              view_voiture_record();
              notification_controle_visite();
        },
      });
    }

  });
}

// Papier voiture

function view_papier_voiture_record(){
  $("#papier-voiture").html("");
  $.ajax({
    url: "viewpapiervoiture.php",
    method: "post",
    cache: false,
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#papier-voiture").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
   

  function searchPapierVoiture(){
    $("#searchPapier").keyup(function () {
      var search = $(this).val();
      $.ajax({
        url: "searchPapier.php",
        method: "post",
        data: {
          query: search
        },
        success: function (response) {
          $("#papier-voiture").html(response);
        },
      });
    });

  }

  function update_voiture_papier(){
    $(document).on("click", "#btn-edit-papiervoiture", function () {
      $("#up_message")
      .removeClass("alert alert-danger")
      .html("");
      $("#updatePapiers").find('form').eq(0).trigger('reset');
      var papier_voiture_id = $(this).attr("data-id");
      document.getElementById('up_IdPapier').setAttribute('value', papier_voiture_id);
      $("#updatePapiers").modal("show");
      $("#updatePapiers").scrollTop(0);
    });   
    $(document).on("click", "#btn_update_papier", function () {
      var up_voitureid = $("#up_IdPapier").val();
      var up_voitureassurance =   $("#up_voitureassurance").prop("files")[0];
      var up_voiturevignette =   $("#up_voiturevignette").prop("files")[0];
      var up_voiturevisite =  $("#up_voiturevisite").prop("files")[0];
      var form_data = new FormData();
      form_data.append("up_voitureid", up_voitureid);
      form_data.append("up_voitureassurance", up_voitureassurance);
      form_data.append("up_voiturevignette", up_voiturevignette);
      form_data.append("up_voiturevisite", up_voiturevisite);
      $.ajax({
        url: "update_papier_voiture.php",
        method: "POST",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updatePapiers").modal("hide");
            $("#uppapiervoiture_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecUpPapierVoiture").modal("show");
            setTimeout(function () {
              if ($("#EchecUpPapierVoiture").length > 0) {
                $("#EchecUpPapierVoiture").modal("hide");
              }
            },2000);
          } else {
            $("#updatePapiers").modal("hide");
            $("#uppapiervoiture_success").addClass("text-checked").html(data);
            $("#SuccessUpPapierVoiture").modal("show");
            $("#uppapiervoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpPapierVoiture").length > 0) {
                var imgAssur = "assur-"+up_voitureid; 
                var imgVig = "vig-"+up_voitureid; 
                var imgVis = "vis-"+up_voitureid; 
                refreshImage(imgAssur);
                refreshImage(imgVig);
                refreshImage(imgVis);                 
                $("#SuccessUpPapierVoiture").modal("hide"); 
              }
            },3000);  
          }
        },
      });
    });
  }

  function view_papier_archivage_voiture_record() {
    $("#PimmPapier").change(function (){
      var idVoiture= $("#PimmPapier").val();
      $.ajax({
        url: "PapierYearArchivage.php",
        method: "post",
        data: {
          idVoiture: idVoiture
        },
        success: function (response) {
          if(response.status=="success"){

          }
          else{
            alert(response.status);
          }
        },
      });
      });
    }

// Marque Voiture 

function view_marquevoiture_record() {
  $.ajax({
    url: "viewmarquevoiture.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#marquevoiture-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchMarqueVoiture() {
  $("#searchMarqueVoiture").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchMarqueVoiture.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#marquevoiture-list").html(response);
      },
    });
  });
}

function insert_marquevoiture_Record() {
  $(document).on("click", "#show_form_marquevoiture", function () {
    $("#message_marque").removeClass("alert alert-danger").html("");
    $("#Registration-MarqueVoiture").modal("show");
  });
  $(document).on("click", "#btn-register-marquevoiture", function () {
    $("#Registration-MarqueVoiture").scrollTop(0);
    var voituremarque = $("#voituremarque").val();
    var voituremodel = $("#voituremodel").val();
    var prixjan = $("#prixjan").val();
    var prixfev = $("#prixfev").val();
    var prixmars = $("#prixmars").val();
    var prixavril = $("#prixavril").val();
    var prixmai = $("#prixmai").val();
    var prixjuin = $("#prixjuin").val();
    var prixjuil = $("#prixjuil").val();
    var prixaout = $("#prixaout").val();
    var prixsept = $("#prixsept").val();
    var prixoct = $("#prixoct").val();
    var prixnov = $("#prixnov").val();
    var prixdec = $("#prixdec").val();

    if (voituremarque == "" || voituremodel == "" || prixjan == "" || prixfev == "" || prixmars == "" || prixavril == "" || prixmai == "" || prixjuin == "" || prixjuil == "" || prixaout == ""
    || prixsept == "" || prixoct == "" || prixnov == "" || prixdec == "") {
      $("#message_marque").addClass("alert alert-danger").html("Veuillez remplir tous les champs obligatoires !");
    } else {
      var form_data = new FormData();
      form_data.append("voituremarque", voituremarque);
      form_data.append("voituremodel", voituremodel);
      form_data.append("prixjan", prixjan);
      form_data.append("prixfev", prixfev);
      form_data.append("prixmars", prixmars);
      form_data.append("prixavril", prixavril);
      form_data.append("prixmai", prixmai);
      form_data.append("prixjuin", prixjuin);
      form_data.append("prixjuil", prixjuil);
      form_data.append("prixaout", prixaout);
      form_data.append("prixsept", prixsept);
      form_data.append("prixoct", prixoct);
      form_data.append("prixnov", prixnov);
      form_data.append("prixdec", prixdec);
      $.ajax({
        url: "AjoutMarqueVoiture.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#Registration-MarqueVoiture").modal("hide");
            $("#addmarquevoiture_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecAddMarqueVoiture").modal("show");
            setTimeout(function () {
              if ($("#EchecAddMarqueVoiture").length > 0) {
                $("#EchecAddMarqueVoiture").modal("hide");
              }
            }, 2000);
            setTimeout(function () {
              if ($("#EchecAddMarqueVoiture").length > 0) {
                location.reload();
              }
            }, 2000);
          } else {
            $("#Registration-MarqueVoiture").modal("hide");
            $("#addmarquevoiture_success").addClass("text-checked").html(data);
            $("#SuccessAddMarqueVoiture").modal("show");
            $("#addmarquevoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddMarqueVoiture").length > 0) {
                $("#SuccessAddMarqueVoiture").modal("hide");
              }
            }, 2000);
            setTimeout(function () {
              if ($("#SuccessAddMarqueVoiture").length > 0) {
                location.reload();
              }
            }, 2000);
          }
        },
      });
    }
  });
  $('#Registration-MarqueVoiture').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
}

function get_prix_marquevoiture_record() {
  $(document).on("click", "#btn-vue-prixmarque", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_prix_marquevoiture_data.php",
      method: "post",
      data: {
        id_marquevoiture: ID
      },
      dataType: "JSON",
      success: function (data) {
        $("#up_prix_marquevoitureid").val(data[0]);
        $("#up_prixjan").val(data[1]);
        $("#up_prixfev").val(data[2]);
        $("#up_prixmars").val(data[3]);
        $("#up_prixavr").val(data[4]);
        $("#up_prixmai").val(data[5]);
        $("#up_prixjuin").val(data[6]);
        $("#up_prixjuil").val(data[7]);
        $("#up_prixaout").val(data[8]);
        $("#up_prixsep").val(data[9]);
        $("#up_prixoct").val(data[10]);
        $("#up_prixnov").val(data[11]);
        $("#up_prixdec").val(data[12]);
        $("#updatePrixMarque").modal("show");
      },
    });
  });
}

function update_prix_marquevoiture_record() {
  $(document).on("click", "#btn_update_prix_marque", function () {
    $("#updatePrixMarque").scrollTop(0);
    var up_prix_marquevoitureid = $("#up_prix_marquevoitureid").val();
    var up_prixjan = $("#up_prixjan").val();
    var up_prixfev = $("#up_prixfev").val();
    var up_prixmars = $("#up_prixmars").val();
    var up_prixavr = $("#up_prixavr").val();
    var up_prixmai = $("#up_prixmai").val();
    var up_prixjuin = $("#up_prixjuin").val();
    var up_prixjuil = $("#up_prixjuil").val();
    var up_prixaout = $("#up_prixaout").val();
    var up_prixsep = $("#up_prixsep").val();
    var up_prixoct = $("#up_prixoct").val();
    var up_prixnov = $("#up_prixnov").val();
    var up_prixdec = $("#up_prixdec").val();

    if (up_prixjan == "" || up_prixfev == "" || up_prixmars == "" || up_prixavr == "" || up_prixmai == "" || up_prixjuin == ""
      || up_prixjuil == "" || up_prixaout == "" || up_prixsep == "" || up_prixoct == "" || up_prixnov == "" || up_prixdec == "") {
        alert("Veuillez remplir tous les champs obligatoires !");
    } else {
      var form_data = new FormData();
      form_data.append("up_prix_marquevoitureid", up_prix_marquevoitureid);
      form_data.append("up_prixjan", up_prixjan);
      form_data.append("up_prixfev", up_prixfev);
      form_data.append("up_prixmars", up_prixmars);
      form_data.append("up_prixavr", up_prixavr);
      form_data.append("up_prixmai", up_prixmai);
      form_data.append("up_prixjuin", up_prixjuin);
      form_data.append("up_prixjuil", up_prixjuil);
      form_data.append("up_prixaout", up_prixaout);
      form_data.append("up_prixsep", up_prixsep);
      form_data.append("up_prixoct", up_prixoct);
      form_data.append("up_prixnov", up_prixnov);
      form_data.append("up_prixdec", up_prixdec);
      $.ajax({
        url: "update_prix_marquevoiture.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updatePrixMarque").modal("hide");
            $("#EchecUpPrixMarqueVoiture").modal("show");
            $("#up_prix_marquevoiture_success").removeClass("text-checked").addClass("text-echec").html(data);
            setTimeout(function () {
              if ($("#EchecUpPrixMarqueVoiture").length > 0) {
                $("#EchecUpPrixMarqueVoiture").modal("hide");
              }
            }, 2000);
            view_marquevoiture_record();
          } else {
            $("#updatePrixMarque").modal("hide");
            $("#SuccessUpPrixMarqueVoiture").modal("show");
            $("#up_prix_marquevoiture_success").addClass("text-checked").html(data);
            $("#up_prix_marquevoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpPrixMarqueVoiture").length > 0) {
                $("#SuccessUpPrixMarqueVoiture").modal("hide");
              }
            }, 2000);
            view_marquevoiture_record();
          }
        },
      });
    }
  });
}

function get_marquevoiture_record() {
  $(document).on("click", "#btn-edit-marquevoiture", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_marquevoiture_data.php",
      method: "post",
      data: {
        id_marquevoiture: ID
      },
      dataType: "JSON",
      success: function (data) {
        $("#up_marquevoitureid").val(data[0]);
        $("#up_voituremarque").val(data[1]);
        $("#up_voituremodel").val(data[2]);
        $("#up_voitureprix").val(data[3]);
        $("#updateMarqueVoiture").modal("show");
      },
    });
  });
}

function update_marquevoiture_record() {
  $(document).on("click", "#btn_update_marquevoiture", function () {
    $("#up_message")
        .removeClass("alert alert-danger")
        .html("");
    $("#updateMarqueVoiture").scrollTop(0);
    var up_marquevoitureid = $("#up_marquevoitureid").val();
    var up_voituremarque = $("#up_voituremarque").val();
    var up_voituremodel = $("#up_voituremodel").val();

    if (up_voituremarque == "" || up_voituremodel == "") {
      $("#up_message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else {
      var form_data = new FormData();
      form_data.append("up_marquevoitureid", up_marquevoitureid);
      form_data.append("up_voituremarque", up_voituremarque);
      form_data.append("up_voituremodel", up_voituremodel);

      $.ajax({
        url: "update_marquevoiture.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updateMarqueVoiture").modal("hide");
            $("#upmarquevoiture_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecUpMarqueVoiture").modal("show");
            setTimeout(function () {
              if ($("#EchecUpMarqueVoiture").length > 0) {
                $("#EchecUpMarqueVoiture").modal("hide");
              }
            }, 2000);
            view_marquevoiture_record();
          } else {
            $("#updateMarqueVoiture").modal("hide");
            $("#upmarquevoiture_success").addClass("text-checked").html(data);
            $("#SuccessUpMarqueVoiture").modal("show");
            $("#upmarquevoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpMarqueVoiture").length > 0) {
                $("#SuccessUpMarqueVoiture").modal("hide");
              }
            }, 2000);
            view_marquevoiture_record();
          }
        },
      });
    }
  });
}

function delete_marquevoiture_record() {
  $(document).on("click", "#btn-delete-marquevoiture", function () {
    var Delete_ID = $(this).attr("data-id1");
    $("#deleteMarqueVoiture").modal("show");
    $(document).on("click", "#btn_delete", function () {
      $.ajax({
        url: "delete_marquevoiture.php",
        method: "post",
        data: {
          id_marquevoiture: Delete_ID
        },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#deleteMarqueVoiture").modal("hide");
            $("#deletemarquevoiture_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecDeleteMarqueVoiture").modal("show");
            setTimeout(function () {
              if ($("#EchecDeleteMarqueVoiture").length > 0) {
                $("#EchecDeleteMarqueVoiture").modal("hide");
              }
            }, 2000);
            view_marquevoiture_record();
          } else {
            $("#deleteMarqueVoiture").modal("hide");
            $("#deletemarquevoiture_success").addClass("text-checked").html(data);
            $("#SuccessDeleteMarqueVoiture").modal("show");
            $("#deletemarquevoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessDeleteMarqueVoiture").length > 0) {
                $("#SuccessDeleteMarqueVoiture").modal("hide");
              }
            }, 2000);
            view_marquevoiture_record();
          }
        },
      });
    });
  });
}

// Stock

function view_stockvoiture_record() {
  $.ajax({
    url: "viewstockvoiture.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#stockvoiture-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchStockVoiture() {
  $("#searchStockVoiture").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchStockVoiture.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#stockvoiture-list").html(response);
      },
    });
  });
}

function get_agence_voiture() {
  $(document).on("click", "#btn-transfert-voiture", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_voiture_data.php",
      method: "post",
      data: {
        id_voiture: ID
      },
      dataType: "JSON",
      success: function (data) {
        $("#up_idvoiture").val(data[0]);
        $("#up_voitureAgence").val(data[6]);
        $("#updateagencevoiture").modal("show");
      },
    });
  });
}

function transfert_voiture_agence_record() {
  $(document).on("click", "#btn_update_agencevoiture", function () {
    $("#up_message")
    .removeClass("alert alert-danger")
    .html("");
    $("#updateagencevoiture").scrollTop(0);
    var up_idvoiture = $("#up_idvoiture").val();
    var up_voitureAgence = $("#up_voitureAgence").val();

    if (up_voitureAgence == null) {
      $("#up_message").addClass("alert alert-danger").html("Veuillez choisir l'agence !");
    } else {
      $.ajax({
        url: "update_agencevoiture.php",
        method: "POST",
        data: {
          id_voiture: up_idvoiture,
          up_voitureAgence: up_voitureAgence,
        },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updateagencevoiture").modal("hide");
            $("#upagencevoiture_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecUpAgenceVoiture").modal("show");
            setTimeout(function () {
              if ($("#EchecUpAgenceVoiture").length > 0) {
                $("#EchecUpAgenceVoiture").modal("hide");
              }
            }, 2000);
            view_stockvoiture_record();
          } else {
            $("#updateagencevoiture").modal("hide");
            $("#upagencevoiture_success").addClass("text-checked").html(data);
            $("#SuccessUpAgenceVoiture").modal("show");
            $("#upagencevoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpAgenceVoiture").length > 0) {
                $("#SuccessUpAgenceVoiture").modal("hide");
              }
            }, 2000);
            view_stockvoiture_record();
          }
        },
      });
    }
  });
}

function export_stock_voiture() {
  $(document).on("click", "#export_stock", function () {
    window.open("export_stock_voiture.php");
  });
}

// Contrat 

function view_contrat_record() {
  $.ajax({
    url: "viewcontrat.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#contrat-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchContrat() {
  $("#searchContrat").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchContrat.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#contrat-list").html(response);
      },
    });
  });
}

function insert_contrat_Record() {
  $(document).on("click", "#show_form_contrat", function () {
    $("#message")
    .removeClass("alert alert-danger")
    .html("");
    $("#Registration-Contrat").modal("show");
  });
  $(document).on("click", "#btn-register-contrat", function () {
    $("#Registration-Contrat").scrollTop(0);
    var DateDebutContrat = $("#DateDebutContrat").val();
    var DateFinContrat = $("#DateFinContrat").val();
    var ClientContrat = $("#ClientContrat").val();
    var AgenceContrat = $("#AgenceContrat").val();
    var VoitureContrat = $("#list_voiture").val();

    if (DateDebutContrat == "" || DateFinContrat == "" || ClientContrat == null || VoitureContrat == null) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else if (DateDebutContrat > DateFinContrat) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Date Fin doit etre superieur à la Date Debut !");
    } else {
      var form_data = new FormData();
      form_data.append("DateDebutContrat", DateDebutContrat);
      form_data.append("DateFinContrat", DateFinContrat);
      form_data.append("ClientContrat", ClientContrat);
      form_data.append("AgenceContrat", AgenceContrat);
      form_data.append("VoitureContrat", VoitureContrat);
      $.ajax({
        url: "AjoutContrat.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#Registration-Contrat").modal("hide");
            $("#addcontrat_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecAddContrat").modal("show");
            setTimeout(function () {
              if ($("#EchecAddContrat").length > 0) {
                $("#EchecAddContrat").modal("hide");
              }
            }, 2000);
            view_contrat_record();
          } else {
            $("#Registration-Contrat").modal("hide");
            $("#addcontrat_success").addClass("text-checked").html(data);
            $("#SuccessAddContrat").modal("show");
            $("#addcontrat_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddContrat").length > 0) {
                $("#SuccessAddContrat").modal("hide");
              }
            }, 2000);
            view_contrat_record();
          }
        },
      });
    }
  });
  $('#Registration-Contrat').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
}

function delete_contrat_record() {
  $(document).on("click", "#btn-delete-contrat", function () {
    var Delete_ID = $(this).attr("data-id1");
    $("#deleteContrat").modal("show");
    $(document).on("click", "#btn_delete", function () {
      $.ajax({
        url: "delete_contrat.php",
        method: "post",
        data: {
          id_contrat: Delete_ID
        },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#deleteContrat").modal("hide");
            $("#deletecontrat_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecDeleteContrat").modal("show");
            setTimeout(function () {
              if ($("#EchecDeleteContrat").length > 0) {
                $("#EchecDeleteContrat").modal("hide");
              }
            }, 2000);
            view_contrat_record();
          } else {
            $("#deleteContrat").modal("hide");
            $("#deletecontrat_success").addClass("text-checked").html(data);
            $("#SuccessDeleteContrat").modal("show");
            $("#deletecontrat_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessDeleteContrat").length > 0) {
                $("#SuccessDeleteContrat").modal("hide");
              }
            }, 2000);
            view_contrat_record();
          }
        },
      });
    });
  });
}

function afficher_voiture_dispo() {
  var DateDebutContrat = $("#DateDebutContrat").val();
  var DateFinContrat = $("#DateFinContrat").val();
  var AgenceContrat = $("#AgenceContrat").val();
  $.ajax({
    type: "post",
    url: "listevoiture.php",
    data: {
      DateDebutContrat: DateDebutContrat,
      DateFinContrat: DateFinContrat,
      AgenceContrat: AgenceContrat,
    },
    success: function (data) {
      $("#listevoiture").html(data);
    },
  });
}

// Contrat Archive

function view_contrat_archive_record() {
  $.ajax({
    url: "viewcontratarchive.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#contrat-archive").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchContratArchive() {
  $("#searchContratArchive").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchContratArchive.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#contrat-archive").html(response);
      },
    });
  });
}
// Contrat Historique

function view_contrat_historique_record() {
  $.ajax({
    url: "viewcontrathistorique.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#contrat-historique").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchContratHistorique() {
  $("#searchContratHistorique").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchContratHistorique.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#contrat-historique").html(response);
      },
    });
  });
}

// Download Contrat

function get_contrat_pdf() {
  $(document).on("click", "#btn-show-contrat", function () {
    var id_contrat = $(this).attr("data-id");
    window.open("fpdf/contratlocation.php?N=" + id_contrat, '_blank');
  });
}

// Entretien 

function view_entretien_record() {
  $.ajax({
    url: "viewentretien.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#entretien-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchEntretien() {
  $("#searchEntretien").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchEntretien.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#entretien-list").html(response);
      },
    });
  });
}

function afficher_voiture_agence() {
  var entretienagence = $("#entretienagence").val();
  $.ajax({
    type: "post",
    url: "listevoiture_agence.php",
    data: {
      entretienagence: entretienagence,
    },
    success: function (data) {
      $("#listevoiture_agence").html(data);
    },
  });
}

function insert_entretien_Record() {
  $(document).on("click", "#show_form_entretien", function () {
    $("#message")
    .removeClass("alert alert-danger")
    .html("");
    $("#Registration-Entretien").modal("show");
  });
  $(document).on("click", "#btn-register-entretien", function () {
    $("#Registration-Entretien").scrollTop(0);
    var DateDebutEntretien = $("#DateDebutEntretien").val();
    var DateFinEntretien = $("#DateFinEntretien").val();
    var prixentretien = $("#prixentretien").val();
    var commentaire = $("#Commentaire_ajout").val();
    var voiture_entretien = $("#voiture_entretien").val();
        // Checkbox
        let checkboxes = document.querySelectorAll('input[name="blockage"]:checked');
        let blockageVoiture = [];
        checkboxes.forEach((checkbox) => {
          blockageVoiture.push(checkbox.value);
        });
    if (DateDebutEntretien == "" || DateFinEntretien == "" || prixentretien == "" || voiture_entretien == undefined || commentaire ==""  ) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else if (DateDebutEntretien > DateFinEntretien) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Date Fin doit etre superieur à la Date Debut !");
    } else {
      var form_data = new FormData();
      form_data.append("DateDebutEntretien", DateDebutEntretien);
      form_data.append("DateFinEntretien", DateFinEntretien);
      form_data.append("prixentretien", prixentretien);
      form_data.append("voiture_entretien", voiture_entretien);
      form_data.append("blockageVoiture", blockageVoiture);
      form_data.append("commentaire", commentaire);

      $.ajax({
        url: "AjoutEntretien.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#Registration-Entretien").modal("hide");
            $("#addentretien_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecAddEntretien").modal("show");
            setTimeout(function () {
              if ($("#EchecAddEntretien").length > 0) {
                $("#EchecAddEntretien").modal("hide");
              }
            }, 2000);
            view_entretien_record();
          } else {
            $("#Registration-Entretien").modal("hide");
            $("#addentretien_success").addClass("text-checked").html(data);
            $("#SuccessAddEntretien").modal("show");
            $("#addentretien_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddEntretien").length > 0) {
                $("#SuccessAddEntretien").modal("hide");
              }
            }, 2000);
            view_entretien_record();
          }
        },
      });
    }
  });
  $('#Registration-Entretien').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
}

function get_entretien_record() {
  $(document).on("click", "#btn-edit-entretien", function () {
    var ID = $(this).attr("data-id");
    $.ajax({
      url: "get_entretien_data.php",
      method: "post",
      data: {
        id_entretien: ID
      },
      dataType: "JSON",
      success: function (data) {
        $("#up_entretienid").val(data[0]);
        $("#up_DateDebutEntretien").val(data[1]);
        $("#up_DateFinEntretien").val(data[2]);
      $("input[name='upblockage']:checked").value=data[3];        
        $("#commentaire_modifie").val(data[4]);
        console.log(data[4]);
        $("#up_prixentretien").val(data[5]);
        $("#updateEntretien").modal("show");
        if(data[3]==0){
          $("#UpblockageNON").prop('checked', true);
        
        }
        else{
          $("#UpblockageOUI").prop('checked', true);

        }
      },
    });
  });
}

function update_entretien_record() {
  $(document).on("click", "#btn_update_entretien", function () {
    $("#up_message")
    .removeClass("alert alert-danger")
    .html("");
    $("#updateEntretien").scrollTop(0);
    var up_entretienid = $("#up_entretienid").val();
    var up_DateDebutEntretien = $("#up_DateDebutEntretien").val();
    var up_DateFinEntretien = $("#up_DateFinEntretien").val();
    var up_prixentretien = $("#up_prixentretien").val();
    var up_commentaire = $("#commentaire_modifie").val();
    console.log(up_commentaire);
        // Checkbox
        let checkboxes = document.querySelectorAll('input[name="upblockage"]:checked');
        let upblockageVoiture = [];
        checkboxes.forEach((checkbox) => {
          upblockageVoiture.push(checkbox.value);
        });

        if (up_DateDebutEntretien == "" || up_DateFinEntretien == "" || up_prixentretien == "" || up_commentaire ==""  ) {
          $("#up_message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else if (up_prixentretien < 0) {
      $("#up_message")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier le prix de l'entretien !");
    } else {
      var form_data = new FormData();
      form_data.append("up_entretienid", up_entretienid);
      form_data.append("up_DateDebutEntretien", up_DateDebutEntretien);
      form_data.append("up_DateFinEntretien", up_DateFinEntretien);
      form_data.append("up_prixentretien", up_prixentretien);
      form_data.append("up_commentaire", up_commentaire);
      form_data.append("upblockageVoiture",upblockageVoiture);
      $.ajax({
        url: "update_entretien.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#updateEntretien").modal("hide");
            $("#upentretien_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecUpEntretien").modal("show");
            setTimeout(function () {
              if ($("#EchecUpEntretien").length > 0) {
                $("#EchecUpEntretien").modal("hide");
              }
            }, 2000);
            view_entretien_record();
          } else {
            $("#updateEntretien").modal("hide");
            $("#upentretien_success").addClass("text-checked").html(data);
            $("#SuccessUpEntretien").modal("show");
            $("#upentretien_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpEntretien").length > 0) {
                $("#SuccessUpEntretien").modal("hide");
              }
            }, 2000);
            view_entretien_record();
          }
        },
      });
    }
  });
}

function delete_entretien_record() {
  $(document).on("click", "#btn-delete-entretien", function () {
    var Delete_ID = $(this).attr("data-id1");
    $("#deleteEntretien").modal("show");
    $(document).on("click", "#btn_delete", function () {
      $.ajax({
        url: "delete_entretien.php",
        method: "post",
        data: {
          id_entretien: Delete_ID
        },
        success: function (data) {
          if (data.includes('text-echec')) {
            $("#deleteEntretien").modal("hide");
            $("#deleteentretien_echec").removeClass("text-checked").addClass("text-echec").html(data);
            $("#EchecDeleteEntretien").modal("show");
            setTimeout(function () {
              if ($("#EchecDeleteEntretien").length > 0) {
                $("#EchecDeleteEntretien").modal("hide");
              }
            }, 2000);
            view_entretien_record();
          } else {
            $("#deleteEntretien").modal("hide");
            $("#deleteentretien_success").addClass("text-checked").html(data);
            $("#SuccessDeleteEntretien").modal("show");
            $("#deleteentretien_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessDeleteEntretien").length > 0) {
                $("#SuccessDeleteEntretien").modal("hide");
              }
            }, 2000);
            view_entretien_record();
          }
        },
      });
    });
  });
}

// Entretien Archive

function view_entretien_archive_record() {
  $.ajax({
    url: "viewentretienarchive.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#entretien-archive").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchEntretienArchive() {
  $("#searchEntretienArchive").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchEntretienArchive.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#entretien-archive").html(response);
      },
    });
  });
}

// Historique Entretien

function view_entretien_historique_record() {
  $.ajax({
    url: "viewentretienhistorique.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#entretien-historique").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}

function searchEntretienHistorique() {
  $("#searchEntretienHistorique").keyup(function () {
    var search = $(this).val();
    $.ajax({
      url: "searchEntretienHistorique.php",
      method: "post",
      data: {
        query: search
      },
      success: function (response) {
        $("#entretien-historique").html(response);
      },
    });
  });
}

// Planing

function view_planing_contrat_record() {
  $.ajax({
    type: "GET",
    url: "viewPlaningContratRecord.php",
    success: function (data) {
      var $calendarEl = $('#calendar');
      var calendar = new FullCalendar.Calendar($calendarEl[0], {
        headerToolbar: {
          left: 'title',
          right: 'listDay,listWeek,dayGridMonth,multiMonthYear prev,today,next'
        },
        navLinks: true,
        selectMirror: true,
        locale: "fr",
        editable: true,
        height: "auto",
        fixedWeekCount: false,
        buttonText: {
          listDay: 'Jour',
          listWeek: 'Semaine',
          today: 'Aujourd\'hui',
          dayGridMonth: 'Mois',
          prev: 'Précedent',
          next: 'Suivant',
          multiMonthYear: 'Année'
        },
        events: JSON.parse(data),
        eventColor: '#FEE2E9',
        eventTextColor: '#171717',
      });
      
      $(document).on("click", "#ConsulterContrat", function () {
        var parent = $(this).next();
         var datecontrat = parent.attr("title");
        var datecontrat = datecontrat.split(" ");
        date_contrat_title = "Contrat " + datecontrat[2] + " " + datecontrat[3] + " " + datecontrat[4];
        switch (datecontrat[3]) {
          case "janvier":
            datecontrat[3] = '01';
            break;
          case "février":
            datecontrat[3] = '02';
            break;
          case "mars":
            datecontrat[3] = '03';
            break;
          case "avril":
            datecontrat[3] = '04';
            break;
          case "mai":
            datecontrat[3] = '05';
            break;
          case "juin":
            datecontrat[3] = '06';
            break;
          case "juillet":
            datecontrat[3] = '07';
            break;
          case "août":
            datecontrat[3] = '08';
            break;
          case "septembre":
            datecontrat[3] = '09';
            break;
          case "octobre":
            datecontrat[3] = '10';
            break;
          case "novembre":
            datecontrat[3] = '11';
            break;
          case "décembre":
            datecontrat[3] = '12';
        }
        if (datecontrat[2] < 10) {
          var date_contrat = datecontrat[4].concat("-", datecontrat[3]).concat("-0", datecontrat[2]);
        }
        else {
          var date_contrat = datecontrat[4].concat("-", datecontrat[3]).concat("-", datecontrat[2]);
        }
        $.ajax({
          url: "viewPlanningContratByDay.php",
          method: "post",
          data: {
            date: date_contrat
          },
          success: function (data) {
            $("#PlanningContratDayListe").html(data);
            $("#ModalTitleContratByDay").html(date_contrat_title);
            $('#ContratListePopup').modal('toggle');
          },
        });
      });
      calendar.render();
    }
  });
}

