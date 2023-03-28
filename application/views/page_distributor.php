<div id="contact" class="section mt-5">
	<div class="container container-full p-5">
		<div class="row d-flex justify-content-between">
			<?php foreach ($province as $value): ?>
				<div class="col-12 col-lg-12 mt-2">
					<div id="accordion<?= $value['province'] ?>">
						<div class="card shadow bg-dark text-white">
							<a class="card-link" data-toggle="collapse" href="#province<?= $value['province'] ?>">
								<div class="card-header">
									<?= $this->list_distributor_model->get_province_name($value['province'])['name'];?>
								</div>
							</a>

							<div id="province<?= $value['province'] ?>" class="collapse" data-parent="#accordion<?= $value['province'] ?>">
								<div id="accordionkedua<?= $value['province'] ?>">
									<div class="card text-dark">
										<?php foreach ($this->list_distributor_model->get_city($value['province']) as $city): ?>
											<a class="card-link" data-toggle="collapse" href="#collapse<?= $city['city'] ?>">
												<div class="card-header bg-secondary text-white">

													<?= $this->list_distributor_model->get_city_name($city['city'])['name'] ?>

												</div>
											</a>
											<div id="collapse<?= $city['city'] ?>" class="collapse" data-parent="#accordionkedua<?= $value['province'] ?>">
												<div id="accordionketiga<?= $value['province'] ?>">
													<div class="card text-dark">
														<?php foreach ($this->list_distributor_model->get_company_name($value['province'],$city['city']) as $company_name): ?>
															<div class="dropdown-divider"></div>
															<a href="#detil<?= $company_name['list_distributor_id'] ?>" data-toggle="collapse">
																<div class="card-body">
																	<?= $company_name['company_name'] ?>
																</div>
															</a>
															<div class="dropdown-divider"></div>
															<div id="detil<?= $company_name['list_distributor_id'] ?>" class="collapse" data-parent="#accordionketiga<?= $company_name['province'] ?>">
																<div class="card-body">
																	<div class="modal-body">
																		<ul class="nav nav-tabs" role="tablist">
																			<li class="nav-item">
																				<a class="nav-link active pr-5 pl-5" data-toggle="tab" href="#tab1<?= $company_name['list_distributor_id'] ?>">Profil</a>
																			</li>
																			<li class="nav-item">
																				<a class="nav-link pr-5 pl-5" data-toggle="tab" href="#tab2<?= $company_name['list_distributor_id'] ?>">Bisnis</a>
																			</li>
																			<li class="nav-item">
																				<a class="nav-link pr-5 pl-5" data-toggle="tab" href="#tab3<?= $company_name['list_distributor_id'] ?>">Alamat</a>
																			</li>
																			<li class="nav-item">
																				<a class="nav-link pr-5 pl-5" data-toggle="tab" href="#tab4<?= $company_name['list_distributor_id'] ?>">Sosial Media</a>
																			</li>
																		</ul>
																		<div class="tab-content">
																			<div id="tab1<?= $company_name['list_distributor_id'] ?>" class="tab-pane active p-4"><br>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Company Name</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['company_name'] ?>" maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Shop</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['shop_name'] ?>" maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Number Phone</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['number_phone'] ?>" maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Google Maps</label>
																						<div class="col-sm-8">
																							<a href="http://www.google.com/maps/place/<?= $company_name['latitude'] ?>,<?= $company_name['longitude'] ?>" target="_blank" style="text-decoration: underline;">
																								<i class="fas fa-map-marker-alt"></i> <?= $company_name['shop_name'] ?></a>
																						</div>
																					</div>
																				</div>
																			</div>
																			<div id="tab2<?= $company_name['list_distributor_id'] ?>" class="tab-pane fade p-4"><br>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Category</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['category'] ?>" maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Business</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['business'] ?>" maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Haircut</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" <?php if ($company_name['haircut']==1): ?>
																							<?= 'value="Ya"' ?>
																						<?php else: ?>
																							<?= 'value="Tidak"' ?>
																							<?php endif ?> maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Sales</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" <?php if ($company_name['sales']==1): ?>
																							<?= 'value="Ya"' ?>
																						<?php else: ?>
																							<?= 'value="Tidak"' ?>
																							<?php endif ?> maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Repair</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" <?php if ($company_name['repair']==1): ?>
																							<?= 'value="Ya"' ?>
																						<?php else: ?>
																							<?= 'value="Tidak"' ?>
																							<?php endif ?> maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Sparepart</label>
																						<div class="col-sm-8">
																							<input type="text" class="form-control bg-white" name="name" <?php if ($company_name['spare_part']==1): ?>
																							<?= 'value="Ya"' ?>
																						<?php else: ?>
																							<?= 'value="Tidak"' ?>
																							<?php endif ?> maxlength="64" disabled="">
																						</div>
																					</div>
																				</div>
																			</div>
																			<div id="tab3<?= $company_name['list_distributor_id'] ?>" class="tab-pane fade p-4"><br>
																				<div class="form-group">
																					<div class="row">
																						<label class="control-label col-sm-4 font-weight-bold">Alamat</label>
																						<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['address'] ?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Kode Pos</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['post_code'] ?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Provinsi</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?= $this->list_distributor_model->get_province_dua($company_name['province'])['name'] ?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Kota/ Kabupaten</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?= $this->list_distributor_model->get_city_dua($company_name['city'])['name'] ?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Kecamatan</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?= $this->list_distributor_model->get_subdistrict($company_name['sub_district'])['name'] ?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Koorditan Google Map</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['latitude'].$company_name['longitude'] ?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																				</div>
																				<div id="tab4<?= $company_name['list_distributor_id'] ?>" class="tab-pane fade p-4"><br>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Facebook</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['facebook'] ?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Twiter</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?= $company_name['twiter'] ?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Instagram</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?=$company_name['instagram']?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Youtube</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?=$company_name['youtube']?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Linkedin</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?=$company_name['linkedin']?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Tiktok</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?=$company_name['tiktok']?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<label class="control-label col-sm-4 font-weight-bold">Website/ Market Place</label>
																							<div class="col-sm-8">
																								<input type="text" class="form-control bg-white" name="name" value="<?=$company_name['website']?>" maxlength="64" disabled="">
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															<?php endforeach ?>

														</div>
													</div>






												</div>
											<?php endforeach ?>

										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				<?php endforeach ?>
			</div>

		</div>

	</div>

</div>

</div>