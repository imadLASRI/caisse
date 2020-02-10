@extends('voyager::master')

@section('content')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- datepicker -->
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" integrity="sha256-p6xU9YulB7E2Ic62/PX+h59ayb3PBJ0WFTEQxq0EjHw=" crossorigin="anonymous" /> -->

    <div class="page-content">
        @include('voyager::alerts')
        <!-- @include('voyager::dimmers') -->
        <div id="dash">
            <!-- ======== Chart row ======== -->
            <div class="row">
                <div class="container">
                    <div id="chart" class="flex">
                        <div class="chart_container_journal">
                        <!-- flex_space_between flex_align_center -->
                        <div class="chart_header flex flex_align_baseline space-between">
                            <!-- DATE FILTERS -->
                            <div class="flex-space">
                                <div class="dateFilters">
                                    <label for="date_debut">Date de Début : </label>
                                        <input class="date_filters" id="date_debut" placeholder="JJ/MM/AAAA" type="date"/>
                                    </label>
                                    <label for="date_fin">Date de Fin : </label>
                                        <input class="date_filters" id="date_fin" type="date" placeholder="JJ/MM/AAAA" min=""/>
                                    </label>
                                </div>

                                <!-- CUSTOM CHART DISPLAY -->
                                <select name="customChart" id="customChart" class="chartFiltre">
                                    <option value="auto" selected>Affichage : Auto</option>
                                    <option value="days" >Par Jours</option>
                                    <option value="weeks" >Par Semaines</option>
                                    <option value="months" >Par Mois</option>
                                    <option value="years" >Par Années</option>
                                </select>
                            </div>

                            <!-- DISPLAY FILTERS -->
                            <div class="displayFilters">

                                <!-- AFFECTATION FILTERS -->

                                <div id="affectation_filters">
                                    <label class="filtre_label">Filtre Affectations: </label><br>
                                    <!-- BU -->
                                    <select name="filtre_bu" id="filtre_bu" class="chartFiltre">
                                        <option value="none" selected>Toutes Business Unit</option>
                                        @foreach($bu as $businessunit)
                                            <option value="{{ $businessunit->id }}">{{ $businessunit->nom }}</option>
                                        @endforeach
                                    </select>

                                    <!-- CLIENT -->
                                    <select name="filtre_client" id="filtre_client" class="chartFiltre">
                                        <option value="none" selected>Tous les clients</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->nom }}</option>
                                        @endforeach
                                    </select>

                                    <!-- PROJET -->
                                    <select name="filtre_projet" id="filtre_projet" class="chartFiltre">
                                        <option value="none" selected>Tous les projets</option>
                                        @foreach($projets as $projet)
                                            <option value="{{ $projet->id }}">{{ $projet->titre }}</option>
                                        @endforeach
                                    </select>

                                    <!-- SITE -->
                                    <select name="filtre_site" id="filtre_site" class="chartFiltre">
                                        <option value="none" selected>Tous les sites</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}">{{ $site->nom }}</option>
                                        @endforeach
                                    </select>

                                    <!-- PRESTATION -->
                                    <select name="filtre_prestation" id="filtre_prestation" class="chartFiltre">
                                        <option value="none" selected>Toutes les prestations</option>
                                        @foreach($prestations as $prestation)
                                            <option value="{{ $prestation->id }}">{{ $prestation->titre }}</option>
                                        @endforeach
                                    </select>

                                    <!-- FOURNISSEUR -->
                                    <select name="filtre_fournisseur" id="filtre_fournisseur" class="chartFiltre">
                                        <option value="none" selected>Tous les fournisseurs</option>
                                        @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                        @endforeach
                                    </select>

                                    <!-- SUPERVISEUR -->
                                    <select name="filtre_superviseur" id="filtre_superviseur" class="chartFiltre">
                                        <option value="none" selected>Tous les superviseurs</option>
                                        @foreach($superviseurs as $superviseur)
                                            <option value="{{ $superviseur->id }}">{{ $superviseur->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- ALIMENTATION FILTERS -->

                                <div id="alimentation_filters">
                                    <label class="filtre_label">Filtre Alimentations: </label><br>
                                    <!-- PROVENANCE -->
                                    <select name="filtre_provenance" id="filtre_provenance" class="chartFiltre">
                                        <option value="none" selected>Toutes Provenances</option>
                                        @foreach($provenances as $provenance)
                                            <option value="{{ $provenance->id }}">{{ $provenance->nom }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <!-- METHODE -->
                                    <select name="filtre_methode" id="filtre_methode" class="chartFiltre">
                                        <option value="none" selected>Toutes Methodes</option>
                                        @foreach($methodes as $methode)
                                            <option value="{{ $methode->id }}">{{ $methode->titre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                            <!-- <canvas id="myChart" width="auto" height="70vh"></canvas> -->
                    </div>
                </div>
            </div>
            <!-- ======== End Chart row ======== -->

            <!-- ======== History logs ======== -->
            <div class="row" style="margin:0;">
                <div class="container">
                    <div id="history" class="flex flex_space_between">
                        <div class="left">
                            <div class="section_title flex flex_align_center flex_space_between">
                                <h2 class="title">Affectations :</h2>
                                <a href="{{ route('voyager.affectations.index') }}" class="btn btn_primary view_all">Afficher tout</a>
                            </div>
                            <div class="history_data_container" id="history_affect" style="height: 300px; overflow: auto;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Date affectation</th>
                                            <th>Montant</th>
                                            <th>BU</th>                                           
                                            <th>Projet</th>
                                            <th>Etat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="right">
                            <div class="section_title flex flex_align_center flex_space_between">
                                <h2 class="title">Alimentations :</h2>
                                <a href="{{ route('voyager.alimentations.index') }}" class="btn btn_primary view_all">Afficher tout</a>
                            </div>
                            <div class="history_data_container" id="history_alim" style="height: 300px; overflow: auto;">
                                <table>
                                    <thead >
                                        <tr>
                                            <th>Date Alimentation</th>
                                            <th>Montant</th>
                                            <th>Méthode</th>
                                            <th>Provenance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ======== End History logs ======== -->
        </div>
    </div>
@stop

@section('javascript')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js" integrity="sha256-8zyeSXm+yTvzUN1VgAOinFgaVFEFTyYzWShOy9w7WoQ=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js" integrity="sha256-nZaxPHA2uAaquixjSDX19TmIlbRNCOrf5HO1oHl5p70=" crossorigin="anonymous"></script>

    
    <!-- JQ UI for datepicker -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script> -->



    <script type="text/javascript">

        // getting chart data function
        function myChartData(){
            $('#myChart').remove();
            $('.chart_container_journal').append('<canvas id="myChart" width="auto" height="80vh"></canvas>');

            // calling nbrOfDays to get the number of days
            var numberOfDays = nbrOfDays( baldeDatePicker($('#date_debut').val()), baldeDatePicker($('#date_fin').val()) );
            
            // reversing start and end date if user fliped them / frontend security
            if(numberOfDays<0){
                numberOfDays = numberOfDays * -1;

                var temp = $('#date_debut').val();
                $('#date_debut').val(  $('#date_fin').val() );
                $('#date_fin').val( temp );
            }

            $.ajax({
                    dataType: 'JSON',
                    type: 'POST',
                    url: '/journalchart',
                    data : {
                        '_token' : $('meta[name="csrf-token"]').attr('content'),
                        'filtre_bu' : $('#filtre_bu').val(),
                        'filtre_client': $('#filtre_client').val(),
                        'filtre_projet': $('#filtre_projet').val(),
                        'filtre_provenance': $('#filtre_provenance').val(),
                        'filtre_methode': $('#filtre_methode').val(),
                        'filtre_site': $('#filtre_site').val(),
                        'filtre_prestation': $('#filtre_prestation').val(),
                        'filtre_fournisseur': $('#filtre_fournisseur').val(),
                        'filtre_superviseur': $('#filtre_superviseur').val(),
                        'date_debut' : baldeDatePicker($('#date_debut').val()),
                        'date_fin' : baldeDatePicker($('#date_fin').val()),
                        'numberOfDays' : numberOfDays,
                        'chartMode' : $('#customChart').val()
                    },

                    success: function(data) {

                        if ( !$.isEmptyObject(data['affectations_logs']) ){
                            $('.left').show();
                            // getting all history logs based on the filters
                            $('#history_affect table tbody tr').remove();

                            // history logs after filtering
                            $.each(data['affectations_logs'], function(index, value){
                                $('#history_affect table').append(`<tr>
                                                                    <td>` + bladeJSdate(value.date_affectation) + `</td>
                                                                    <td>` + value.montant + `</td>
                                                                    <td>` + value.company_id + `</td>
                                                                    <td>` + value.project_id + `</td>
                                                                    <td>` + value.etat + `</td>
                                                                </tr>`);
                            });
                            // $('#history_affect').prev('div').find('.title').text('Affectations filtrées :');
                        }else{
                            $('#history_affect table tbody tr').remove();
                            $('.left').hide();
                        }

                        if ( !$.isEmptyObject(data['alimentations_logs']) ){
                            $('.right').show();
                            // getting all history logs based on the filters
                            $('#history_alim table tbody tr').remove();

                            // history logs after filtering
                            $.each(data['alimentations_logs'], function(index, value){
                                $('#history_alim table').append(`<tr>
                                                                    <td>` + bladeJSdate(value.date_alimentation) + `</td>
                                                                    <td>` + value.montant + `</td>
                                                                    <td>` + value.method_id + `</td>
                                                                    <td>` + value.provenance_id + `</td>
                                                                </tr>`);
                            });
                            // $('#history_alim').prev('div').find('.title').text('Alimentations filtrées :');
                        }else{
                            $('#history_alim table tbody tr').remove();
                            $('.right').hide();
                        }

                        // Refilling Site select if a supervisor is chosen
                        $('#filtre_site option').show();

                        if($('#filtre_superviseur').val() != 'none'){
                            $('#filtre_site option').hide();
                            $('#filtre_site option[value="none"]').show();

                            $.each(data['sites_provisor'], function(index, value){
                                $.each($('#filtre_site option'), function(i, v){
                                    if(value.id == $(v).val()){
                                        $('#filtre_site option[value="' + value.id + '"]').show();
                                    }
                                });
                            });

                            // keeping the chosen site if not hidden
                            if($('#filtre_site').val() != 'none'){
                                if($('#filtre_site option[value="' + $('#filtre_site').val() + '"]').css('display') != 'none'){
                                    $('#filtre_site').val($('#filtre_site').val());
                                }
                                else{
                                    $('#filtre_site').val("none");
                                    $('#filtre_site').css('color', 'inherit');
                                }                            
                            }
                        }

                        // empty arrays before fetching new data
                        bu = [];
                        affect_data = [];
                        alim_data = [];
                        timeLine = [];

                        $.each(data['timeLine'], function(index, value){
                            timeLine.push(value);
                        });

                        $.each(data['alim_month'], function(index, value){
                            alim_data.push(value);
                        });

                        $.each(data['affect_month'], function(index, value){
                            affect_data.push(value);
                        });

                    // CHART
                    // ============================================================>>
                    var ctx = document.getElementById('myChart');
                    var myChart = new Chart(ctx, {
                        type: 'line',

                        data: {
                            labels: timeLine,
                            
                            datasets: [{
                                label: 'Allimentation',
                                backgroundColor: [
                                    '#3E629E'
                                ],
                                borderColor: [
                                    '#3E629E'
                                ],
                                data: alim_data,
                                fill: false,
                            },
                            {
                                label: 'Affectation',
                                fill: false,
                                data: affect_data,
                                backgroundColor: [
                                    '#97999A'
                                ],
                                borderColor: [
                                    '#97999A'
                                ]
                            }]
                        // end data
                        }
                    // end Chart()
                    });
                    // end Success ajax
                    },
            });
        }

        $(document).ready(function(){
            //
            $('.left').hide();
            $('.right').hide();

            // setting stating dates on load
            $('#date_fin').val(todayJS());
            $('#date_debut').val(monthsAgo());

            // Custom DATEPICKER
            bladeToDatepicker( $('#date_debut'), '', bladeJSdate(todayJS()), true, $('#date_debut'), $('#date_fin'));
            $('#date_debut').val(
                        bladeJSdate($('#date_debut').val())
                    );

            bladeToDatepicker( $('#date_fin'), $('#date_debut').val(), bladeJSdate(todayJS()), true, $('#date_debut'), $('#date_fin'));
            $('#date_fin').val(
                        bladeJSdate($('#date_fin').val())
                    );
            
            myChartData();

            // recalling ajax if filters are ON
            // ==================================================================>
            // Affectation filters

            $('#filtre_bu').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('affectation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });

            $('#filtre_client').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('affectation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });

            $('#filtre_projet').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('affectation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });
            
            $('#filtre_site').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('affectation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });

            $('#filtre_prestation').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('affectation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });

            $('#filtre_fournisseur').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('affectation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });

            // Adding supervisor
            $('#filtre_superviseur').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('affectation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });

            // Alimentation filters

            $('#filtre_provenance').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('alimentation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });

            $('#filtre_methode').change(function(){
                if($('#date_debut').val() != '' && $('#date_fin').val() != ''){
                    JournalFiltersManager('alimentation');
                }
                ($(this).val() != 'none')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });
            // ====================================================

            // Custom chart select
            $('#customChart').change(function(){
                myChartData();
                ($(this).val() != 'auto')? $(this).css('color', 'black') : $(this).css('color', 'inherit');
            });
            
            $('footer').remove();
        // END RDY function
        });

        </script>
@stop
