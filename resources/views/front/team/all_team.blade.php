@extends('front.layouts.data')
@section('title', 'Team')
@section('css')
<link rel="stylesheet" href="{{ asset('front/css/style_teams.css') }}">
@endsection

@section('navbar')
<nav class="navbar">
    <div class="content container-fluid" style="max-width: 1300px;">
        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ asset('front/img/tahu_ngoding.png') }}" alt=""></a>
        </div>
        <ul class="menu-list">
            <div class="icon cancel-btn">
                <i class="fas fa-times"></i>
            </div>

            <li><a href="{{ url('/') }}" style="font-family: 'Poppins', sans-serif !important;">Home</a></li>
            <li><a href="{{ url('/about') }}" style="font-family: 'Poppins', sans-serif !important;">About</a></li>
            <li><a href="{{ url('/projects') }}" style="font-family: 'Poppins', sans-serif !important;">Projects</a>
            </li>
            <li><a href="{{ url('/teams') }}" style="font-family: 'Poppins', sans-serif !important;">Teams</a></li>
            <li><a href="{{ url('/blog') }}" style="font-family: 'Poppins', sans-serif !important;">Blog</a></li>
            <li><a href="{{ url('/contact') }}" style="font-family: 'Poppins', sans-serif !important;">Contact</a></li>

            @php
                $web_profile = App\Models\WebProfile::all();
            @endphp

            @foreach($web_profile as $data)
            <div class="icon-sosmed-navbar">
                <div class="icon-sosmed-header mt-5">
                <a href="https://www.instagram.com/{{ $data->instagram }}" target="_blank"><img src="{{ asset('front/img/icon-instagram.svg') }}"
                    alt=""></a>
                </div>
                <div class="icon-sosmed-header">
                <a href="https://github.com/{{ $data->github }}"><img src="{{ asset('front/img/icon-github.svg') }}" alt=""></a>
                </div>
                <div class="icon-sosmed-header">
                <a href="{{ $data->linkedin }}""><img src="{{ asset('front/img/icon-linked-in.svg') }}" alt=""></a>
                </div>
            </div>
            @endforeach
        </ul>
        <div class="icon menu-btn">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</nav>
@endsection

@section('content')
<div class="jumbotron jumbotron-fluid teams mt-5 pt-5">
    <div class="content container-fluid" style="max-width: 1300px;">
        <div class="text">
            <h2 class="text-center"><span id="teamSpan">TEAMS</span></h2>
            <p class="text-center mt-5 mb-5">We are young and creative people who are trying to find and develop our
                talents. We can only do small things on our own, but together we can do extraordinary things.</p>
        </div>

        <div id="loadTeam">
        @foreach($team as $data)
            <img class="rounded-circle mb-3" alt="avatar1" style="border-radius: 50%; 
            width: 100px;
            height: 100px;object-fit: contain;" src="{{ Storage::url($data->photo) }}" />
        @endforeach
    {{-- 
            <div class="text-center mt-5 mb-5">
                <a href="{{ url('all-team') }}" type="button" class="button btn" >View More</a>
            </div> --}}

    </div>
</div>
<br><br><br><br><br>

<div class="modal fade" id="team" tabindex="-1" aria-labelledby="team" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="modal-img text-center mb-3">
                            <img src="#" alt="" id="modalImage" width="150">
                        </div>
                        <h3 class="text-img" style="color: #ffd800; text-align: center;" id="modalName"></h3>
                        <p class="card-text-dialog-box text-img text-white" style="text-align: center;" id="titleModalPengurus"></p>
                    </div>
                    <div class="col-md-8">
                        <div class="modal-text py-2 px-5 text-start">
                            {{-- <h3 class="text-white fw-bold">Hadi</h3>
                            <p class="card-text-dialog-box text-white">Ketua</p> --}}
                            <p class="text-white" id="descriptionModal">
                                -
                            </p>
                            <div class="icon-sosmed-footer mt-4">
                                <a href="https://www.instagram.com/tahungoding/" target="_blank"><img
                                        src="{{ asset('front/img/icon-instagram.svg') }}" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $.ajax({
        url: "{{ route('team.ref-divisi') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function (result) {

            var data = JSON.parse(result);

            var team = data[0];

            var ref_divisi = data[1];


            var wrap_team_object = [];

            for (const [key, value] of Object.entries(team)) {
                var team_object = {};

                if(value[4] != null) {
                    team_object['pid'] = value[4] != null ? value[4].toString() : '';
                }
                
                team_object['id'] = value[3] != null ? value[3].toString() : '';

                team_object['title'] = value[2];
                team_object['name'] = value[0];
                team_object['img'] = value[1];
                team_object['description'] = value[5];
             
                wrap_team_object.push(team_object);
            }

            console.log(wrap_team_object);

            //JavaScript

            var chart = new OrgChart(document.getElementById("tree"), {
                template: 'polina',    
                mouseScrool: OrgChart.none,
                layout: OrgChart.mixed,
                enableSearch: false,
                nodeMouseClick: OrgChart.action.none,
                nodeBinding: {
                    img_0: "img",
                    field_0: "name",
                    field_1: "title"
                }
            });

            chart.load(wrap_team_object);


            chart.on('click', function(sender, args){ 
                var data = sender.get(args.node.id);
                console.log(data.name);
                $("#team").modal('show');
                                $("#modalImage").attr('src', '');
                                $("#modalName").html('');
                                $("#titleModalPengurus").html('');
                                $("#descriptionModal").html('');
                                $("#modalImage").attr('src', data.img);
                                $("#modalName").html(data.name);
                                $("#titleModalPengurus").html(data.title);
                                if(data.description == '') {
                                    $("#descriptionModal").html('-');
                                } else {
                                    $("#descriptionModal").html(data.description);
                                }
            });   





        }
    });


    function byYear(ref_periode_id) {
       
        $.ajax({
            url: "{{ route('team.ref-divisi') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                ref_periode_id: ref_periode_id
            },
            success: function (result) {

                var data = JSON.parse(result);

            var team = data[0];

            var ref_divisi = data[1];


            var wrap_team_object = [];

            for (const [key, value] of Object.entries(team)) {
                var team_object = {};

                if(value[4] != null) {
                    team_object['pid'] = value[4] != null ? value[4].toString() : '';
                }
                
                team_object['id'] = value[3] != null ? value[3].toString() : '';

                team_object['title'] = value[2];
                team_object['name'] = value[0];
                team_object['img'] = value[1];
                team_object['description'] = value[5];
             
                wrap_team_object.push(team_object);
            }

            console.log(wrap_team_object);

            //JavaScript

            var chart = new OrgChart(document.getElementById("tree"), {
                template: 'polina',    
                mouseScrool: OrgChart.none,
                layout: OrgChart.mixed,
                enableSearch: false,
                nodeMouseClick: OrgChart.action.none,
                nodeBinding: {
                    img_0: "img",
                    field_0: "name",
                    field_1: "title"
                }
            });

            chart.load(wrap_team_object);

            chart.on('click', function(sender, args){ 
                var data = sender.get(args.node.id);
                console.log(data.name);
                $("#team").modal('show');
                                $("#modalImage").attr('src', '');
                                $("#modalName").html('');
                                $("#titleModalPengurus").html('');
                                $("#descriptionModal").html('');
                                $("#modalImage").attr('src', data.img);
                                $("#modalName").html(data.name);
                                $("#titleModalPengurus").html(data.title);
                                if(data.description == '') {
                                    $("#descriptionModal").html('-');
                                } else {
                                    $("#descriptionModal").html(data.description);
                                }
            });   
            }
        });
    }

</script>
<script>


</script>
<script type="text/javascript">
    var page = 1;

    function loadContent() {
        page++;
        loadMoreData(page);
    }


    function loadMoreData(page) {
        $.ajax({
                url: '?page=' + page,
                type: "get",
                beforeSend: function () {
                    $('.ajax-load').show();
                }
            })
            .done(function (data) {
                if (data.html == " ") {
                    $('.ajax-load').html("No more records found");
                    return;
                }
                $('.ajax-load').hide();
                $("#loadProject").append(data.html);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('server not responding...');
            });
    }

</script>
@endsection