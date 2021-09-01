                <div class="form-group m-form__group row @error('plan_price_a') has-danger @enderror  @error('plan_price_b') has-danger @enderror @error('plan_price_c') has-danger @enderror @error('plan_price_d') has-danger @enderror @error('update_plan_price_a') has-danger @enderror  @error('update_plan_price_b') has-danger @enderror @error('update_plan_price_c') has-danger @enderror @error('update_plan_price_d') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12" for="name">Plan Price *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="40%">Month</th>
                                    <th>Offer Price</th>
                                    <th>Regular Price</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="month_a" value="1" readonly="" />
                                    </td>
                                    <td>
                                        @if($plan->getPlanPriceDetail($plan->id, 1))
                                        <input type="hidden" name="plan_price_id_a" value="{{$plan->getPlanPriceDetail($plan->id, 1)->id}}" />
                                        <input type="text" class="form-control" name="update_plan_price_a" value="{{old('plan_price_a', $plan->getPlanPriceDetail($plan->id, 1)->price ?? '')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @else
                                        <input type="text" class="form-control" name="plan_price_a" value="{{old('plan_price_a')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @endif
                                        @error('plan_price_a')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                        @error('update_plan_price_a')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                    </td>
									
									<td>
                                        @if($plan->getPlanPriceDetail($plan->id, 1))
                                        <input type="hidden" name="regular_price_id_a" value="{{$plan->getPlanPriceDetail($plan->id, 1)->id}}" />
                                        <input type="text" class="form-control" name="update_regular_price_a" value="{{old('regular_price_a', $plan->getPlanPriceDetail($plan->id, 1)->regular_price ?? '')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @else
                                        <input type="text" class="form-control" name="regular_price_a" value="{{old('regular_price_a')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @endif
                                        @error('regular_price_a')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                        @error('update_regular_price_a')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                    </td>
									
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="month_b" value="3" readonly="" />
                                    </td>
                                    <td>
                                        @if($plan->getPlanPriceDetail($plan->id, 3))
                                        <input type="hidden" name="plan_price_id_b" value="{{$plan->getPlanPriceDetail($plan->id, 3)->id}}" />
                                        <input type="text" class="form-control" name="update_plan_price_b" value="{{old('plan_price_b', $plan->getPlanPriceDetail($plan->id, 3)->price ?? '')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @else
                                        <input type="text" class="form-control" name="plan_price_b" value="{{old('plan_price_b')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @endif
                                        @error('plan_price_b')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                        @error('update_plan_price_b')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                    </td>
									<td>
                                        @if($plan->getPlanPriceDetail($plan->id, 3))
                                        <input type="hidden" name="regular_price_id_b" value="{{$plan->getPlanPriceDetail($plan->id, 3)->id}}" />
                                        <input type="text" class="form-control" name="update_regular_price_b" value="{{old('regular_price_b', $plan->getPlanPriceDetail($plan->id, 3)->regular_price ?? '')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @else
                                        <input type="text" class="form-control" name="regular_price_b" value="{{old('regular_price_b')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @endif
                                        @error('regular_price_b')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                        @error('update_regular_price_b')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="month_c" value="6" readonly="" />
                                    </td>
                                    <td>
                                      @if($plan->getPlanPriceDetail($plan->id, 6))
                                      <input type="hidden" name="plan_price_id_c" value="{{$plan->getPlanPriceDetail($plan->id, 6)->id}}" />
                                      <input type="text" class="form-control" name="update_plan_price_c" value="{{old('plan_price_c', $plan->getPlanPriceDetail($plan->id, 6)->price ?? '')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                      @else
                                      <input type="text" class="form-control" name="plan_price_c" value="{{old('plan_price_c')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                      @endif
                                      @error('plan_price_c')
                                      <div class="form-control-feedback">The plan price field is invalid.</div>
                                      @enderror
                                      @error('update_plan_price_c')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                    </td>
									<td>
                                        @if($plan->getPlanPriceDetail($plan->id, 6))
                                        <input type="hidden" name="regular_price_id_c" value="{{$plan->getPlanPriceDetail($plan->id, 6)->id}}" />
                                        <input type="text" class="form-control" name="update_regular_price_c" value="{{old('regular_price_c', $plan->getPlanPriceDetail($plan->id, 6)->regular_price ?? '')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @else
                                        <input type="text" class="form-control" name="regular_price_c" value="{{old('regular_price_c')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @endif
                                        @error('regular_price_c')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                        @error('update_regular_price_c')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="month_d" value="12" readonly="" />
                                    </td>
                                    <td>
                                      @if($plan->getPlanPriceDetail($plan->id, 12))
                                      <input type="hidden" name="plan_price_id_d" value="{{$plan->getPlanPriceDetail($plan->id, 12)->id}}" />
                                      <input type="text" class="form-control" name="update_plan_price_d" value="{{old('plan_price_d', $plan->getPlanPriceDetail($plan->id, 12)->price ?? '')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                      @else
                                      <input type="text" class="form-control" name="plan_price_d" value="{{old('plan_price_d')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                      @endif
                                      @error('plan_price_d')
                                      <div class="form-control-feedback">The plan price field is invalid.</div>
                                      @enderror
                                      @error('update_plan_price_d')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                    </td>
									<td>
                                        @if($plan->getPlanPriceDetail($plan->id, 12))
                                        <input type="hidden" name="regular_price_id_d" value="{{$plan->getPlanPriceDetail($plan->id, 12)->id}}" />
                                        <input type="text" class="form-control" name="update_regular_price_d" value="{{old('regular_price_d', $plan->getPlanPriceDetail($plan->id, 12)->regular_price ?? '')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @else
                                        <input type="text" class="form-control" name="regular_price_d" value="{{old('regular_price_d')}}" placeholder="300" onkeypress="return NumericValidation(event);"  />
                                        @endif
                                        @error('regular_price_d')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                        @error('update_regular_price_d')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
