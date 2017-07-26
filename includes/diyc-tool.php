<div id="diycTopContainer" title="Image Upload and Editor">
		<div id="diycMainAccordiontop">
			<div id="diycMainAccordion">
				<h3 id="diycAccTitle05">Image Upload</h3>
				<div id="diycImgDia">
					<form action="getimage1.php" id="diycImgForm" name="diycImgForm" method="post" enctype="multipart/form-data">
							  <input type="file" name="diycFileImg" id="diycFileImg" />
							  <input type="hidden" name="diycFileImgID1" id="diycFileImgID1" value="" />
							  <input type="hidden" name="diycFileImgID2" id="diycFileImgID2" value="0" />
							  <input type="submit" value="Upload Image" />
					</form>
					<div class="diycImgGal" style="visibility:hidden"></div>
					<div id="diycTF10" class="diycTF1"></div>
					<div id="imageGallery1" name="imageGallery1"></div>
				</div>
				<h3 id="diycAccTitle07">Image Edit</h3>
				<div id="diycEditImageDia">
					<p>Crop: <button id="diycBT62">On</button><button id="diycBT63">Crop It</button></p>
					<p>Brightness <input id="diycBrightnessValue" size="2" value="100" /></p>
					<p>Contrast <input id="diycContrastValue" size="2" value="0" /></p>
					<p>Sepia <button id="diycBT60">On</button><button id="diycBT61">Off</button><input id="diycSepiaValue" size="2" value="50" /></p>
					<p><button id="diycBT64">Undo All</button></p>
				</div>
				<h3 id="diycAccTitle08">Save Image</h3>
				<div id="diycSaveDia">
					<button id="diycBT65">Save Image</button>
					<p>Once you save your image, you will be taken to a product page where you will be able to select your options and purchase the product.</p>
				</div>
			</div>
		</div>
		<div id="diycWorkArea">
				<div id="diycMainImage"><p>Upload Your Image First</p></div>
				<div id="diycCropper">
				</div>
		</div>
		<div id="diycInfo">
			<p id="diycCropMessage">Drag and resize the cropping box. Then click on the Crop button</p>
		</div>
		<div id="diycHidden">
			<input type="hidden" id="diycNonce" value="<?php echo wp_create_nonce( "create-product-nonce" ); ?>" />
		</div>
	</div>
</div>
<div id="diycProgressDia1">
	<div class="diycProgress">
		<div class="diycBar"></div >
		<div class="diycPercent">0%</div >
	</div>
	<div id="diycStatus"></div>
</div>
<div id="diycFileErrorDiv" title="Error">
	<p id="diycFileError1">No file was selected for upload. Please try again.</p>
	<p id="diycFileError2">Image file must be JPEG, GIF, BMP, or PNG. Please try again.</p>
	<p id="diycCommError">Communications Error - please try later.</p>
</div>
<div id="diycHelpDia" title="Help">
	<?php include('diyc-help-dialog.html'); ?>
</div>
<div id="diycRedirectDia" title="Saving">
	<strong>Your image is being saved. You will be re-directed shortly.</strong>
</div>
