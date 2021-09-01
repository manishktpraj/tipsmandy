                <div class="form-group m-form__group row @error('plan_price.0') has-danger @enderror  @error('plan_price.1') has-danger @enderror @error('plan_price.2') has-danger @enderror @error('plan_price.3') has-danger @enderror">
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
                                        <input type="text" class="form-control" name="months[]" value="1" readonly="" required />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="plan_price[]" value="{{old('plan_price.0')}}" placeholder="300" required onkeypress="return NumericValidation(event);"  />
                                        @error('plan_price.0')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                    </td>
									<td>
                                        <input type="text" class="form-control" name="regular_price[]" value="{{old('regular_price.0')}}" placeholder="300" onkeypress="return NumericValidation(event);" />
                                        @error('regular_price.0')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="months[]" value="3" readonly="" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="plan_price[]" value="{{old('plan_price.1')}}" placeholder="300" onkeypress="return NumericValidation(event);" />
                                        @error('plan_price.1')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                    </td>
									<td>
                                        <input type="text" class="form-control" name="regular_price[]" value="{{old('regular_price.1')}}" placeholder="300" onkeypress="return NumericValidation(event);" />
                                        @error('regular_price.1')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="months[]" value="6" readonly="" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="plan_price[]" value="{{old('plan_price.2')}}" placeholder="300" onkeypress="return NumericValidation(event);" />
                                        @error('plan_price.2')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                    </td>
									<td>
                                        <input type="text" class="form-control" name="regular_price[]" value="{{old('regular_price.2')}}" placeholder="300" onkeypress="return NumericValidation(event);" />
                                        @error('regular_price.2')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="months[]" value="12" readonly="" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="plan_price[]" value="{{old('plan_price.3')}}" placeholder="300" onkeypress="return NumericValidation(event);" />
                                        @error('plan_price.3')
                                        <div class="form-control-feedback">The plan price field is invalid.</div>
                                        @enderror
                                    </td>
									<td>
                                        <input type="text" class="form-control" name="regular_price[]" value="{{old('regular_price.3')}}" placeholder="300" onkeypress="return NumericValidation(event);" />
                                        @error('regular_price.3')
                                        <div class="form-control-feedback">The regular price field is invalid.</div>
                                        @enderror
                                    </td>
                                </tr>
								
                            </tbody>
                        </table>
                    </div>
                </div>


