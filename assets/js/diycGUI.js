/*Version 2.0 */
var SRV,ajaxURL,imageDir,imageURL,siteURL,origProdName,disableATC;
var shopcode = 'DIY';
var DESIGN_ID;
var CANVASW, CANVASH;
var diycIndex = 0;
var diycActInd = 0;
var diycImgInd = 0;
var diycImgW = new Array;
var diycImgH = new Array;
var diycSepiaValue = 'off';
var diycImageExt = 'jpg';
var	CANVASASP;
var diycCropValue = 'none';
var postID;
var themeBootstrap;
jQuery(document).ready(function() {
	SRV = diycIEParams.srv+'/diycware-image-editor/assets/';
	imageDir = diycIEParams.imageDir+'/diycware-image-editor-saved';
	imageURL = diycIEParams.imageURL+'/diycware-image-editor-saved';
	ajaxURL = diycIEParams.ajaxURL;
	siteURL = diycIEParams.siteURL;
	postID = diycIEParams.postID;
	origProdName = diycIEParams.originalProduct;
	disableATC = diycIEParams.disableATC;
	if (disableATC == 'true') {
		jQuery('div[class="variations_button"]').remove();
	}
	themeBootstrap = diycIEParams.themeBootstrap;
	if (themeBootstrap == 'true') {
		jQuery('#diycLaunchToolBT').attr('class','btn btn-success');
		jQuery('#diycLaunchHelpBT').attr('class','btn btn-warning');
	}
	else {
		jQuery('#diycLaunchToolBT').button();
		jQuery('#diycLaunchHelpBT').button();
		jQuery( "input[type=submit]").button();
	}
	jQuery('#diycLaunchToolBT').click(function (){
		jQuery('#diycTopContainer').dialog('open');
		var T = parseInt(jQuery('#diycWorkArea').position().top);
		var L = parseInt(jQuery('#diycWorkArea').position().left);
		jQuery('#diycCropper').css('top',T+"px");
		jQuery('#diycCropper').css('left',L+"px");
	});
	jQuery('#diycLaunchHelpBT').click(function(event) {
		event.preventDefault();
		jQuery('#diycHelpDia').dialog('open');

	});
	getepoc();
});
jQuery(document).ready(function() {
	var X = jQuery("#diycLaunchToolBT").position().left;
	var Y = jQuery("#diycLaunchToolBT").position().top;
	jQuery('#diycTopContainer').dialog({height: 600, width: 960, autoOpen: false, position: {my: "left top", at: "left top", of: "#diycLaunchToolBT"}});
	jQuery('#diycMainAccordion').accordion({icons: false, heightStyle: "fill"});
	jQuery('#diycProgressDia1').dialog( {height: 250, resizable: false, width: 500, autoOpen: false, position: {my: "center", at: "center", of: "#diycTopContainer"}});
	jQuery('#diycFileErrorDiv').dialog({height: 300, resizable: false, width: 300, autoOpen: false});
	jQuery('#diycFileErrorDiv').on('dialogclose', function( event, ui ) {hideErrorPs();} );
	jQuery('#diycHelpDia').dialog({height: 300, resizable: true, width: 400, autoOpen: false});
	jQuery('#diycRedirectDia').dialog({height: 300, resizable: false, width: 400, autoOpen: false, modal: true});
	jQuery("#diycSepiaValue").spinner({max: '100', min: '0', step: 10, change: sepiaImage, spin: sepiaImage, stop: sepiaImage});
	jQuery("#diycContrastValue").spinner({max: '100', min: '0', step: 10, change: imageEditBC, spin: imageEditBC, stop: imageEditBC});
	jQuery("#diycBrightnessValue").spinner({max: '200', min: '0', step: 10, change: imageEditBC, spin: imageEditBC, stop: imageEditBC});
	jQuery("#diycBrightnessValue").val(100);
	jQuery('#diycCropper').draggable({containment: "#diycWorkArea"});
	jQuery('#diycCropper').resizable();
	if (themeBootstrap == 'true') {
		jQuery('#diycMainAccordion button').each(function() {
			jQuery(this).attr('class','btn btn-info');
		});
	}
	else {
		jQuery('#diycMainAccordion button').each(function() {
			jQuery(this).button();
		});
	}
	jQuery("#diycBT60").click(function() {sepiaImage('on');});
	jQuery("#diycBT61").click(function() {sepiaImage('off');});
	jQuery("#diycBT62").click(function() {enableCrop();});
	jQuery("#diycBT63").click(function() {cropIt();});
	jQuery("#diycBT64").click(function() {undoAll();});
	jQuery("#diycBT65").click(function() {saveImage();});
	jQuery('#diycFileImgID2').val('0');
	var T = parseInt(jQuery('#diycWorkArea').position().top);
	var L = parseInt(jQuery('#diycWorkArea').position().left);
	jQuery('#diycCropper').css('top',T+"px");
	jQuery('#diycCropper').css('left',L+"px");
	CANVASW = parseInt(jQuery('#diycCropper').css('width'));
	CANVASH = parseInt(jQuery('#diycCropper').css('height'))
});
function getepoc() {
	jQuery.ajax({
		url: SRV+'php/getepoc.php?sc='+shopcode+'&x='+(Math.random()*1000000),
		type: 'GET',
		dataType: 'text',
		success: function(response) {
			jQuery('#diycFileImgID1').val(response);
			DESIGN_ID = response;
			},
		error: function() {alert('Fail');}
	});
	jQuery("#diycDesignID").val(DESIGN_ID);
}
jQuery(function () {
	var diycBar = jQuery('.diycBar');
	var diycPercent = jQuery('.diycPercent');
	var diycStatus = jQuery('#diycStatus');
	jQuery('#diycImgForm').ajaxForm({
	url: SRV+'php/getimage1.php',
	data: { width : CANVASW, height : CANVASH},
	beforeSend: function() {
		jQuery('#diycProgressDia1').dialog('open');
        diycStatus.empty();
        var percentVal = '0%';
		diycBar.width(percentVal);
        diycPercent.html(percentVal);
    },
	uploadProgress: function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        diycBar.width(percentVal)
		diycPercent.html(percentVal);
	},
	success: function() {
		var percentVal = '100%';
		diycBar.width(percentVal)
		diycPercent.html(percentVal);
	},
	failure: function(){commError();},
	complete: function(xhr) {
		diycStatus.html('<strong>Upload Complete</strong>');
		var params = xhr.responseText.split(':');
		if (params[0] != 'error') {
			jQuery('#imageGallery1').empty();
			jQuery('#diycProgressDia1').dialog('close');
			diycImageExt = params[0];
			var html = '<strong>Image Properties: </strong><br />Image Size = '+params[1]+'x'+params[2]+'px<br />Resolution = '+parseInt(params[3])+'dpi x '+parseInt(params[4])+'dpi';
			jQuery('#diycTF10').html(html);
			insertImage2();
			diycImgW[parseInt(jQuery('#diycFileImgID2').val())] = params[1];
			diycImgH[parseInt(jQuery('#diycFileImgID2').val())] = params[2];
		}
		else {
			var errorMessage = params[1];
			if (params[1] == 'nofile') {
				jQuery('#diycProgressDia1').dialog('close');
				jQuery('#diycFileError1').css('display','inline');
				jQuery('#diycFileErrorDiv').dialog('open');
			}
			if (params[1] == 'filetype') {
				jQuery('#diycProgressDia1').dialog('close');
				jQuery('#diycFileError2').css('display','inline');
				jQuery('#diycFileErrorDiv').dialog('open');
			}
		}
	}
	}); 
});
function insertImage2(img) {
	jQuery('#diycMainImage').empty();
	var src = SRV+'temp/'+DESIGN_ID+'/Cimageedit0'+diycImageExt;
	var aspRatio, width, height;
	width = diycImgW[0];
	height = diycImgH[0];
	aspRatio = parseFloat(width)/parseFloat(height);
	var handles = "se";
	objType = 'image';
	diycIndex++;
	diycActInd = diycIndex;
	jQuery('#diycMainImage').append('<img src="'+src+'" />');
}
function scaleCanvasToImage() {
	width = diycImgW[0];
	height = diycImgH[0];
	aspRatio = parseFloat(width)/parseFloat(height);
	jQuery('#diycCanvas').css('width',width+'px');
	jQuery('#diycCanvas').css('height',parseFloat(width)/parseFloat(CANVASASP)+'px');
}
function sepiaImage(level) {
	var imageOnPage = checkOnPageImage();
	if (!imageOnPage) {
		return;
	}
	if (level == 'on') {
		if (diycSepiaValue == 'off') {
			var sepia = jQuery("#diycSepiaValue").val();
			diycSepiaValue = jQuery("#diycSepiaValue").val();
			
		}
		else {
			return;
		}
	}
	if (level == 'off') {
		if (diycSepiaValue == 'off') {
			return;
		}
		else {
			diycSepiaValue = 'off';
			//jQuery('#diycSepiaSlider').slider('value',50);
			jQuery("#diycSepiaValue").val(50)
			var sepia = 'off';
		}
	} 
	if (level != 'on' && level != 'off') {
		var sepia = jQuery("#diycSepiaValue").val();
	}
	jQuery.ajax({
		url: SRV+'php/sepia.php',
		type: 'POST',
		data: {
			design_id: DESIGN_ID,
			sepia: sepia,
			ext: diycImageExt
		},
		success: function(string) {
			src = SRV+'temp/'+DESIGN_ID+'/Cimageedit0'+diycImageExt+'?cache='+(Math.random()*1000000);
			jQuery('#diycMainImage').empty();
			jQuery('#diycMainImage').append('<img src="'+src+'" />');
			jQuery("#diycBrightnessValue").val(100);
			jQuery("#diycContrastValue").val(0);
		},
		error: function() {commError();}
	});
}
function imageEditBC() {
	var imageOnPage = checkOnPageImage();
	if (!imageOnPage) {
		return;
	}
	jQuery.ajax({
		url: SRV+'php/image_edit.php',
		type: 'POST',
		data: {
			design_id: DESIGN_ID,
			contrast: jQuery("#diycContrastValue").val(),
			brightness: jQuery("#diycBrightnessValue").val(),
			ext: diycImageExt
		},
		success: function(string) {
			src = SRV+'temp/'+DESIGN_ID+'/Cimageedit0'+diycImageExt+'?cache='+(Math.random()*1000000);
			jQuery('#diycMainImage').empty();
			jQuery('#diycMainImage').append('<img src="'+src+'" />');
			jQuery("#diycSepiaValue").val(50);
			diycSepiaValue = 'off';
		},
		error: function() {commError();}
	});
}
function enableCrop() {
	var state = jQuery('#diycCropper').css('display');
	if (state == 'none') {
		jQuery('#diycCropper').css('display','block');
		jQuery('#diycBT63').css('display','inline');
		jQuery('#diycCropMessage').css('display','block');
	}
	else {
		jQuery('#diycCropper').css('display','none');
		jQuery('#diycBT63').css('display','none');
		jQuery('#diycCropMessage').css('display','none');
	}
}
function cropIt() {
	var imageOnPage = checkOnPageImage();
	if (!imageOnPage) {
		return;
	}
	var x1 = parseInt(jQuery('#diycCropper').position().left)-parseInt(jQuery('#diycMainImage').position().left);
	var y1 = parseInt(jQuery('#diycCropper').position().top)-parseInt(jQuery('#diycMainImage').position().top);
	var w = parseInt(jQuery('#diycCropper').css('width'));
	var h = parseInt(jQuery('#diycCropper').css('height'));
	if (x1 >= 0) {
		x1 = '+'+x1;
	}
	if (y1 >= 0) {
		y1 = '+'+y1;
	}
	jQuery.ajax({
		url: SRV+'php/crop.php',
		type: 'POST',
		data: {
			design_id: DESIGN_ID,
			x1: x1,
			y1: y1,
			w: w,
			h: h,
			ext: diycImageExt
		},
		success: function(string) {
			src = SRV+'temp/'+DESIGN_ID+'/Cimageedit0'+diycImageExt+'?cache='+(Math.random()*1000000);
			jQuery('#diycMainImage').empty();
			jQuery('#diycMainImage').append('<img src="'+src+'" />');
			diycCropValue = x1+":"+y1+":"+w+":"+h;
			jQuery("#diycBrightnessValue").val(100);
			jQuery("#diycContrastValue").val(0);
			jQuery("#diycSepiaValue").val(50);
			diycSepiaValue = 'off';
			jQuery( "#diycCropper" ).css( 'display','none' );
			jQuery( "#diycBT63" ).css( 'display','none' );
			jQuery( "#diycBT62" ).css( 'display','none' )
		},
		error: function() {commError();}
	});
}
function checkOnPageImage() {
	if (jQuery('#diycMainImage img').length == 0) {
		return false;
	}
	else {
		return true;
	}
}
function undoAll() {
	var imageOnPage = checkOnPageImage();
	if (!imageOnPage) {
		return;
	}
	jQuery.ajax({
		url: SRV+'php/undo_all.php',
		type: 'POST',
		data: {
			design_id: DESIGN_ID,
			ext: diycImageExt
		},
		success: function(string) {
			src = SRV+'temp/'+DESIGN_ID+'/Cimageedit0'+diycImageExt+'?cache='+(Math.random()*1000000);
			jQuery('#diycMainImage').empty();
			jQuery('#diycMainImage').append('<img src="'+src+'" />');
			if (jQuery( "#diycBT62" ).css( 'display') == 'none' ) {
				jQuery( "#diycBT62" ).css( 'display','inline');
			}
			resetValues();
		},
		error: function() {commError();}
	});
}
function loadMyXML (file) {
	var xmlDoc;
	xmlDoc=new window.XMLHttpRequest();
	xmlDoc.open("GET",file,false);
	xmlDoc.send("null");
	return xmlDoc.responseXML;
}
function saveImage() {
	jQuery.ajax({
		url: ajaxURL,
		type: 'POST',
		data: {
			action: 'diycie_action_callback',
			call: 'duplicate',
			name: jQuery('#diycFileImg').val(),
			design_id: DESIGN_ID,
			contrast: jQuery("#diycContrastValue").val(),
			brightness: jQuery("#diycBrightnessValue").val(),
			sepia: diycSepiaValue,
			crop: diycCropValue,
			ext: diycImageExt,
			dir: imageDir,
			id: postID,
			nonce: jQuery('#diycNonce').val()
		},
		success: function(string) {
			jQuery('#diycRedirectDia').dialog('open');
			var src = origProdName.toLowerCase().replace(/\s/g,'-')+'-'+DESIGN_ID;
			window.location.assign(siteURL+'/product/'+src);
		},
		error: function() {commError();}
	});
}
function resetValues() {
	jQuery("#diycBrightnessValue").val('100');
	jQuery("#diycContrastValue").val('0');
	diycSepiaValue = 'off';
	jQuery("#diycSepiaValue").val('50');
}
function hideErrorPs() {
	jQuery('#diycFileErrorDiv').find('p').css('display','none');
}
function commError() {
	jQuery('#diycCommError').css('display','inline');
	jQuery('#diycFileErrorDiv').dialog('open');
}