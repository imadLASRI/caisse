@extends('voyager::master')

@section('content')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <div class="page-content">
        @include('voyager::alerts')
        <!-- @include('voyager::dimmers') -->
        
        <div id="dash">
            <!-- ======== Stats row ======== -->
            <div class="row">
                <div class="container">
                    <div id="stats" class="flex flex_space_between">
                        <div class="state_container">
                            <div class="state_data">
                                <p class="state_data_value">{{ number_format($restcaisse,2,"."," ") ?? "-" }} DH</p>
                                <span class="state_data_label">Caisse disponible</span>
                            </div>
                            <div class="state_icon">
                                <div class="icon icon_primary">
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="state_container">
                            <div class="state_data">
                                <p class="state_data_value">{{ number_format($totalAlimentation,2,"."," ") ?? "-"  }} DH</p>
                                <p class="state_data_label">Montant reçu</p>
                            </div>
                            <div class="state_icon">
                                <div class="icon icon_success">
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="state_container">
                            <div class="state_data">
                                <p class="state_data_value">
                                    {{ number_format($totalAffectation,2,"."," ") ?? "-" }} DH
                                    <div style="font-size:12px;">(dont <b>{{ number_format($totalaffectNonDecaisse,2,"."," ") ?? "-" }}</b> DH non décaissé)</div>
                                </p>
                               
                                <p class="state_data_label">Montant affecté</p>
                            </div>
                            <div class="state_icon">
                                <div class="icon icon_danger">
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="state_container">
                            <div class="state_data">
                                <p class="state_data_value">{{ $bu ?? "-" }}</p>
                                <p class="state_data_label">Business Unit</p>
                            </div>
                            <div class="state_icon">
                                <div class="icon icon_primary">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ======== End Stats row ======== -->


            <!-- ======== Chart row ======== -->
            <div class="row">
                <div class="container">
                    <div id="chart" class="flex">
                        <div class="chart_container">

                        <div class="chart_header flex flex_space_between flex_align_center">
                          
                            <h3 class="title">Alimentations / Affectations </h3>
                            <select name="dataYear" id="dataYear">
                                <option value="none" selected>Cette Année</option>
                                @foreach( $Years as $year )
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                            <!-- <canvas id="myChart" width="auto" height="70vh"></canvas> -->
                        </div>
                        <div class="chart_stats_container">
                            <div class="state_container">
                                <div class="state_data">
                                    <h5>Affectation / BU </h5>
                                    @foreach($affectation_by_bu as $key => $value)
                                        <div style="margin-top:15px">
                                            <p class="state_data_label">{{ $key }}</p>
                                            <p class="state_data_value">{{ number_format($value,2,"."," ") }} DH</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ======== End Chart row ======== -->


            <!-- ======== History logs ======== -->
            <div class="row">
                <div class="container">
                    <div id="history" class="flex flex_space_between">
                        <div class="left">
                            <div class="section_title flex flex_align_center flex_space_between">
                                <h2 class="title">Dernières affectations :</h2>
                                <a href="{{ route('voyager.affectations.index') }}" class="btn btn_primary view_all">Afficher tout</a>
                            </div>
                            <div class="history_data_container">
                              
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
                                        @foreach($latestAssignment as $affectation)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($affectation->date_affectation)) ?? "-" }}</td>
                                            <td>{{ number_format($affectation->montant,2,"."," ") ?? "-" }}</td>
                                            <td>{{ $affectation->company->nom ?? "-" }}</td>
                                            <td>{{ $affectation->projects->titre ?? "-" }}</td>
                                            <td>{{ $etat[$affectation->etat] ?? "-" }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="right">
                            <div class="section_title flex flex_align_center flex_space_between">
                                <h2 class="title">Dernières Alimentations :</h2>
                                <a href="{{ route('voyager.alimentations.index') }}" class="btn btn_primary view_all">Afficher tout</a>
                            </div>
                            <div class="history_data_container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Date Alimentation</th>
                                            <th>Montant</th>
                                            <th>Méthode</th>
                                            <th>Provenance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestSupply as $allimentation)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($allimentation->date_alimentation)) }}</td>
                                            <td>{{ number_format($allimentation->montant ,2,"."," ") }}</td>
                                            <td>{{ $allimentation->methode->titre ?? "-" }}</td>
                                            <td>{{ $allimentation->provenance->nom ?? "-" }}</td>
                                        </tr>
                                        @endforeach
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

    
    <script type="text/javascript">

        function myChartData(){
            $('#myChart').remove();
            $('.chart_container').append('<canvas id="myChart" width="auto" height="70vh"></canvas>');

            $.ajax({
                    dataType: 'JSON',
                    type: 'POST',
                    url: '/chartdata',
                    data : {
                        '_token' : $('meta[name="csrf-token"]').attr('content'),
                        'dataYear' : $('#dataYear').val(),
                    },

                    success: function(data) {
                        // empty arrays before fetching new data
                        affect_data = [];
                        alim_data = [];
                        months = [];
                        nodata = 0;

                        $.each(data['alim_month'], function(index, value){
                            alim_data.push(value);
                        });

                        $.each(data['affect_month'], function(index, value){
                            affect_data.push(value);
                        });

                        $.each(data['months'], function(index, value){
                            months.push(value);
                        });

                    // CHART
                    // ============================================================>>
                    var ctx = document.getElementById('myChart');
                    var myChart = new Chart(ctx, {
                        type: 'line',

                        data: {
                            labels: months,
                            
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
                            }, {
                                label: 'Affectation',
                                fill: false,
                                data: affect_data,
                                backgroundColor: [
                                    '#97999A'
                                ],
                                borderColor: [
                                    '#97999A'
                                ],
                        }]
                    }
                    });
                    // ===========================================================<<
                    // end Success ajax
                    },
                });
        }

        $(document).ready(function(e){

            myChartData();
            $(document).on('change','#dataYear',function(){
                myChartData();
            });
            
        // END RDY function
        });

        </script>
@stop
