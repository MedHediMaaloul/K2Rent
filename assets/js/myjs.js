$(document).ready(function () {
  ReloadButtonExit();
  ReloadButtonExitX();
  notification_fin_contrat();
  remove_notification_fin_contrat();
  notification_create_contrat();
  remove_notification_create_contrat();
  view_notification_record();
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
  // Marque Voiture
  view_marquevoiture_record();
  searchMarqueVoiture();
  insert_marquevoiture_Record();
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
  // login
  login();
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
  $('#voitureMarqueModel').select2({
    dropdownParent: $('#voitureMarqueModel').parent()
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

// Notification

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
  $(document).on("click", "#toggle-contrat", function () {
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
  $(document).on("click", "#toggle-contrat", function () {
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
    $("#Registration-Agence-Heur").modal("show");
  });
  $(document).on("click", "#btn-register-agence-heur", function () {
    $("#Registration-Agence-Heur").scrollTop(0);
    var IdAgence = $("#IdAgence").val();
    var jourH = $("#jourH").val();
    var heurdebutH = $("#fetch-heurdebutH").val();
    var heurfinH = $("#fetch-heurfinH").val();
    if (IdAgence == null || jourH == null || heurdebutH == "" || heurfinH == "") {
      $("#message").addClass("alert alert-danger").html("Veuillez remplir tous les champs obligatoires !");
    } else {
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
    $(document).on("click", "#btn-delete-agence-heur", function () {
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
    $("#Registration-Client").modal("show");
  });
  $(document).on("click", "#btn-register-client", function () {
    $("#Registration-Client").scrollTop(0);
    var ClientName = $("#clientName").val();
    var ClientEmail = $("#clientEmail").val();
    var ClientPhone = $("#clientPhone").val();
    var ClientAdresse = $("#clientAdresse").val();
    var ClientCIN = $("#clientCIN").prop("files")[0];
    var ClientPermis = $("#clientPermis").prop("files")[0];

    if (ClientName == "" || ClientEmail == "" || ClientPhone == "" || ClientAdresse == "" || ClientCIN == null || ClientPermis == null) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else if (!isValidEmailAddress(ClientEmail)) {
      $("#message")
        .addClass("alert alert-danger")
        .html("le champ « email » est invalide");
    } else {
      var form_data = new FormData();
      form_data.append("ClientName", ClientName);
      form_data.append("ClientEmail", ClientEmail);
      form_data.append("ClientPhone", ClientPhone);
      form_data.append("ClientAdresse", ClientAdresse);
      form_data.append("ClientCIN", ClientCIN);
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
            }, 5000);
            view_client_record();
          } else {
            $("#Registration-Client").modal("hide");
            $("#addclient_success").addClass("text-checked").html(data);
            $("#SuccessAddClient").modal("show");
            $("#addclient_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddClient").length > 0) {
                $("#SuccessAddClient").modal("hide");
              }
            }, 5000);
            view_client_record();
          }
        },
      });
    }
  });
  $('#Registration-Client').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
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
        $("#up_clientName").val(data[1]);
        $("#up_clientEmail").val(data[2]);
        $("#up_clientPhone").val(data[3]);
        $("#up_clientAdresse").val(data[4]);
        $("#up_clientCIN").val();
        $("#up_clientPermis").val();
        $("#updateClient").modal("show");
      },
    });
  });
}

function update_client_record() {
  $(document).on("click", "#btn_update", function () {
    $("#updateClient").scrollTop(0);
    var up_idclient = $("#up_idclient").val();
    var up_clientName = $("#up_clientName").val();
    var up_clientEmail = $("#up_clientEmail").val();
    var up_clientPhone = $("#up_clientPhone").val();
    var up_clientAdresse = $("#up_clientAdresse").val();
    var up_clientCIN = $("#up_clientCIN").prop("files")[0];
    var up_clientPermis = $("#up_clientPermis").prop("files")[0];

    if (up_clientName == "" || up_clientEmail == "" || up_clientPhone == "" || up_clientAdresse == "") {
      $("#up_message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else if (!isValidEmailAddress(up_clientEmail)) {
      $("#up_message")
        .addClass("alert alert-danger")
        .html("le champ « email » est invalide");
    } else {
      var form_data = new FormData();
      form_data.append("up_idclient", up_idclient);
      form_data.append("up_clientName", up_clientName);
      form_data.append("up_clientEmail", up_clientEmail);
      form_data.append("up_clientPhone", up_clientPhone);
      form_data.append("up_clientAdresse", up_clientAdresse);
      form_data.append("up_clientCIN", up_clientCIN);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
    $("#Registration-Voiture").modal("show");
  });
  $(document).on("click", "#btn-register-voiture", function () {
    $("#Registration-Voiture").scrollTop(0);
    var voiturepimm1 = $("#voiturepimm1").val();
    var voiturepimm2 = $("#voiturepimm2").val();
    var voitureMarqueModel = $("#voitureMarqueModel").val();
    var voituretypecarburant = $("#voituretypecarburant").val();
    var voitureboitevitesse = $("#voitureboitevitesse").val();
    var voiturenbreplace = $("#voiturenbreplace").val();
    var voiturenbrevalise = $("#voiturenbrevalise").val();
    var voiturepuissance = $("#voiturepuissance").val();
    var voiturecartegrise = $("#voiturecartegrise").prop("files")[0];
    var voitureassurance = $("#voitureassurance").prop("files")[0];
    var voitureagence = $("#voitureagence").val();
    // Checkbox
    let checkboxes = document.querySelectorAll('input[name="voitureclim"]:checked');
    let voitureclimatisation = [];
    checkboxes.forEach((checkbox) => {
      voitureclimatisation.push(checkbox.value);
    });

    if (voiturepimm1 == "" || voiturepimm2 == "" || voiturenbreplace == "" || voiturepuissance == "" || voitureagence == "" ||
      voitureMarqueModel == null || voituretypecarburant == null || voitureboitevitesse == null || voiturenbrevalise == null || voiturecartegrise == null || voitureassurance == null) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else if (voiturepimm1 > 999 || voiturepimm1 < 10 || voiturepimm2 > 9999 || voiturepimm2 < 0) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier l'immatriculation du voiture !");
    } else if (voiturepuissance > 20 || voiturepuissance < 1 ) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier la puissance du voiture !");
    } else if (voiturenbreplace > 10 || voiturenbreplace < 1 ) {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez vérifier le nombre de place du voiture !");
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
              if ($("#EchecAddVoiture").length > 0) {
                $("#EchecAddVoiture").modal("hide");
              }
            }, 5000);
            view_voiture_record();
            view_stockvoiture_record();
          } else {
            $("#Registration-Voiture").modal("hide");
            $("#addvoiture_success").addClass("text-checked").html(data);
            $("#SuccessAddVoiture").modal("show");
            $("#addvoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddVoiture").length > 0) {
                $("#SuccessAddVoiture").modal("hide");
              }
            }, 5000);
            view_voiture_record();
            view_stockvoiture_record();
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
        $("#up_voitureassurance").val();
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
    var up_voitureassurance = $("#up_voitureassurance").prop("files")[0];
    // Checkbox
    let checkboxes = document.querySelectorAll('input[name="up_voitureclim"]:checked');
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
      form_data.append("up_voitureassurance", up_voitureassurance);

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
            }, 5000);
            view_voiture_record();
          } else {
            $("#updateVoiture").modal("hide");
            $("#upvoiture_success").addClass("text-checked").html(data);
            $("#SuccessUpVoiture").modal("show");
            $("#upvoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessUpVoiture").length > 0) {
                $("#SuccessUpVoiture").modal("hide");
              }
            }, 5000);
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
            }, 5000);
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
            }, 5000);
            view_voiture_record();
          }
        },
      });
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
    $("#Registration-MarqueVoiture").modal("show");
  });
  $(document).on("click", "#btn-register-marquevoiture", function () {
    $("#Registration-MarqueVoiture").scrollTop(0);
    var voituremarque = $("#voituremarque").val();
    var voituremodel = $("#voituremodel").val();
    var voitureprix = $("#voitureprix").val();

    if (voituremarque == "" || voituremodel == "" || voitureprix == "") {
      $("#message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else {
      var form_data = new FormData();
      form_data.append("voituremarque", voituremarque);
      form_data.append("voituremodel", voituremodel);
      form_data.append("voitureprix", voitureprix);
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
            }, 5000);
            view_marquevoiture_record();
          } else {
            $("#Registration-MarqueVoiture").modal("hide");
            $("#addmarquevoiture_success").addClass("text-checked").html(data);
            $("#SuccessAddMarqueVoiture").modal("show");
            $("#addmarquevoiture_success").removeClass("text-echec").addClass("text-checked");
            setTimeout(function () {
              if ($("#SuccessAddMarqueVoiture").length > 0) {
                $("#SuccessAddMarqueVoiture").modal("hide");
              }
            }, 5000);
            view_marquevoiture_record();
          }
        },
      });
    }
  });
  $('#Registration-MarqueVoiture').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
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
    $("#updateMarqueVoiture").scrollTop(0);
    var up_marquevoitureid = $("#up_marquevoitureid").val();
    var up_voituremarque = $("#up_voituremarque").val();
    var up_voituremodel = $("#up_voituremodel").val();
    var up_voitureprix = $("#up_voitureprix").val();

    if (up_voituremarque == "" || up_voituremodel == "" || up_voitureprix == "") {
      $("#up_message")
        .addClass("alert alert-danger")
        .html("Veuillez remplir tous les champs obligatoires !");
    } else {
      var form_data = new FormData();
      form_data.append("up_marquevoitureid", up_marquevoitureid);
      form_data.append("up_voituremarque", up_voituremarque);
      form_data.append("up_voituremodel", up_voituremodel);
      form_data.append("up_voitureprix", up_voitureprix);

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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
    $("#Registration-Entretien").modal("show");
  });
  $(document).on("click", "#btn-register-entretien", function () {
    $("#Registration-Entretien").scrollTop(0);
    var DateDebutEntretien = $("#DateDebutEntretien").val();
    var DateFinEntretien = $("#DateFinEntretien").val();
    var prixentretien = $("#prixentretien").val();
    var voiture_entretien = $("#voiture_entretien").val();

    if (DateDebutEntretien == "" || DateFinEntretien == "" || prixentretien == "" || voiture_entretien == undefined) {
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
            }, 5000);
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
            }, 5000);
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
        $("#up_prixentretien").val(data[3]);
        $("#updateEntretien").modal("show");
      },
    });
  });
}

function update_entretien_record() {
  $(document).on("click", "#btn_update_entretien", function () {
    $("#updateEntretien").scrollTop(0);
    var up_entretienid = $("#up_entretienid").val();
    var up_DateDebutEntretien = $("#up_DateDebutEntretien").val();
    var up_DateFinEntretien = $("#up_DateFinEntretien").val();
    var up_prixentretien = $("#up_prixentretien").val();

    if (up_DateDebutEntretien == "" || up_DateFinEntretien == "" || up_prixentretien == "") {
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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
            }, 5000);
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

function login(){
  $('#SubmitLogin').click(function()
  {
  var username=$("#login").val();
  var password=$("#password").val();
  var dataString = 'login='+username+'&password='+password;
  $("#obligatoireLogin").empty();
  $("#obligatoirePassword").empty();
  if (username == "" && password == "" ) {
      $("#obligatoireLogin").html("Login est obligatoire!");
      $("#obligatoirePassword").html("Password est obligatoire!");
}else if (username == "") {
  $("#obligatoireLogin").html("Login est obligatoire!");
}
else if (password == "") {
  $("#obligatoirePassword").html("Password est obligatoire!");
}
else {
  $.ajax({
  type: "POST",
  url: "loginUser.php",
  data: dataString,
  cache: false,
  success: function(data){
    data = $.parseJSON(data);
    if(data.status=="success")
  {
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
  }
  else if(data.status=="disable"){
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
  }
  else {
  $("#erreur").html("<img src='assets/images/login/Subtract.png' > <span style='font-weight: 700;font-size: 15px;line-height: 15px;color: #BF1616;'>Login ou mot de passe incorrect !</span> <span style='font-weight: 600;font-size: 15px;line-height: 15px;color: #0071C4;'>Veuillez essayer à nouveau.</span>");
  }
  }
  });
  return false;}
  });
  }

