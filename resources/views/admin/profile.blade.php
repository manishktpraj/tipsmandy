@extends('admin.layouts.master')

@section('subheader_title', 'My Profile')

@section('content')

				<div class="row">
					<div class="col-lg-4">
						<div class="m-portlet m-portlet--full-height  ">
							<div class="m-portlet__body">
								<div class="m-card-profile">
									<div class="m-card-profile__title m--hide">
										Your Profile
									</div>
									<div class="m-card-profile__pic">
										<div class="m-card-profile__pic-wrapper">
											<img src="{{Profile::admin_avatar($profile['id'])}}" alt="{{$profile['name']}}"  />
										</div>
									</div>
									<div class="m-card-profile__details">
										<span class="m-card-profile__name">{{$profile['name']}}</span>
										<a href="" class="m-card-profile__email m-link">{{$profile['email']}}</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-8">

						<div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
							<div class="m-portlet__head">
								<div class="m-portlet__head-tools">
									<ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--left m-tabs-line--success" role="tablist">
										<li class="nav-item m-tabs__item">
											<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1" role="tab">
												<i class="flaticon-share m--hide"></i>
												Update Profile
											</a>
										</li>
									</ul>
								</div>
							</div>

							<div class="tab-content">
								<div class="tab-pane active" id="m_user_profile_tab_1">

									<form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.profile')}}" method="post" enctype="multipart/form-data">

										@method('PUT')

										@csrf

										<div class="m-portlet__body">

											@if($flash = session('success'))
											<div class="form-group m-form__group m--margin-top-10" style="display: none;">
												<div id="flash-message" class="alert alert-success">
													{{$flash}}
												</div>
											</div>
											@endif

											<div class="form-group m-form__group row @error('name') has-danger @enderror">
												<label for="example-text-input" class="col-2 col-form-label">Name</label>
												<div class="col-7">
													<input class="form-control m-input" type="text" value="{{ old('name', $profile['name']) }}" name="name" autocomplete="name" autofocus="" required="required">
													@error('name')
													<div class="form-control-feedback">{{ $message }}</div>
													@enderror
												</div>
											</div>

											<div class="form-group m-form__group row @error('email') has-danger @enderror">
												<label for="example-text-input" class="col-2 col-form-label">Email</label>
												<div class="col-7">
													<input class="form-control m-input" type="email" value="{{ old('email', $profile['email']) }}" name="email" autocomplete="email" required="required">
													@error('email')
													<div class="form-control-feedback">{{ $message }}</div>
													@enderror
												</div>
											</div>

											<div class="form-group m-form__group row @error('password') has-danger @enderror">
												<label for="password" class="col-2 col-form-label">New Password</label>
												<div class="col-7">
													<input class="form-control m-input" type="password" name="password" id="password" autocomplete="password" placeholder="New Password" >
													@error('password')
													<div class="form-control-feedback">{{ $message }}</div>
													@enderror
												</div>
											</div>

											<div class="form-group m-form__group row @error('password_confirmation') has-danger @enderror">
												<label for="password-confirm" class="col-2 col-form-label">Confirm Password</label>
												<div class="col-7">
													<input class="form-control m-input" type="password" id="password-confirm" name="password_confirmation" autocomplete="password_confirmation" placeholder="Confirm Password">
													@error('password_confirmation')
													<div class="form-control-feedback">{{ $message }}</div>
													@enderror
												</div>
											</div>

											<div class="form-group m-form__group row @error('profile_image') has-danger @enderror">
												<label class="col-lg-2 col-form-label">Profile</label>
												<div class="col-lg-6">
													<input type="file" class="form-control m-input" name="profile_image" accept="image/*" onchange="loadFile(event,'profile_image')" />
													<span class="m-form__help">Allowed formats - jpg, jpeg, png.</span>
													@error('profile_image')
														<div class="form-control-feedback">{{ $message }}</div>
													@enderror
													<p></p>
													<div class="fileinput fileinput-exists">
														<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
															@if(!empty($profile['avatar']) && File::exists('public/uploads/avatar/'.$profile['avatar']))
																<img src="{{asset('public/uploads/avatar/'.$profile['avatar'])}}" id="profile_image" style="max-height: 140px;">
															@else
																<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="profile_image" style="max-height: 140px;">
															@endif
														</div>
													</div>
												</div>
											</div>

										</div>

										<div class="m-portlet__foot m-portlet__foot--fit">
											<div class="m-form__actions">
												<div class="row">
													<div class="col-2">
													</div>
													<div class="col-7">
														<button type="submit" class="btn btn-success m-btn m-btn--air m-btn--custom btn-sm"> Update</button>&nbsp;&nbsp;

														<a href="{{route('admin.dashboard')}}" class="btn btn-secondary btn-sm">Cancel</a>

													</div>
												</div>
											</div>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>
				</div>

@endsection
