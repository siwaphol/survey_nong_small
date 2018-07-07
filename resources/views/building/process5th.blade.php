@extends('LayOut')

@section('content')
<div class="panel panel-body">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="#">Home</a></li>
	  <li class="breadcrumb-item"><a href="#">อาคาร</a></li>
		<li class="breadcrumb-item"><a href="#">{{$data->code}}</a></li>
	  <li class="breadcrumb-item active">การใช้เชื้อเพลิงและพลังงานหมุนเวียน ในรอบปี</li>
	</ol>
</div>

@include('partials._breadcrumb_steps',['progress_type'=> \App\Main::PROGRESS_BUILDING,'progressNo'=>5])

<div class="panel panel-body">

	<div class="row">
		@if($action == "edit")
			<div class="col-xs-6">
				@else
					<div class="col-xs-12">
						@endif
						<h4><span class="bg-danger rounded sq-28"><span class="icon icon-industry"></span></span> การใช้เชื้อเพลิงและพลังงานหมุนเวียน ในรอบปี</h4>
					</div>
					@if($action == "edit")
						<div class="col-xs-6">
							<h4><a href="{{ url('building/process5') }}/{{$doc_number}}" class="btn btn-sm btn-info pull-right " type="button"><span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มข้อมูล</a></h4>
						</div>
					@endif
			</div>


			<!-- Form Section -->
			@if($action == "edit")
				{!! Form::open(['url' => '/building/process5/'.$doc_number,'id' => 'p4','method' => 'put','class' => 'form-horizontal', 'data-toggle' => 'md-validator', 'novalidate' => 'novalidate'])!!}
			@else
				{!! Form::open(['url' => '/building/process5','id' => 'p4','method' => 'post','class' => 'form-horizontal', 'novalidate' => 'novalidate'])!!}
			@endif

			{!! Form::hidden("doc_number", $doc_number,[])!!}

			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#menu1">การใช้เชื้อเพลิง</a></li>
				<li><a data-toggle="tab" href="#menu2">การใช้เพลังงานหมุนเวียน</a></li>
			</ul>

			<div class="tab-content">
				<div id="menu1" class="tab-pane fade in active">

					<div id="example">
						<component is="my-component"></component>
					</div>

				</div>

				<div id="menu2" class="tab-pane fade">

					<div id="example2">
						<component is="my-component2"></component>
					</div>

				</div>
			</div>

			<button type="button" class="btn btn-primary btn-block " id="save">บันทึกข้อมูล</button>
		{!! Form::close()!!}
		<!-- ******************************************* -->
	</div>
</div>

<template id="insert-template">
	<div class="row">
		@if($action != "edit")
			<div class="col-xs-6">
				@else
					<div class="col-xs-6">
						@endif
						<h4><span class="bg-danger rounded sq-24"><span class=" icon icon-fire"></span></span> เชื้อเพลิงสิ้นเปลือง</h4>
					</div>
					@if($action != "edit")
						<div class="col-xs-6">
							<h4><button class="btn btn-sm btn-info pull-right" type="button" v-on:click="addEnergy"><span class="icon icon-plus-square-o icon-lg icon-fw "></span> เพิ่มเชื้อเพลิง</button></h4>
						</div>
					@else
						<div class="col-xs-6">
							<h4><button class="btn btn-sm btn-info pull-right" type="button" v-on:click="addEnergy"><span class="icon icon-plus-square-o icon-lg icon-fw "></span> เพิ่มเชื้อเพลิง</button></h4>
						</div>
					@endif
			</div>
			@if($action == "edit")
				@foreach($EAJ as $energy)
					@if(count($energy) > 0 && $energy->energy_types{0}->type == "พลังงานสิ้นเปลือง")
						<div class="card energy-template">
							<div class="card-body">
								<div class="row">
									<div class="col-xs-6">
										<div class="md-form-group md-label-floating">
											{!! Form::select("energy_name[]", $energy4select, $energy->energy_types{0}->id,  [ 'class' => 'md-form-control text-red','required' => '', 'aria-required' => 'true','disabled']) !!}
											<small class="md-help-block">ชนิดพลังงาน</small>
										</div>
									</div>
									{!! Form::hidden("energy_name[]", $energy->energy_types{0}->id, ["class"=>"md-form-control energy-name"]) !!}
									<div class="col-xs-6">
										<div class="md-form-group md-label-floating">
											{!! Form::text("e_heat[]", $energy->energy_types{0}->heat_rate != '' ? $energy->energy_types{0}->heat_rate : 'ไม่มี' , ['class' => 'md-form-control text-red energy-heat','required' => '', 'aria-required' => 'true','readonly']) !!}
											<small class="md-help-block">ค่าความร้อนเฉลี่ย (MJ/หน่วย)</small>
										</div>
									</div>
									{!! Form::hidden("er_id[]", $energy->id, ["class"=>"md-form-control er-id"]) !!}
									<div class="col-md-12">
										<table class="table table-bordered table-hover" id="energy-table">
											<thead>
											<tr>
												<th class="text-center col-md-1" rowspan="2">ปี</th>
												<th class="text-center col-md-2" rowspan="2"><center>ปริมาณการใช้<br/>ต่อเดือน</center></th>
												<th class="text-center" colspan="4"><center>เชื้อเพลิงสิ้นเปลือง</center></th>
												<th class="text-center col-md-1" rowspan="2"><center>ตัวเลือก</center></th>
											</tr>
											<tr>
												<th class="text-center col-md-2"><center><div class="text-red">{{$energy->energy_types{0}->unit}}</div></center></th>
												<th class="text-center col-md-2"><center>บาท/หน่วย</center></th>
												<th class="text-center col-md-2"><center>เป็นเงิน (บาท)</center></th>
												<th class="text-center col-md-2"><center>เป็นพลังงาน (MJ)</center></th>
											</tr>
											</thead>
											<tbody class="tbody">
											@if(count($energy->energy_used_per_years) > 0)
												@foreach($energy->energy_used_per_years as $data)
													{!! Form::hidden("e_id[]", $data->id, ["class"=>"md-form-control e-id"]) !!}
													<tr class="clone">
														<td>
															{!! Form::number("e_year[]", $data->year, ["class"=>"md-form-control year"]) !!}
														</td>
														<td>
															{!! Form::select("e_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'), $data->month, ["class"=>"md-form-control month"]) !!}
														</td>
														<td>
															{!! Form::number("e_unit[]", $data->unit, ["class"=>"md-form-control unit",'onfocusout' => 'e_total(this)']) !!}
														</td>
														<td>
															{!! Form::number("e_cost_unit[]", $data->cost_unit, ["class"=>"md-form-control cost-unit",'onfocusout' => 'e_total(this)']) !!}
														</td>
														<td>
															{!! Form::number("e_total_cost[]", $data->total_cost, ["class"=>"md-form-control total-cost",'readonly']) !!}
														</td>
														<td>
															{!! Form::number("e_mj[]", $data->mj, ["class"=>"md-form-control mj",'readonly']) !!}
														</td>
														<td class="demo-icons">
															<a class="electric icon icon-close " title="ลบ" data-id="{{$data->id}}"></a>
														</td>
													</tr>
												@endforeach
											@else
												<tr class="clone">
													<td>
														{!! Form::number("e_year[]", null, ["class"=>"md-form-control year"]) !!}
													</td>
													<td>
														{!! Form::select("e_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'), null, ["class"=>"md-form-control month"]) !!}
													</td>
													<td>
														{!! Form::number("e_unit[]", null, ["class"=>"md-form-control unit",'onfocusout' => 'e_total(this)']) !!}
													</td>
													<td>
														{!! Form::number("e_cost_unit[]", null, ["class"=>"md-form-control cost-unit",'onfocusout' => 'e_total(this)']) !!}
													</td>
													<td>
														{!! Form::number("e_total_cost[]", null, ["class"=>"md-form-control total-cost",'readonly']) !!}
													</td>
													<td>
														{!! Form::number("e_mj[]", null, ["class"=>"md-form-control mj",'readonly']) !!}
													</td>
													<td class="demo-icons">
														<a class="electric icon icon-close " title="ลบ"></a>
													</td>
												</tr>
											@endif
											</tbody>
										</table>
									</div>
									<div class="col-md-12">
										<div class="md-form-group md-label-floating">
											<button type="button" class="btn btn-sm btn-info add-month ">
												<span class="icon icon-plus-square-o icon-lg icon-fw"></span>
												เพิ่มเดือน
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					@else
						<div v-for="energy in energys">
							<div class="card energy-template">
								<div class="card-body">
									<div class="row">
										<div class="col-xs-6">
											<div class="md-form-group md-label-floating">
												{!! Form::select("energy_name[]", $energy4select, 0, ['id' => 'energy_name_@{{energy.id}}', 'class' => 'md-form-control energy-name', 'aria-required' => 'true','onchange' => 'energy_change(@{{energy.id}})']) !!}
												<small class="md-help-block">ชนิดพลังงาน</small>
											</div>
										</div>
										<div class="col-xs-6">
											<div class="md-form-group md-label-floating">
												{!! Form::text("e_heat[]", null, ['id' => 'energy_heat_@{{energy.id}}', 'class' => 'md-form-control text-red energy-heat', 'aria-required' => 'true','readonly']) !!}
												<small class="md-help-block">ค่าความร้อนเฉลี่ย (MJ/หน่วย)</small>
											</div>
										</div>
										<div class="col-md-12">
											<table class="table table-bordered table-hover" id="table_@{{energy.id}}">
												<thead>
												<tr>
													<th class="text-center col-md-1" rowspan="2">ปี</th>
													<th class="text-center col-md-2" rowspan="2"><center>ปริมาณการใช้<br/>ต่อเดือน</center></th>
													<th class="text-center" colspan="4"><center>เชื้อเพลิงสิ้นเปลือง</center></th>
													<th class="text-center col-md-1" rowspan="2"><center>ตัวเลือก</center></th>
												</tr>
												<tr>
													<th class="text-center col-md-2"><center><div class="text-red" id="energy_unit_@{{energy.id}}"></div></center></th>
													<th class="text-center col-md-2"><center>บาท/หน่วย</center></th>
													<th class="text-center col-md-2"><center>เป็นเงิน (บาท)</center></th>
													<th class="text-center col-md-2"><center>เป็นพลังงาน (MJ)</center></th>
												</tr>
												</thead>
												<tbody>
												<tr v-for="month in energy.months" track-by="$index">
													<td>
														{!! Form::number("e_year[]", null, ["class"=>"md-form-control year"]) !!}
													</td>
													<td>
														{!! Form::select("e_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'),null, ["class"=>"md-form-control month"]) !!}
													</td>
													<td>
														{!! Form::number("e_unit[]", null, ["class"=>"md-form-control unit",'onfocusout' => 'e_total(this)']) !!}
													</td>
													<td>
														{!! Form::number("e_cost_unit[]", null, ["class"=>"md-form-control cost-unit",'onfocusout' => 'e_total(this)']) !!}
													</td>
													<td>
														{!! Form::number("e_total_cost[]", null, ["class"=>"md-form-control total-cost",'readonly']) !!}
													</td>
													<td>
														{!! Form::number("e_mj[]", null, ["class"=>"md-form-control mj",'readonly']) !!}
													</td>
													<td class="demo-icons">
														<a class="electric icon icon-sm icon-close icon-fw " data-pid="@{{energy.id}}" v-on:click="deleteMonth(energy.id,month)" title="ลบ"></a>
													</td>
												</tr>
												</tbody>
											</table>
										</div>
										<div class="col-md-12">
											<div class="md-form-group md-label-floating">
												<button type="button" class="btn btn-sm btn-info " v-on:click="addMonth(energy.id)">
													<span class="icon icon-plus-square-o icon-lg icon-fw"></span>
													เพิ่มเดือน
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endif
				@endforeach
			@else
				<div v-for="energy in energys">
					<div class="card energy-template">
						<div class="card-body">
							<div class="row">
								<div class="col-xs-6">
									<div class="md-form-group md-label-floating">
										{!! Form::select("energy_name[]", $energy4select, 0, ['id' => 'energy_name_@{{energy.id}}', 'class' => 'md-form-control energy-name','required' => '', 'aria-required' => 'true','onchange' => 'energy_change(@{{energy.id}})']) !!}
										<small class="md-help-block">ชนิดพลังงาน</small>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="md-form-group md-label-floating">
										{!! Form::text("e_heat[]", null, ['id' => 'energy_heat_@{{energy.id}}', 'class' => 'md-form-control text-red energy-heat','required' => '', 'aria-required' => 'true','readonly']) !!}
										<small class="md-help-block">ค่าความร้อนเฉลี่ย (MJ/หน่วย)</small>
									</div>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered table-hover" id="table_@{{energy.id}}">
										<thead>
										<tr>
											<th class="text-center col-md-1" rowspan="2">ปี</th>
											<th class="text-center col-md-2" rowspan="2"><center>ปริมาณการใช้<br/>ต่อเดือน</center></th>
											<th class="text-center" colspan="4"><center>เชื้อเพลิงสิ้นเปลือง</center></th>
											<th class="text-center col-md-1" rowspan="2"><center>ตัวเลือก</center></th>
										</tr>
										<tr>
											<th class="text-center col-md-2"><center><div class="text-red" id="energy_unit_@{{energy.id}}"></div></center></th>
											<th class="text-center col-md-2"><center>บาท/หน่วย</center></th>
											<th class="text-center col-md-2"><center>เป็นเงิน (บาท)</center></th>
											<th class="text-center col-md-2"><center>เป็นพลังงาน (MJ)</center></th>
										</tr>
										</thead>
										<tbody>
										<tr v-for="month in energy.months" track-by="$index">
											<td>
												{!! Form::number("e_year[]", null, ["class"=>"md-form-control year"]) !!}
											</td>
											<td>
												{!! Form::select("e_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'),null, ["class"=>"md-form-control month"]) !!}
											</td>
											<td>
												{!! Form::number("e_unit[]", null, ["class"=>"md-form-control unit",'onfocusout' => 'e_total(this)']) !!}
											</td>
											<td>
												{!! Form::number("e_cost_unit[]", null, ["class"=>"md-form-control cost-unit",'onfocusout' => 'e_total(this)']) !!}
											</td>
											<td>
												{!! Form::number("e_total_cost[]", null, ["class"=>"md-form-control total-cost",'readonly']) !!}
											</td>
											<td>
												{!! Form::number("e_mj[]", null, ["class"=>"md-form-control mj",'readonly']) !!}
											</td>
											<td class="demo-icons">
												<a class="electric icon icon-sm icon-close icon-fw " data-pid="@{{energy.id}}" v-on:click="deleteMonth(energy.id,month)" title="ลบ"></a>
											</td>
										</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-12">
									<div class="md-form-group md-label-floating">
										<button type="button" class="btn btn-sm btn-info " v-on:click="addMonth(energy.id)">
											<span class="icon icon-plus-square-o icon-lg icon-fw"></span>
											เพิ่มเดือน
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
	@endif
</template>

<template id="insert-template2">
	<div class="row">
		@if($action != "edit")
			<div class="col-xs-6">
				@else
					<div class="col-xs-6">
						@endif
						<h4><span class="bg-danger rounded sq-24"><span class=" icon icon-recycle"></span></span> พลังงานหมุนเวียน</h4>
					</div>
					@if($action != "edit")
						<div class="col-xs-6">
							<h4><button class="btn btn-sm btn-info pull-right " type="button" v-on:click="addRecycle"><span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มพลังงาน</button></h4>
						</div>
					@else
						<div class="col-xs-6">
							<h4><button class="btn btn-sm btn-info pull-right " type="button" v-on:click="addRecycle"><span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มพลังงาน</button></h4>
						</div>
					@endif
			</div>
			@if($action == "edit")
				@foreach($EAJ as $recycle)
					@if((count($recycle) > 0) && ($recycle->energy_types{0}->type == "พลังงานหมุนเวียน"))
						<div class="card recycle-template">
							<div class="card-body">
								<div class="row">
									<div class="col-xs-6">
										<div class="md-form-group md-label-floating">
											{!! Form::select("recycle_name[]", $recycle4select, $recycle->energy_types{0}->id, [ 'class' => 'md-form-control text-red','required' => '', 'aria-required' => 'true','disabled']) !!}
											<small class="md-help-block">ชนิดพลังงาน</small>
										</div>
									</div>
									{!! Form::hidden("recycle_name[]", $recycle->energy_types{0}->id, ["class"=>"md-form-control recycle-name"]) !!}
									<div class="col-xs-6">
										<div class="md-form-group md-label-floating">
											{!! Form::text("r_heat[]", $recycle->energy_types{0}->heat_rate, ['class' => 'md-form-control text-red recycle-heat','required' => '', 'aria-required' => 'true','readonly']) !!}
											<small class="md-help-block">ค่าความร้อนเฉลี่ย (MJ/หน่วย)</small>
										</div>
									</div>
									{!! Form::hidden("er_id[]", $recycle->id, ["class"=>"md-form-control er-id"]) !!}
									<div class="col-md-12">
										<table class="table table-bordered table-hover">
											<thead>
											<tr>
												<th class="text-center col-md-1" rowspan="2">ปี</th>
												<th class="text-center col-md-2" rowspan="2"><center>ปริมาณการใช้<br/>ต่อเดือน</center></th>
												<th class="text-center" colspan="4"><center>พลังงานหมุนเวียน</center></th>
												<th class="text-center col-md-1" rowspan="2"><center>ตัวเลือก</center></th>
											</tr>
											<tr>
												<th class="text-center col-md-2"><center><div class="text-red">{{$recycle->energy_types{0}->unit}}</div></center></th>
												<th class="text-center col-md-2"><center>บาท/หน่วย</center></th>
												<th class="text-center col-md-2"><center>เป็นเงิน (บาท)</center></th>
												<th class="text-center col-md-2"><center>เป็นพลังงาน (MJ)</center></th>
											</tr>
											</thead>
											<tbody>
											@if(count($recycle->energy_used_per_years) > 0)
												@foreach($recycle->energy_used_per_years as $data)
													{!! Form::hidden("r_id[]", $data->id, ["class"=>"md-form-control r-id"]) !!}
													<tr class="clone">
														<td>
															{!! Form::number("r_year[]", $data->year, ["class"=>"md-form-control year"]) !!}
														</td>
														<td>
															{!! Form::select("r_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'), $data->month, ["class"=>"md-form-control month"]) !!}
														</td>
														<td>
															{!! Form::number("r_unit[]", $data->unit, ["class"=>"md-form-control unit",'onfocusout' => 'r_total(this)']) !!}
														</td>
														<td>
															{!! Form::number("r_cost_unit[]", $data->cost_unit, ["class"=>"md-form-control cost-unit",'onfocusout' => 'r_total(this)']) !!}
														</td>
														<td>
															{!! Form::number("r_total_cost[]", $data->total_cost, ["class"=>"md-form-control total-cost",'readonly']) !!}
														</td>
														<td>
															{!! Form::number("r_mj[]", $data->mj, ["class"=>"md-form-control mj",'readonly']) !!}
														</td>
														<td class="demo-icons">
															<a class="electric icon icon-close " title="ลบ" data-id="{{$data->id}}"></a>
														</td>
													</tr>
												@endforeach
											@else
												<tr class="clone">
													<td>
														{!! Form::number("r_year[]", null, ["class"=>"md-form-control year"]) !!}
													</td>
													<td>
														{!! Form::select("r_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'), null, ["class"=>"md-form-control month"]) !!}
													</td>
													<td>
														{!! Form::number("r_unit[]", null, ["class"=>"md-form-control unit",'onfocusout' => 'r_total(this)']) !!}
													</td>
													<td>
														{!! Form::number("r_cost_unit[]", null, ["class"=>"md-form-control cost-unit",'onfocusout' => 'r_total(this)']) !!}
													</td>
													<td>
														{!! Form::number("r_total_cost[]", null, ["class"=>"md-form-control total-cost",'readonly']) !!}
													</td>
													<td>
														{!! Form::number("r_mj[]", null, ["class"=>"md-form-control mj",'readonly']) !!}
													</td>
													<td class="demo-icons">
														<a class="electric icon icon-close " title="ลบ"></a>
													</td>
												</tr>
											@endif
											</tbody>
										</table>
									</div>
									<div class="col-md-12">
										<div class="md-form-group md-label-floating">
											<button type="button" class="btn btn-sm btn-info add-month">
												<span class="icon icon-plus-square-o icon-lg icon-fw "></span>
												เพิ่มเดือน
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					@else
						<div v-for="recycle in recycles">
							<div class="card recycle-template">
								<div class="card-body">
									<div class="row">
										<div class="col-xs-6">
											<div class="md-form-group md-label-floating">
												{!! Form::select("recycle_name[]", $recycle4select, 0, ['id' => 'recycle_name_@{{recycle.id}}', 'class' => 'md-form-control recycle-name','required' => '', 'aria-required' => 'true','onchange' => 'recycle_change(@{{recycle.id}})']) !!}
												<small class="md-help-block">ชนิดพลังงาน</small>
											</div>
										</div>
										<div class="col-xs-6">
											<div class="md-form-group md-label-floating">
												{!! Form::text("r_heat[]", null, ['id' => 'recycle_heat_@{{recycle.id}}', 'class' => 'md-form-control text-red recycle-heat','required' => '', 'aria-required' => 'true','readonly']) !!}
												<small class="md-help-block">ค่าความร้อนเฉลี่ย (MJ/หน่วย)</small>
											</div>
										</div>
										<div class="col-md-12">
											<table class="table table-bordered table-hover" id="table_@{{recycle.id}}">
												<thead>
												<tr>
													<th class="text-center col-md-1" rowspan="2">ปี</th>
													<th class="text-center col-md-2" rowspan="2"><center>ปริมาณการใช้<br/>ต่อเดือน</center></th>
													<th class="text-center" colspan="4"><center>พลังงานหมุนเวียน</center></th>
													<th class="text-center col-md-1" rowspan="2"><center>ตัวเลือก</center></th>
												</tr>
												<tr>
													<th class="text-center col-md-2"><center><div class="text-red" id="recycle_unit_@{{recycle.id}}"></div></center></th>
													<th class="text-center col-md-2"><center>บาท/หน่วย</center></th>
													<th class="text-center col-md-2"><center>เป็นเงิน (บาท)</center></th>
													<th class="text-center col-md-2"><center>เป็นพลังงาน (MJ)</center></th>
												</tr>
												</thead>
												<tbody>
												<tr v-for="month in recycle.months" track-by="$index">
													<td>
														{!! Form::number("r_year[]", null, ["class"=>"md-form-control year"]) !!}
													</td>
													<td>
														{!! Form::select("r_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'),null, ["class"=>"md-form-control month"]) !!}
													</td>
													<td>
														{!! Form::number("r_unit[]", null, ["class"=>"md-form-control unit",'onfocusout' => 'r_total(this)']) !!}
													</td>
													<td>
														{!! Form::number("r_cost_unit[]", null, ["class"=>"md-form-control cost-unit",'onfocusout' => 'r_total(this)']) !!}
													</td>
													<td>
														{!! Form::number("r_total_cost[]", null, ["class"=>"md-form-control total-cost",'readonly']) !!}
													</td>
													<td>
														{!! Form::number("r_mj[]", null, ["class"=>"md-form-control mj",'readonly']) !!}
													</td>
													<td class="demo-icons">
														<a class="electric icon icon-sm icon-close icon-fw " data-pid="@{{recycle.id}}" v-on:click="deleteMonth(recycle.id,month)" title="ลบ"></a>
													</td>
												</tr>
												</tbody>
											</table>
										</div>
										<div class="col-md-12">
											<div class="md-form-group md-label-floating">
												<button type="button" class="btn btn-sm btn-info" v-on:click="addMonth(recycle.id)">
													<span class="icon icon-plus-square-o icon-lg icon-fw "></span>
													เพิ่มเดือน
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endif
				@endforeach
			@else
				<div v-for="recycle in recycles">
					<div class="card recycle-template">
						<div class="card-body">
							<div class="row">
								<div class="col-xs-6">
									<div class="md-form-group md-label-floating">
										{!! Form::select("recycle_name[]", $recycle4select, 0, ['id' => 'recycle_name_@{{recycle.id}}', 'class' => 'md-form-control recycle-name','required' => '', 'aria-required' => 'true','onchange' => 'recycle_change(@{{recycle.id}})']) !!}
										<small class="md-help-block">ชนิดพลังงาน</small>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="md-form-group md-label-floating">
										{!! Form::text("r_heat[]", null, ['id' => 'recycle_heat_@{{recycle.id}}', 'class' => 'md-form-control text-red recycle-heat','required' => '', 'aria-required' => 'true','readonly']) !!}
										<small class="md-help-block">ค่าความร้อนเฉลี่ย (MJ/หน่วย)</small>
									</div>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered table-hover" id="table_@{{recycle.id}}">
										<thead>
										<tr>
											<th class="text-center col-md-1" rowspan="2">ปี</th>
											<th class="text-center col-md-2" rowspan="2"><center>ปริมาณการใช้<br/>ต่อเดือน</center></th>
											<th class="text-center" colspan="4"><center>พลังงานหมุนเวียน</center></th>
											<th class="text-center col-md-1" rowspan="2"><center>ตัวเลือก</center></th>
										</tr>
										<tr>
											<th class="text-center col-md-2"><center><div class="text-red" id="recycle_unit_@{{recycle.id}}"></div></center></th>
											<th class="text-center col-md-2"><center>บาท/หน่วย</center></th>
											<th class="text-center col-md-2"><center>เป็นเงิน (บาท)</center></th>
											<th class="text-center col-md-2"><center>เป็นพลังงาน (MJ)</center></th>
										</tr>
										</thead>
										<tbody>
										<tr v-for="month in recycle.months" track-by="$index">
											<td>
												{!! Form::number("r_year[]", null, ["class"=>"md-form-control year"]) !!}
											</td>
											<td>
												{!! Form::select("r_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'),null, ["class"=>"md-form-control month"]) !!}
											</td>
											<td>
												{!! Form::number("r_unit[]", null, ["class"=>"md-form-control unit",'onfocusout' => 'r_total(this)']) !!}
											</td>
											<td>
												{!! Form::number("r_cost_unit[]", null, ["class"=>"md-form-control cost-unit",'onfocusout' => 'r_total(this)']) !!}
											</td>
											<td>
												{!! Form::number("r_total_cost[]", null, ["class"=>"md-form-control total-cost",'readonly']) !!}
											</td>
											<td>
												{!! Form::number("r_mj[]", null, ["class"=>"md-form-control mj",'readonly']) !!}
											</td>
											<td class="demo-icons">
												<a class="electric icon icon-sm icon-close icon-fw " data-pid="@{{recycle.id}}" v-on:click="deleteMonth(recycle.id,month)" title="ลบ"></a>
											</td>
										</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-12">
									<div class="md-form-group md-label-floating">
										<button type="button" class="btn btn-sm btn-info" v-on:click="addMonth(recycle.id)">
											<span class="icon icon-plus-square-o icon-lg icon-fw "></span>
											เพิ่มเดือน
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
	@endif
</template>

<template id="table-row">
	<tr class="clone">
		<td>
			{!! Form::number("r_year[]", null, ["class"=>"md-form-control year"]) !!}
		</td>
		<td>
			{!! Form::select("r_month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'), null, ["class"=>"md-form-control month"]) !!}
		</td>
		<td>
			{!! Form::number("r_unit[]", null, ["class"=>"md-form-control unit",'onfocusout' => 'r_total(this)']) !!}
		</td>
		<td>
			{!! Form::number("r_cost_unit[]", null, ["class"=>"md-form-control cost-unit",'onfocusout' => 'r_total(this)']) !!}
		</td>
		<td>
			{!! Form::number("r_total_cost[]", null, ["class"=>"md-form-control total-cost",'readonly']) !!}
		</td>
		<td>
			{!! Form::number("r_mj[]", null, ["class"=>"md-form-control mj",'readonly']) !!}
		</td>
		<td class="demo-icons">
			<a class="electric icon icon-close " title="ลบ"></a>
		</td>
	</tr>
</template>

@endsection


@section('script')
	<script src="{{asset('js/vue.js')}}"></script>
	<script src="{{asset('js/vue-resource.js')}}"></script>
	<script type="text/javascript" src="{{ asset('custom_assets/js/building/process5th.js')}}"></script>

@stop