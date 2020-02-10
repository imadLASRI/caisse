
$(document).ready(function() {
  // moving bulk delet btn
  $("#bulk_delete_btn").insertBefore(".table-responsive .row:last-child");

  // calling function on load
  removeOrderSign();
  // removing order signs
  function removeOrderSign(){

      var tabField = ['Montant','Date Affectation', 'Date Alimentation','Titre','Type','Nom&Prénom','Nom'];
      $.each( $('th'), function(index, value){

          if (  jQuery.inArray(value.innerText,tabField)==-1  ){
              $(this).addClass('sorting_disabled');
              $(this).removeClass('sorting sorting_desc sorting_asc');
          }
      });
  }

  $('th.sorting_disabled').off('click');
  $('th.sorting').on('click',function(e){
      removeOrderSign();       
  });

  // validation msgs
  $(".panel form").attr("novalidate", "novalidate");
  $('.form-edit-add .help-block').remove();

  // submitting data added from modals after validation
  parsleyModal();
});

// formating datepicker to DB format
function baldeDatePicker(pickerDate) {

  var temp = pickerDate.split("/"); 
  var formated = temp[2] + '-' + temp[1] + '-' + temp[0];
  
  return formated;
}

// creating datepicker with different parameters (last 3 are optional)
function bladeToDatepicker(selector, min="", max="", journal=false, datedebut='', datefin=''){
  selector.datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    changeYear: true,
    minDate: min,
    maxDate: max,

    onSelect: function() {
      if(journal){
        // setting min and max values on change
        datefin.datepicker( "option", "minDate", datedebut.val() );
        datedebut.datepicker( "option", "maxDate", datefin.val() );

        // reload chart
        myChartData();
      }
    }
  });

  selector.prop('placeholder', 'JJ / MM / AAAA');
  selector.prop('type', 'text');
  selector.prop('autocomplete', 'off');
}

// formating date to use in the JOURNAL
function bladeJSdate(bladeDate) {

var temp = bladeDate.split("-");
var formated = temp[2] + '/' + temp[1] + '/' + temp[0];

return formated;
}

// Getting todyas date
function todayJS() {
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0');
var yyyy = today.getFullYear();

today = yyyy + '-' + mm + '-' + dd;

return today;
}
// Getting 3 monthsAgo date
function monthsAgo() {
var today = new Date();
today.setMonth(today.getMonth() - 3);

var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0');
var yyyy = today.getFullYear();

today = yyyy + '-' + mm + '-' + dd;

return today;
}

// getting the number of days between the two dates
function nbrOfDays(debut, fin) {
var dateDebut = new Date(debut);
var dateFin = new Date(fin);
var numberOfDays = ((dateFin.getTime() - dateDebut.getTime()) / (1000 * 3600 * 24)) + 1;

return numberOfDays;
}
// =================================>

function parsleyModal(){
  var form = $('.ModalForm');
  form.on('submit', function(e){
      e.preventDefault();

      var model = $(this).find('.modalSubmit').data('model');

      CreateFromModal(model);
      // msg success
      toastr.success(model.charAt(0).toUpperCase() + model.slice(1) + ' Ajouté');
      $('#toast-container').addClass('toastMargin');
      
      // setting the added as selected after a timeout
      setSelected(model);

      // clearing form fields after submit
      $.each($('.ModalForm'), (index, val) => {
        val.reset();
      });

  });
}

// Client refill if added new one
// =================================>
function loadClients(){
  // removing all previous options
  $('#clients').empty().append("<option value=''>Sélectionnez un client</option>");
  $('#projects').empty().append("<option value=''>Sélectionnez un projet</option>");
  $('#sites').empty().append("<option value=''>Sélectionnez un site</option>");
  $('#prestations').empty().append("<option value=''>Sélectionnez une prestation</option>");
  $('#suppliers').empty().append("<option value=''>Sélectionnez un fournisseur</option>");
  // refilling clients to add the new one
  $.ajax({
      dataType: 'JSON',
      type: 'GET',
      url: '/getclients',
      data: {
          '_token': $('meta[name="csrf-token"]').attr('content'),
      },

      success: function(data) {
          $.each( data, function( key, value ) {
              $('#clients').append("<option value='" + value.id + "'>" + value.nom + "</option>");
          });

          // adding add option
          $('#clients').append("<option class='addnew' value='nouveauclient' >Nouveau Client</option>");
          $('#projects').append("<option class='addnew' value='nouveauprojet' >Nouveau Projet</option>");
          $('#sites').append("<option class='addnew' value='nouveausite' >Nouveau Site</option>");
          $('#prestations').append("<option class='addnew' value='nouveauprestation' >Nouvelle Prestation</option>");
          $('#suppliers').append("<option class='addnew' value='nouveauprovider' >Nouveau Fournisseur</option>");
      },
  });
}


// Projets refill if added new one
// =================================>
function loadProjets(){
// removing all previous options
$('#projects').empty().append("<option value=''>Sélectionnez un projet</option>");
$('#sites').empty().append("<option value=''>Sélectionnez un site</option>");
$('#prestations').empty().append("<option value=''>Sélectionnez une prestation</option>");
$('#suppliers').empty().append("<option value=''>Sélectionnez un fournisseur</option>");

$.ajax({
    dataType: 'JSON',
    type: 'POST',
    url: '/getprojects',
    data: {
        '_token': $('meta[name="csrf-token"]').attr('content'),
        'id': $('#clients').val(),
    },

    success: function(data) {
        $.each( data, function( key, value ) {
            $('#projects').append("<option value='" + value.id + "'>" + value.titre + "</option>");
        });

        // adding add option
        $('#projects').append("<option class='addnew' value='nouveauprojet' >Nouveau Projet</option>");
        $('#sites').append("<option class='addnew' value='nouveausite' >Nouveau Site</option>");
        $('#prestations').append("<option class='addnew' value='nouveauprestation' >Nouvelle Prestation</option>");
        $('#suppliers').append("<option class='addnew' value='nouveauprovider' >Nouveau Fournisseur</option>");
    },
});
}


// Site refill if added new one
// =================================>
function loadSites(){
// removing all previous options
  $('#sites').empty().append("<option value=''>Sélectionnez un site</option>");
  $('#prestations').empty().append("<option value=''>Sélectionnez une prestation</option>");
  $('#suppliers').empty().append("<option value=''>Sélectionnez un fournisseur</option>");


  $.ajax({
      dataType: 'JSON',
      type: 'POST',
      url: '/getsites',
      // url: '/getprestations',
      data: {
          '_token': $('meta[name="csrf-token"]').attr('content'),
          'id': $('#projects').val(),
      },

      success: function(data) {

          $.each( data, function( key, value ) {
              // $('#prestations').append("<option value='" + value.id + "'>" + value.titre + "</option>");
              $('#sites').append("<option value='" + value.id + "'>" + value.nom + "</option>");
          });
          // adding add option

          $('#sites').append("<option class='addnew' value='nouveausite' >Nouveau Site</option>");
          $('#prestations').append("<option class='addnew' value='nouveauprestation' >Nouvelle Prestation</option>");
          $('#suppliers').append("<option class='addnew' value='nouveauprovider' >Nouveau Fournisseur</option>");
      },
  });
}


// Prestation refill if added new one
// =================================>
function loadPrestations(){
// removing all previous options
$('#prestations').empty().append("<option value=''>Sélectionnez une prestation</option>");
$('#suppliers').empty().append("<option value=''>Sélectionnez un fournisseur</option>");

$.ajax({
    dataType: 'JSON',
    type: 'POST',
    url: '/getprestations',
    data: {
        '_token': $('meta[name="csrf-token"]').attr('content'),
        'id': $('#sites').val(),
    },

    success: function(data) {

        $.each( data, function( key, value ) {
            $('#prestations').append("<option value='" + value.id + "'>" + value.titre + "</option>");
        });
        // adding add option

        $('#prestations').append("<option class='addnew' value='nouveauprestation' >Nouvelle Prestation</option>");
        $('#suppliers').append("<option class='addnew' value='nouveauprovider' >Nouveau Fournisseur</option>");
    },
});
}


// Fournisseur refill if added new one
// =================================>
function loadFournisseurs(){
// removing all previous options
$('#suppliers').empty().append("<option value=''>Sélectionnez un fournisseur</option>");

$.ajax({
    dataType: 'JSON',
    type: 'POST',
    url: '/getProviders',
    data: {
        '_token': $('meta[name="csrf-token"]').attr('content'),
        'id': $('#prestations').val(),
    },

    success: function(data) {

        $.each( data, function( key, value ) {
            $('#suppliers').append("<option value='" + value.id + "'>" + value.nom + "</option>");
        });
        // adding add option
        $('#suppliers').append("<option class='addnew' value='nouveauprovider' >Nouveau Fournisseur</option>");
    },
});
}


// linking option to ADD modals
// ==========================
function showModal(){
  // add new
  if($('#clients').val() == "nouveauclient"){
      $('#modalTog').attr('data-target', '#clientModal');
      $('#modalTog').trigger('click');
  }
  if($('#projects').val() == "nouveauprojet"){
      if($('#clients').val() != ''){
          $('#modalTog').attr('data-target', '#projetModal');
          $('#modalTog').trigger('click');
      }
      else{
        toastr.error('Merci de sélectionner un client.');
        $('#toast-container').addClass('toastMargin');
      }
  }
  if($('#sites').val() == "nouveausite"){
      if($('#projects').val() != ''){
          $('#modalTog').attr('data-target', '#siteModal');
          $('#modalTog').trigger('click');
      }
      else{
        toastr.error('Merci de sélectionner un projet.');
        $('#toast-container').addClass('toastMargin');
      }
  }
  if($('#prestations').val() == "nouveauprestation"){
      if($('#sites').val() != ''){
          $('#modalTog').attr('data-target', '#prestationModal');
          $('#modalTog').trigger('click');
      }
      else{
        toastr.error('Merci de sélectionner un site.');
        $('#toast-container').addClass('toastMargin');
      }
  }
  if($('#suppliers').val() == "nouveauprovider"){
      if($('#prestations').val() != ''){
          $('#modalTog').attr('data-target', '#fournisseurModal');
          $('#modalTog').trigger('click');
      }
      else{
        toastr.error('Merci de sélectionner une prestation.');
        $('#toast-container').addClass('toastMargin');
      }
  }
}


// modals ajax data function
// ========================================================================
function CreateFromModal(model){
data = {
  '_token': $('meta[name="csrf-token"]').attr('content'),
  'dataModel': model
};

if(model == 'client'){
  data = { ...data,
    'nom': $('#addClient #nom_client').val(),
    'telephone': $('#addClient #telephone_client').val(),
    'email': $('#addClient #email_client').val(),
    'adresse': $('#addClient #adresse_client').val(),
    'ice': $('#addClient #ice_client').val(),
  };
}
else if(model == 'projet'){
  data = { ...data,
    'titre': $('#addProjet #titre_projet').val(),
    'client_id': $('#clients').val()
  };
}
else if(model == 'site'){
  data = { ...data,
    'nom': $('#addSite #nom_site').val(),
    'budget': $('#addSite #budget_site').val(),
    'project_id': $('#projects').val(),
    'supervisor_id': $('#superviseur_site').val(),
  };
}
else if(model == 'prestation'){
  data = { ...data,
    'titre': $('#addPrestation #titre_prestation').val(),
    'descriptif': $('#addPrestation #descriptif_prestation').val(),
    'site_id': $('#sites').val(),
  };
}
else if(model == 'fournisseur'){
  data = { ...data,
    'nom': $('#addFournisseur #nom_fournisseur').val(),
    'telephone': $('#addFournisseur #telephone_fournisseur').val(),
    'email': $('#addFournisseur #email_fournisseur').val(),
    'adresse': $('#addFournisseur #adresse_fournisseur').val(),
    'ice': $('#addFournisseur #ice_fournisseur').val(),
    'prestation_id': $('#prestations').val(),
  };
}


// Posting data
$.ajax({
  dataType: 'JSON',
  type: 'POST',
  url: '/modaldata',
  data: data,

  success: function(data) {
    // triggering projects select change to refill
    if(model == 'client'){
      // reload data
      loadClients();
    }else if(model == 'projet'){
      // reload data
      $( "#clients" ).trigger( "change" );
    }else if(model == 'site'){
      // reload data
      $( "#projects" ).trigger( "change" );
    }
    else if(model == 'prestation'){
      // reload data
      $( "#sites" ).trigger( "change" );
    }
    else if(model == 'fournisseur'){
      // reload data
      $( "#prestations" ).trigger( "change" );
    }
    $('.close').trigger('click');
  }
});

}

// Set selected option with a time out

function setSelected(model){
  if(model == 'client'){
    setTimeout(function(){
      $('#clients').val($('#clients option:last-child').prev().val());
      $('#clients').select2().trigger('change');
    }, 1000);
  }
  else if(model == 'projet'){
    setTimeout(function(){
      $('#projects').val($('#projects option:last-child').prev().val());
      $('#projects').select2().trigger('change');
    }, 1000);
  }
  else if(model == 'site'){
    setTimeout(function(){
      $('#sites').val($('#sites option:last-child').prev().val());
      $('#sites').select2().trigger('change');
    }, 1000);
  }
  else if(model == 'prestation'){
    setTimeout(function(){
      $('#prestations').val($('#prestations option:last-child').prev().val());
      $('#prestations').select2().trigger('change');
    }, 1000);
  }
  else if(model == 'fournisseur'){
    setTimeout(function(){
      $('#suppliers').val($('#suppliers option:last-child').prev().val());
      $('#suppliers').select2().trigger('change');
    }, 1000);
  }
}

// cleaning logs table on read view (hiding actual selected item)
function cleanLogs(model){
  if(model == 'bu'){
    setTimeout(() => {
      $('#th_bu').remove();
      $('.td_bu').remove();
    }, 500);
    
  }
  else if(model == 'prestation'){
    setTimeout(() => {
      $('#th_prestation').remove();
      $('.td_prestation').remove();
    }, 500);
  }
  else if(model == 'client'){
    setTimeout(() => {
      $('#th_client').remove();
      $('.td_client').remove();
    }, 500);
  }
  else if(model == 'projet'){
    setTimeout(() => {
      $('#th_projet').remove();
      $('.td_projet').remove();
    }, 300);
    $('#myChart').attr('height', '90vh')
  }
  else if(model == 'fournisseur'){
    setTimeout(() => {
      $('#th_fournisseur').remove();
      $('.td_fournisseur').remove();
    }, 300);
  }
  else if(model == 'site'){
    setTimeout(() => {
      $('#th_site').remove();
      $('.td_site').remove();
    }, 300);
  }
}

// Managing journal filters
function JournalFiltersManager(filter){
  if(filter == 'alimentation'){
      $.each($('#affectation_filters select.chartFiltre'), function(index, val){
          $(val).val('none');
          $(val).css('color', 'black');
          $(val).css('color', 'inherit');
      });
  }
  else if(filter == 'affectation'){
      $.each($('#alimentation_filters select.chartFiltre'), function(index, val){
          $(val).val('none');
          $(val).css('color', 'black');
          $(val).css('color', 'inherit');
      });
  }
  myChartData();
}