@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-8">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data"
                            data-parsley-validate>
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}    
                            @php

                                $restcaisse +=$dataTypeContent->montant
                                
                            @endphp                            
                                                                             
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if (isset($row->details->view))
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add')])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                       
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

                            <!-- custom html inputs -->
                            @if(!$edit)
                                <div class="form-group col-md-12 ">
                                    <label class="control-label" for="clients">Client</label>
                                    <select class="form-control customSelect" id="clients" style="" name="client_id" >
                                        <option value="">Sélectionnez un client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->nom }}</option>
                                        @endforeach
                                        <!-- option link to add client -->
                                            <option class="addnew" value="nouveauclient" >Nouveau Client</option>
                                    </select>
                                </div>

                                <div class="form-group  col-md-12 ">
                                    <label class="control-label" for="projects">Projet</label>
                                    <select class="form-control customSelect" id="projects" style="" name="project_id" >
                                        <option value="">Sélectionnez un projet</option>
                                        <!-- option link to add projet -->
                                        <option class="addnew" value="nouveauprojet" >Nouveau Projet</option>
                                    </select>
                                </div>

                                <div class="form-group  col-md-12 ">
                                    <label class="control-label" for="sites">Site</label>
                                    <select class="form-control customSelect" id="sites" style="" name="site_id" >
                                        <option value="">Sélectionnez un site</option>
                                        <!-- option link to add prestation -->
                                        <option class="addnew" value="nouveausite" >Nouveau Site</option>
                                    </select>
                                </div>

                                <div class="form-group  col-md-12 ">
                                    <label class="control-label" for="prestations">Prestation</label>
                                    <select class="form-control customSelect" id="prestations" style="" name="prestation_id" >
                                        <option value="">Sélectionnez une prestation</option>
                                        <!-- option link to add prestation -->
                                        <option class="addnew" value="nouveauprestation" >Nouvelle Prestation</option>
                                    </select>
                                </div>

                                <div class="form-group  col-md-12 ">
                                    <label class="control-label" for="suppliers">Fournisseur</label>
                                    <select class="form-control customSelect" id="suppliers" style="" name="supplier_id" >
                                        <option value="">Sélectionnez un fournisseur</option>
                                        <!-- option link to add prestation -->
                                        <option class="addnew" value="nouveauprovider" >Nouveau Fournisseur</option>
                                    </select>
                                </div>
                            @endif

                            @if($edit)

                            <div class="form-group col-md-12 ">
                                <label class="control-label" for="clients">Client</label>
                                <select class="form-control customSelect" id="clients" style="" name="client_id" >
                                    <option value="">Sélectionnez un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if($assignment->client_id == $client->id) selected @endif>{{ $client->nom }}</option>
                                    @endforeach
                                    <option class="addnew" value="nouveauclient" >Nouveau Client</option>
                                </select>
                            </div>

                            <div class="form-group  col-md-12 ">
                                    <label class="control-label" for="projects">Projet</label>
                                <select class="form-control customSelect" id="projects" style="" name="project_id" >
                                    <option value="">Sélectionnez un projet</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" @if($assignment->project_id == $project->id) selected @endif>{{ $project->titre }}</option>
                                    @endforeach
                                    <option class="addnew" value="nouveauprojet" >Nouveau Projet</option>
                                </select>
                            </div>

                            <!-- added sites -->

                            <div class="form-group  col-md-12 ">
                                    <label class="control-label" for="sites">Site</label>
                                <select class="form-control customSelect" id="sites" style="" name="site_id" >
                                    <option value="">Sélectionnez un site</option>
                                    @foreach($sites as $site)
                                    <option value="{{ $site->id }}" @if($assignment->site_id == $site->id) selected @endif>{{ $site->nom }}</option>
                                    @endforeach
                                    <option class="addnew" value="nouveausite" >Nouveau Site</option>
                                </select>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="prestations">Prestation</label>
                                <select class="form-control customSelect" id="prestations" style="" name="prestation_id" >
                                    <option value="">Sélectionnez une prestation</option>
                                    @foreach($prestations as $prestation)
                                    <option value="{{ $prestation->id }}" @if($assignment->prestation_id == $prestation->id) selected @endif>{{ $prestation->titre }}</option>
                                    @endforeach
                                    <option class="addnew" value="nouveauprestation" >Nouvelle Prestation</option>
                                </select>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="suppliers">Fournisseur</label>
                                <select class="form-control customSelect" id="suppliers" style="" name="supplier_id" >
                                    <option value="">Sélectionnez un fournisseur</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @if($assignment->provider_id == $supplier->id) selected @endif>{{ $supplier->nom }}</option>
                                    @endforeach
                                    <option class="addnew" value="nouveauprovider" >Nouveau Fournisseur</option>
                                </select>
                            </div>
                            @endif
                            <!-- END CUSTOM HTML -->


                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                                 onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>

        <!-- modals button -->
        <button id="modalTog" type="button" data-toggle="modal" data-target="" hidden/>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->

    <!-- including modals / using aliasses declared @appserviceprovider -->
    @addClient
    @addProjet
    @addSite
    @addPrestation
    @addFournisseur

@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            // Custom DATEPICKER
            bladeToDatepicker( $('input[name="date_affectation"]'), '', bladeJSdate(todayJS()) );

            @if($edit)
                // reformating the date to show it on the edit
                $('input[name="date_affectation"]').val(bladeJSdate($('input[name="date_affectation"]').val()));
            @endif

            // if the form is valid switch date format to store in DB
            $('.form-edit-add').on('submit', function(){
                $(this).parsley().validate();
                
                if ($(this).parsley().isValid()) {
                    $('input[name="date_affectation"]').val(
                                                        baldeDatePicker($('input[name="date_affectation"]').val())
                                                    );
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });
                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();

            // CUSTOM JS ============================================================================)

            // parsley validation
            $(' input[name="montant"] ').attr('max', '{{ $restcaisse }}');
            $(' input[name="montant"] ').prev().append(' (Doit être inférieur à <b> {{ number_format($restcaisse,2,"."," ") }}</b>)');

            // Custom select2
            $('select.customSelect').select2();
            $('select.modalsCustomSelect').select2();

            // selecting CLIENT
            // =================================>
            $('#clients').change(function(){
                // if new 
                showModal();
                
                if($(this).val() == 'nouveauclient'){
                    loadClients();
                }
                else{
                    loadProjets();
                }
            });

            // selecting PROJECT
            // =================================>
            $('#projects').change(function(){
                // if new 
                showModal();

                if($(this).val() == 'nouveauprojet'){
                    loadProjets();
                }
                else{
                    loadSites();
                }
            });

            // selecting SITE
            // =================================>
            $('#sites').change(function(){
                // if new 
                showModal();

                if($(this).val() == 'nouveausite'){
                    loadSites();
                }
                else{
                    loadPrestations();
                }
            });

            // selecting PRESTATION
            // =================================>
            $('#prestations').change(function(){
                // if new 
                showModal();

                if($(this).val() == 'nouveauprestation'){
                    loadPrestations();
                }
                else{
                    loadFournisseurs();
                }
            });

            // selecting SUPPLIER
            $('#suppliers').change(function(){
                // if new 
                showModal();

                if($(this).val() == 'nouveauprovider'){
                    loadFournisseurs();
                }


            });

            $('span .selection').click(function(){
                if( $('#clients').val() != "nouveauclient" && $('#projects').val() != "nouveauprojet" 
                    && $('#sites').val() != "nouveausite" && $('#prestations').val() != "nouveauprestation" 
                    && $('#suppliers').val() != "nouveauprovider" )
                    {
                        showModal();
                    }
            });
            
        });

    </script>
@stop
