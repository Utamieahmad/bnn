@extends('layouts.base_layout')
@section('title', ' Omspan')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : '') !!}
				</div>
			</div>


			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Indikator Pelaksanaan Anggaran</h2>
						</div>
						<div class="x_content">
							@include('_templateFilter.omspan_filter')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection