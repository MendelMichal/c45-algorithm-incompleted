var dataArray;
var startDatasetArray;
var finalDatasetArray;

$(document).on({
	ajaxStart : function() {
		$("body").addClass("loading");
	},
	ajaxStop : function() {
		$("body").removeClass("loading");
	}
});

$(document).ready(function() {
	$('#step1').addClass('show');
});

$('input[type=file]').change(function() {
	var t = $(this).val();
	var labelText = 'Nazwa pliku : ' + t.substr(12, t.length);
	$(this).prev('label').text(labelText);

	var file = $('#dataset').prop('files')[0];
	var formDataset = new FormData();
	formDataset.append('file', file);

	$.ajax({
		url : "handlers/uploadHandler.php",
		type : 'POST',
		data : formDataset,
		success : function(data) {
			dataArray = jQuery.parseJSON(data);
			$("#result").html(dataArray[0]);
			if (dataArray[0] == 'Poprawnie wgrano zbiór danych') {
				startDatasetArray = dataArray[1];

				$('#rest').show();

				$('#step1-div').addClass("margin-bottom-40");
				$('#table-preview').html(dataArray[2]);
				$('#dataset-preview').show();
				$('#step2').show();

				if (!$('#step2').hasClass("show")) {
					$('#step2').addClass('show');
				}

				$('html, body').animate({
					scrollTop : $("#step2").offset().top
				}, 1000)
			} else if (dataArray[0] == 'Brak załączonego pliku') {
				$('#rest').hide();
				$('#step2').hide();
				$('#dataset-preview').hide();
				$('#step3').hide();
				$('#dataset-final-preview').hide();
				$('#calculations').hide();

				$('html, body').animate({
					scrollTop : $("#step1").offset().top
				}, 1000);

				$('#isFirstLaneAttribute')[0].reset();
				$('#decisionAttribute')[0].reset();

				if ($('#step2').hasClass("show")) {
					$('#step2').removeClass("show");
				}
				if ($('#step3').hasClass("show")) {
					$('#step3').removeClass("show");
				}
				if ($('#calculations').hasClass("show")) {
					$('#calculations').removeClass("show");
				}
			}

			;
		},
		cache : false,
		contentType : false,
		processData : false
	});
});

$('#isFirstLaneAttribute input').change(
		function() {
			var value = $('input[name=firstLineAttribute]:checked',
					'#isFirstLaneAttribute').val();
			
			$('#calculations').hide();
			if ($('#calculations').hasClass("show")) {
				$('#calculations').removeClass("show");
			}
			
			if (value == 'yes') {
				finalDatasetArray = startDatasetArray;

				$('#step2-div').addClass("margin-bottom-40");
				$('#table-final-preview').html(dataArray[2]);
				$('#dataset-final-preview').show();
				$('#step3').show();

				if (!$('#step3').hasClass("show")) {
					$('#step3').addClass('show');
				}

				$('html, body').animate({
					scrollTop : $("#step3").offset().top
				}, 1000);

				$.loadAttributes();
			} else {
				$.ajax({
					url : "handlers/addAttributeHandler.php",
					type : 'POST',
					data : {
						data : JSON.stringify(startDatasetArray)
					},
					success : function(data) {
						var dataArray2 = jQuery.parseJSON(data);
						finalDatasetArray = dataArray2[0];

						$('#step2-div').addClass("margin-bottom-40");
						$('#table-final-preview').html(dataArray2[1]);
						$('#dataset-final-preview').show();
						$('#step3').show();

						if (!$('#step3').hasClass("show")) {
							$('#step3').addClass('show');
						}

						$('html, body').animate({
							scrollTop : $("#step3").offset().top
						}, 1000);

						$.loadAttributes();
					},
				});
			}
		});

$.loadAttributes = function() {
	$.ajax({
		url : "handlers/loadAttributesHandler.php",
		type : 'POST',
		data : {
			data : JSON.stringify(finalDatasetArray)
		},
		success : function(data) {
			$('#decisionAttribute').html(data);
		},
	});
};

$(document).on(
		'change',
		'#decisionAttribute input',
		function() {
			var value = $('input[name=decAttribute]:checked',
					'#decisionAttribute').val();
			
			$.ajax({
				url : "handlers/algorithmHandler.php",
				type : 'POST',
				data : {
					data : JSON.stringify(finalDatasetArray),
					decision : value
				},
				success : function(data) {
					var data2 = jQuery.parseJSON(data);
					
					$("#gEntropy").text((data2.globalEntropy[0]));
					
					$("#calculationsTable").html((data2.tableHtml));
					
					$('#calculations').show();

					if (!$('#calculations').hasClass("show")) {
						$('#calculations').addClass('show');
					}
					
					$('html, body').animate({
						scrollTop : $("#calculations").offset().top
					}, 1000);
					
				},
			});
		});