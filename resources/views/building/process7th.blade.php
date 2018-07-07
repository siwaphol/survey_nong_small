@extends('LayOut')

@section('content')
<div class="panel panel-body">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="#">Home</a></li>
	  <li class="breadcrumb-item"><a href="#">อาคาร</a></li>
		<li class="breadcrumb-item"><a href="#">{{$data->code}}</a></li>
	  <li class="breadcrumb-item active">การดำเนินการอนุรักษ์พลังงานในอดีตและในอนาคต</li>
	</ol>
</div>

@include('partials._breadcrumb_steps',['progress_type'=> \App\Main::PROGRESS_BUILDING,'progressNo'=>7])

<div class="panel panel-body">
	<h4><span class="bg-danger rounded sq-28"><span class="icon icon-building"></span></span> การดำเนินการอนุรักษ์พลังงานในอดีตและในอนาคต</h4>
	<hr/>
	<div class="row">
		<!-- Form Section -->
		@if(isset($edit))
			{!! Form::open(['url' => '/building/process7/'.$doc_number,'id' => 'p9','method' => 'put','class' => 'form-horizontal'])!!}
		@else
			{!! Form::open(['url' => '/building/process7','id' => 'p9','method' => 'post','class' => 'form-horizontal'])!!}
		@endif
		{!! Form::hidden("doc_number", $doc_number,[])!!}
		<div class="col-xs-6">
			<h4><span class="bg-danger rounded sq-28"><span class="icon icon-bolt"></span></span> มาตราการอนุรักษ์พลังงานในอดีต</h4>
		</div>
		<div class="col-xs-3">
            <a href="{{ url('/plan')}}" class="btn btn-sm btn-info " type="button" target="_blank"><span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มชนิดมาตราการใหม่</a>
        </div>
		<div class="col-xs-3">
			<button class="btn btn-sm btn-info pull-right " type="button" id="add_past_conserve"><span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มมาตราการ</button>
		</div>
		<div class="col-xs-12">
			<div class="card">
				<div class="card-body">
					<table class="table table-bordered table-hover" id="past_conserve_table">
						<thead>
						<tr>
							<th rowspan="3"><center>มาตรการ</center></th>
							<th colspan="5"><center>การใช้พลังงานก่อนการปรับปรุง</center></th>
							<th colspan="5"><center>ผลประหยัด</center></th>
							<th colspan="2"><center>การลงทุน</center></th>
							<th rowspan="3"><center>ตัวเลือก</center></th>
						</tr>
						<tr>
							<th colspan="3"><center>ไฟฟ้า</center></th>
							<th colspan="2"><center>เชื้อเพลิง</center></th>
							<th colspan="3"><center>ไฟฟ้า</center></th>
							<th colspan="2"><center>เชื้อเพลิง</center></th>
							<th rowspan="2"><center>เงินลงทุน<br/>(บาท)</center></th>
							<th rowspan="2"><center>เวลาคืนทุน<br/>(บาท)</center></th>
						</tr>
						<tr>
							<th width="7%"><center>kW</center></th>
							<th width="7%"><center>kWh/ปี</center></th>
							<th width="7%"><center>บาท*/ปี</center></th>
							<th width="7%"><center>กก./ปี</center></th>
							<th width="7%"><center>บาท**/ปี</center></th>
							<th width="7%"><center>kW</center></th>
							<th width="7%"><center>kWh/ปี</center></th>
							<th width="7%"><center>บาท*/ปี</center></th>
							<th width="7%"><center>กก./ปี</center></th>
							<th width="7%"><center>บาท**/ปี</center></th>
						</tr>
						</thead>
						<tbody>
						@if(isset($edit))
							@foreach($SEP as $past_data)
								@if($past_data->timing_plan=="past")
									@if((count($past_data->plan)>0))
									{!! Form::hidden("SEP_id[]", $past_data->id,[])!!}
									<tr>
										<td>
											{!! Form::select("past_conserve[measure][]",$plan, $past_data->plan, ["class"=>"md-form-control select","spellcheck"=>"false"]) !!}
											{{--<select id="demo-select2-1" class="form-control" name="past_conserve[measure][]" $past_data->plan></select>--}}
										</td>
										<td>
											{!! Form::number("past_conserve[before_elec_kw][]", $past_data->electric_power_bf, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_elec_kwh_per_year][]", $past_data->kwh_yr_bf, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_elec_cost_per_year][]", $past_data->bath_yr_bf, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_fuel_kg_per_year][]", $past_data->fuel_kg_yr_bf, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_fuel_cost_per_year][]", $past_data->fuel_bath_yr_bf, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_elec_kw][]", $past_data->electric_power_af, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_elec_kwh_per_year][]", $past_data->kwh_yr_af, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_elec_cost_per_year][]", $past_data->bath_yr_af, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_fuel_kg_per_year][]", $past_data->fuel_kg_yr_af, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_fuel_cost_per_year][]", $past_data->fuel_bath_yr_af, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[investment][]", $past_data->investment, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[payback][]", $past_data->payback_time, ["class"=>"md-form-control ","spellcheck"=>"false", "step"=>"any"]) !!}
										</td>
										<td class="demo-icons">
											<a class="past icon icon-close " title="ลบ"></a>
										</td>
									</tr>
									@else
									<tr>
										<td>
											{!! Form::select("past_conserve[measure][]",$plan ,null, ["class"=>"md-form-control select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_elec_kw][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_elec_kwh_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_elec_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_fuel_kg_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[before_fuel_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_elec_kw][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_elec_kwh_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_elec_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_fuel_kg_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[after_fuel_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[investment][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("past_conserve[payback][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td class="demo-icons">
											<a class="past icon icon-close " title="ลบ"></a>
										</td>
									</tr>
									@endif
								@endif
							@endforeach
						@else
							<tr>
								<td>
									{!! Form::select("past_conserve[measure][]",$plan ,null, ["class"=>"md-form-control select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[before_elec_kw][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[before_elec_kwh_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[before_elec_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[before_fuel_kg_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[before_fuel_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[after_elec_kw][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[after_elec_kwh_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[after_elec_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[after_fuel_kg_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[after_fuel_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[investment][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("past_conserve[payback][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td class="demo-icons">
									<a class="past icon icon-close " title="ลบ"></a>
								</td>
							</tr>
						@endif

						</tbody>
					</table>
					<div>
						<h5>หมายเหตุ: 	* อัตราค่าไฟฟ้าเฉลี่ย , ** อัตราค่าเชื้อเพลิง</h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<h4><span class="bg-danger rounded sq-28"><span class="icon icon-bolt"></span></span> มาตราการอนุรักษ์พลังงานในอนาคต</h4>
		</div>
		<div class="col-xs-6">
			<button class="btn btn-sm btn-info pull-right " type="button" id="add_future_conserve"><span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มมาตราการ</button>
		</div>
		<div class="col-xs-12">
			<div class="card">
				<div class="card-body">
					<table class="table table-bordered table-hover" id="future_conserve_table">
						<thead>
						<tr>
							<th rowspan="3"><center>มาตรการ</center></th>
							<th colspan="5"><center>การใช้พลังงานก่อนการปรับปรุง</center></th>
							<th colspan="5"><center>ผลประหยัด</center></th>
							<th colspan="2"><center>การลงทุน</center></th>
							<th rowspan="3"><center>ตัวเลือก</center></th>
						</tr>
						<tr>
							<th colspan="3"><center>ไฟฟ้า</center></th>
							<th colspan="2"><center>เชื้อเพลิง</center></th>
							<th colspan="3"><center>ไฟฟ้า</center></th>
							<th colspan="2"><center>เชื้อเพลิง</center></th>
							<th rowspan="2"><center>เงินลงทุน<br/>(บาท)</center></th>
							<th rowspan="2"><center>เวลาคืนทุน<br/>(บาท)</center></th>
						</tr>
						<tr>
							<th width="7%"><center>kW</center></th>
							<th width="7%"><center>kWh/ปี</center></th>
							<th width="7%"><center>บาท*/ปี</center></th>
							<th width="7%"><center>กก./ปี</center></th>
							<th width="7%"><center>บาท**/ปี</center></th>
							<th width="7%"><center>kW</center></th>
							<th width="7%"><center>kWh/ปี</center></th>
							<th width="7%"><center>บาท*/ปี</center></th>
							<th width="7%"><center>กก./ปี</center></th>
							<th width="7%"><center>บาท**/ปี</center></th>
						</tr>
						</thead>
						<tbody>
						@if(isset($edit))
							@foreach($SEP as $future_data)
								@if($future_data->timing_plan=="future")
									{!! Form::hidden("SEP_id[]", $future_data->id,[])!!}
									<tr>
										<td>
											{!! Form::select("future_conserve[measure][]",$plan, $future_data->plan, ["class"=>"md-form-control select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[before_elec_kw][]", $future_data->electric_power_bf, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[before_elec_kwh_per_year][]", $future_data->kwh_yr_bf, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[before_elec_cost_per_year][]", $future_data->bath_yr_bf, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[before_fuel_kg_per_year][]", $future_data->fuel_kg_yr_bf, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[before_fuel_cost_per_year][]", $future_data->fuel_bath_yr_bf, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[after_elec_kw][]", $future_data->electric_power_af, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[after_elec_kwh_per_year][]", $future_data->kwh_yr_af, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[after_elec_cost_per_year][]", $future_data->bath_yr_af, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[after_fuel_kg_per_year][]", $future_data->fuel_kg_yr_af, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[after_fuel_cost_per_year][]", $future_data->fuel_bath_yr_af, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[investment][]", $future_data->investment, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td>
											{!! Form::number("future_conserve[payback][]", $future_data->payback_time, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
										</td>
										<td class="demo-icons">
											<a class="future icon icon-close " title="ลบ"></a>
										</td>
									</tr>
								@else
								@endif
							@endforeach
						@else
							<tr>
								<td>
									{!! Form::select("future_conserve[measure][]",$plan, null, ["class"=>"md-form-control select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[before_elec_kw][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[before_elec_kwh_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[before_elec_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[before_fuel_kg_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[before_fuel_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[after_elec_kw][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[after_elec_kwh_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[after_elec_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[after_fuel_kg_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[after_fuel_cost_per_year][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[investment][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td>
									{!! Form::number("future_conserve[payback][]", null, ["class"=>"md-form-control ","spellcheck"=>"false","step"=>"any"]) !!}
								</td>
								<td class="demo-icons">
									<a class="future icon icon-close " title="ลบ"></a>
								</td>
							</tr>
						@endif

						</tbody>
					</table>
					<div>
						<h5>หมายเหตุ: 	* อัตราค่าไฟฟ้าเฉลี่ย , ** อัตราค่าเชื้อเพลิง</h5>
					</div>
				</div>
			</div>
		</div>
		<input type="submit" value="บันทึกข้อมูล" class="btn btn-primary btn-block ">
		{{--<button type="submit" class="btn btn-primary btn-block">บันทึกข้อมูล</button>--}}
	{!! Form::close()!!}
	<!-- ******************************************* -->
	</div>

</div>

@endsection


@section('script')
	<script type="text/javascript" src="{{ asset('custom_assets/js/building/process7th.js')}}"></script>
@stop