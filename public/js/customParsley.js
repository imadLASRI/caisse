$(document).ready(function() {
    // custom parsley for every blade view
    if($('body.affectations')[0]){
        $(' input[name="montant"] ').attr('required', true);
        $(' input[name="montant"] ').attr('data-parsley-type', 'number');
        $('input[name="date_affectation"]').attr('required',true);
        $('select[name="company_id"]').attr('required',true);
        $('select#suppliers').attr('required',true);
        $('select#prestations').attr('required',true);
        $('select#sites').attr('required',true);
        $('select#projects').attr('required',true);
        $('select#clients').attr('required',true);
    }

    if($('body.alimentations')[0]){
      $(' input[name="montant"] ').attr('data-parsley-type', 'number');
      $(' input[name="date_alimentation"] ').attr('required', true);
      $(' select[name="method_id"] ').attr('required', true);
      $(' select[name="provenance_id"] ').attr('required', true);
    }
    
    if($('body.projects')[0]){
      $(' select[name="customer_id"] ').attr('required', true);
    }

    if($('body.sites')[0]){
      $(' select[name="project_id"] ').attr('required', true);
      $(' select[name="supervisor_id"] ').attr('required', true);
    }

    if($('body.supervisors')[0]){
      $(' input[name="tel"] ').attr('required', true);
      $(' input[name="tel"] ').attr('data-parsley-type', 'number');
    }

    if($('body.prestations')[0]){
      $(' select[name="site_id"] ').attr('required', true);
      $(' input[name="descriptif"] ').attr('required', true);
    }

    if($('body.companies')[0]){
      $(' input[name="telephone"] ').attr('required', true);
      $(' input[name="telephone"] ').attr('data-parsley-type', 'number');
      $(' textarea[name="adresse"] ').attr('required', true);
      $(' input[name="ice"] ').attr('required', true);
      $(' input[name="ice"] ').attr('data-parsley-type', 'number');
  
    }

    if($('body.customers')[0]){
      $(' input[name="telephone"] ').attr('required', true);
      $(' input[name="telephone"] ').attr('data-parsley-type', 'number');
      $(' input[name="email"] ').attr('required', true);
      $(' input[name="email"] ').attr('data-parsley-type', 'email');
      $(' textarea[name="adresse"] ').attr('required', true);
      $(' input[name="ice"] ').attr('required', true);
      $(' input[name="ice"] ').attr('data-parsley-type', 'number');
  
    }

    if($('body.providers')[0]){
      $(' input[name="telephone"] ').attr('required', true);
      $(' input[name="telephone"] ').attr('data-parsley-type', 'number');
      $(' input[name="email"] ').attr('required', true);
      $(' input[name="email"] ').attr('data-parsley-type', 'email');
      $(' textarea[name="adresse"] ').attr('required', true);
      $(' input[name="ice"] ').attr('required', true);
      $(' input[name="ice"] ').attr('data-parsley-type', 'number');
      $(' select[name="prestation_id"] ').attr('required', true);
  
    }

    $('form.form-edit-add').parsley();
});
