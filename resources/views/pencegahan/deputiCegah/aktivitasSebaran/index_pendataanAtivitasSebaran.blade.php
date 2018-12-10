@extends('layouts.base_layout')
@section('title', 'Data Aktivitas Sebaran')

@section('content')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#searchButton').click(function () {
            $('.formSearch').slideToggle("slow");
            // Alternative animation for example
            // slideToggle("fast");
        });
    });

//    $(document).ready(function () {
//        $('#datatableCegah').DataTable();
//    });
</script>
<div class="right_col" role="main">
    <div class="m-t-40">
        <div class="page-title">
            <div class="">
                {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
            </div>
        </div>        

        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Data Aktivitas Sebaran<small></small></h2>                        
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="">
                                <a href="#" class="btn btn-lg btn-round btn-primary" target="_blank" onclick="printDiv('printableArea')">
                                    <i class="fa fa-print"></i> Print Out
                                </a>
                            </li>                            
                            <li class="">
                                <a href="{{URL('/pencegahan/dep_cegah/newpdfaktivitassebaran?'.$forprint)}}" class="btn btn-lg btn-round btn-danger">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                            </li>
                            <li class="">
                                <a href="{{URL('/pencegahan/dep_cegah/newexcelaktivitassebaran?'.$forprint)}}" class="btn btn-lg btn-round btn-success">
                                    <i class="fa fa-file-excel-o"></i> Excel
                                </a>
                            </li>
                            <!--li class="">
                                <a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_report_excel" onClick="reportExcel(event, this)" data-url="{{URL('/pencegahan/dep_cegah/pdfaktivitassebaran')}}">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                            </li-->
                            <!--li class="">
                                <a href="#" class="btn btn-lg btn-round btn-success" data-toggle="modal" data-target="#modal_report_excel" onClick="reportExcel(event, this)" data-url="{{URL('/pencegahan/dep_cegah/printaktivitassebaran')}}">
                                    <i class="fa fa-file-excel-o"></i> Excel
                                </a>
                            </li-->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <ul class="nav navbar-left panel_toolbox">
                        <li class="">
                            <a class="btn btn-lg btn-round btn-warning" id="searchButton" onchange="formFilter(this)">
                                <i class="fa fa-search"></i> Search By
                            </a>
                        </li>
                    </ul>
                    <div class="x_content ">
                        @if(session('status'))
                        @php
                        $session= session('status');
                        @endphp
                        <div class="alert alert-{{$session['status']}}">
                            {{ $session['message'] }}
                        </div>
                        @endif                        
                        <!--div style="overflow-x:auto;"-->
                        @include('_templateFilter.cegah_aktivitas_sebaran')
                        <div id="printableArea" class="tableprint">
                            <div id="tabelact">
                            <table id="datatableCegah" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pembuat</th>
                                        <th>Satker</th>
                                        <th>Wilayah</th>
                                        <th style="width: 10%;">Tanggal</th>
                                        <th>Media</th>
                                        <th>Paket</th>
                                        <th>Anggaran</th>
                                        <th>Sasaran</th>
                                        <th>Jml Sebaran</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data_aktivitas))
                                    @php $i = $start_number; @endphp
                                    @foreach($data_aktivitas as $row)
                                    <tr>
                                        <td> {{$i}}</td>
                                        <td> {{$row['userName']}}</td>
                                        <td> {{$row['userSatker']}}</td>
                                        <td> {{$row['userWilayah']}}</td>                                            
                                        <td> {{date_format(date_create($row['tglact']), "d-m-Y")}}</td>
                                        <td> {{$row['nmmedia']}}</td>
                                        <td> {{$row['paket']}}</td>
                                        <td> {{$row['anggaran']}}</td>
                                        <td> {{$row['sasaran']}}</td>
                                        <td> {{$row['jmlsebar']}}</td>
                                        <td> {{$row['actstat']}}</td>
                                    </tr>
                                    @php $i = $i+1; @endphp
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="alert-messages alert-warning">
                                                @if(isset($filter))
                                                @if(count($filter) >0)
                                                Data Aktivitas Sebaran Yang Anda Cari Tidak Tersedia.
                                                @else
                                                Data Aktivitas Sebaran Tidak Tersedia.
                                                @endif
                                                @else
                                                Data Aktivitas Sebaran Tidak Tersedia.
                                                @endif
                                            </div>
                                        </td>
                                    </tr>              
                                    @endif
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <!--/div-->
                        @if(count($data_aktivitas) > 0)
                        {!! $pagination !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function printDiv(divName) {        
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;        
//        document.body.innerHTML = printContents;
//        alert("Sucsess print Out");
        var w = window.open();
        w.document.write($('#tabelact').html()); //only part of the page to print, using jquery
        w.document.close(); //this seems to be the thing doing the trick
//        w.focus();
        w.print();
        w.close();
//        window.open();
//        wndow.focus();        
//        window.print();
//        setTimeout(function () { window.close(); }, 100);
//        w.close();
//        window.close();
//        document.body.innerHTML = originalContents;
//        alert("Sucsess print Out");
//alert('success print out');
    }
</script>

@include('modal.modal_delete_form')
@include('modal.modal_report_excel_aktivitas_sebaran')
@endsection