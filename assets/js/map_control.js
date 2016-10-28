/* -- MAP CONTROL FOR SELLERS -- */
function find_map_point(v,d) {

	$.ajax({
		type: 'GET',
		url: 'system/modules/geoxml3map-contao/assets/kml/marker_position.php?findpos='+v,
		dataType: 'json',
			success: function(response){
				console.log('Success: ', response);
				search_profile_coords(response.lat,response.lng,d);
			},
			error: function(xhr, type){
			   console.log(xhr, type);
			}
	});
}
// search profile
function search_profile(v,d) {
	if(v =='all'){
		$.ajax({
		type: 'GET',
		url: 'system/modules/geoxml3map-contao/assets/kml/marker_list.php?action='+v+'&d='+d,
		dataType: 'json',
			success: function(response){
				if(response != null){
					console.log('Success: ', response);
					renderList(response);
					reload_profile_kml(v,d);
				}
			},
			error: function(xhr, type){
			   console.log(xhr, type);
			}
		});
	}else{
		find_map_point(v,d);
	}
	
}
function search_profile_coords(lat,lng,d) {
	$.ajax({
	type: 'GET',
	url: 'system/modules/geoxml3map-contao/assets/kml/marker_list.php?action=pos&lat='+lat+'&lng='+lng+'&d='+d,
	dataType: 'json',
		success: function(response){
			if(response != null){
				console.log('Success: ', response);
				renderList(response);
				reload_profile_kml_coords(lat,lng,d);
			}
		},
		error: function(xhr, type){
		   console.log(xhr, type);
		}
	});
}
function reload_profile_kml(v,d) {
	
    var gmap_Options={
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center:new google.maps.LatLng(47.3, 0.40),
    };
    var map = new google.maps.Map(document.getElementById("map"),gmap_Options); // gmap
    infowindow = new google.maps.InfoWindow({});
    //var myParser = new geoXML3.parser({map: gmap});
    //myParser.parse('system/modules/distributeur/assets/kml/distributeur_kml.php?action='+v);
    geoXml = new geoXML3.parser({map: map,infoWindow: infowindow,singleInfoWindow: true,afterParse: useTheData});
    geoXml.parse('system/modules/geoxml3map-contao/assets/kml/marker_kml.php?action='+v+'&d='+d);
}
function reload_profile_kml_coords(lat,lng,d) {
    var gmap_Options={
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center:new google.maps.LatLng(47.3, 0.40),
    };
    var map = new google.maps.Map(document.getElementById("map"),gmap_Options); // gmap
    infowindow = new google.maps.InfoWindow({});

    //var myParser = new geoXML3.parser({map: gmap});
    //myParser.parse('system/modules/distributeur/assets/kml/distributeur_kml.php?action=pos&lat='+lat+'&lng='+lng);
    geoXml = new geoXML3.parser({map: map,infoWindow: infowindow,singleInfoWindow: true,afterParse: useTheData});
    geoXml.parse('system/modules/geoxml3map-contao/assets/kml/marker_kml.php?action=pos&lat='+lat+'&lng='+lng+'&d='+d);
}

// Render list of profiles
function renderList(data) {
	
	$("#profileMap #Grid").mixItUp('destroy'); // reinitialize mixitup
	$('#Grid li').remove();
	
	$.each(data, function(index, profile) {
		$('#Grid').append('<li class="mix '+ profile.type +'"><div class="profile-content"><div style="float:left; padding-right: 5px; padding-bottom: 30px;"><a class="plus" href="javascript:kmlClick('+ profile.number +');"><img src="http://maps.google.com/mapfiles/ms/icons/red-dot.png"></a></div><div class="number" style="display:none;">'+ profile.number + '</div><div class="title">'+ profile.title + '</div><div class="address">'+ profile.address + '<br><span class="cp">'+ profile.codePostal + '</span> <span class="city">'+ profile.city + '</span></div><div class="tel">'+ profile.tel +'</div><div class="distance">'+ profile.distance+' km</div></div></li>');
	});
	$("#profileMap #Grid").mixItUp(
  	{
  		pagination: {
			limit: 4,
			loop: false,
			generatePagers: true,
			pagerClass: '',
			prevButtonHTML: '«',
			nextButtonHTML: '»',
		}
	});
}

