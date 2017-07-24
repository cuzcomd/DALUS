function mapToObject(){
    	var mapOverlays = objectArray;
        var tmpMap = new Object;
        var tmpOverlay, paths;
        tmpMap.zoom = map.getZoom();
        tmpMap.tilt = map.getTilt();
        tmpMap.mapTypeId = map.getMapTypeId();
        tmpMap.center = { lat: map.getCenter().lat(), lng: map.getCenter().lng() };
        tmpMap.overlays = new Array();

        for( var i=0; i < mapOverlays.length; i++ ){
            if( mapOverlays[i].getMap() == null ){
                continue;
            }
            tmpOverlay = new Object;
            tmpOverlay.type = mapOverlays[i].type;
            tmpOverlay.title = mapOverlays[i].title;
            tmpOverlay.content = mapOverlays[i].content;

            if( mapOverlays[i].fillColor ){
                tmpOverlay.fillColor = mapOverlays[i].fillColor;
            }

            if( mapOverlays[i].fillOpacity ){
                tmpOverlay.fillOpacity = mapOverlays[i].fillOpacity;
            }

            if( mapOverlays[i].strokeColor ){
                tmpOverlay.strokeColor = mapOverlays[i].strokeColor;
            }

            if( mapOverlays[i].strokeOpacity ){
                tmpOverlay.strokeOpacity = mapOverlays[i].strokeOpacity;
            }

            if( mapOverlays[i].strokeWeight ){
                tmpOverlay.strokeWeight = mapOverlays[i].strokeWeight;
            }

            if( mapOverlays[i].obj_nummer ){
                tmpOverlay.obj_nummer = mapOverlays[i].obj_nummer;
            }

            if( mapOverlays[i].obj_messwert ){
                tmpOverlay.obj_messwert = mapOverlays[i].obj_messwert;
            }

            if( mapOverlays[i].obj_hinweis ){
                tmpOverlay.obj_hinweis = mapOverlays[i].obj_hinweis;
            }

            if( mapOverlays[i].obj_farbe ){
                tmpOverlay.obj_farbe = mapOverlays[i].obj_farbe;
            }

            if( mapOverlays[i].obj_lat ){
                tmpOverlay.obj_lat = mapOverlays[i].obj_lat;
            }

            if( mapOverlays[i].obj_lon ){
                tmpOverlay.obj_lon = mapOverlays[i].obj_lon;
            }

            if( mapOverlays[i].obj_typ ){
                tmpOverlay.obj_typ = mapOverlays[i].obj_typ;
            }

            if( mapOverlays[i].obj_parameter ){
                tmpOverlay.obj_parameter = mapOverlays[i].obj_parameter;
            }

            if( mapOverlays[i].icon ){
                tmpOverlay.icon = mapOverlays[i].icon;
            }

            if( mapOverlays[i].flat ){
                tmpOverlay.flat = mapOverlays[i].flat;
            }

            if( mapOverlays[i].obj_typ == "polygon" ||  mapOverlays[i].obj_typ == "polygon1" ||  mapOverlays[i].obj_typ == "polygon2" ){
                tmpOverlay.paths = new Array();
                paths = mapOverlays[i].getPaths();
                for( var j=0; j < paths.length; j++ ){
                    tmpOverlay.paths[j] = new Array();
                    for( var k=0; k < paths.getAt(j).length; k++ ){
                        tmpOverlay.paths[j][k] = { lat: paths.getAt(j).getAt(k).lat().toString() , lng: paths.getAt(j).getAt(k).lng().toString() };
                    }
                }

            }else if( mapOverlays[i].obj_typ == "polyline" ||  mapOverlays[i].obj_typ == "polygonCenter" ){
                tmpOverlay.path = new Array();
                path = mapOverlays[i].getPath();
                for( var j=0; j < path.length; j++ ){
                    tmpOverlay.path[j] = { lat: path.getAt(j).lat().toString() , lng: path.getAt(j).lng().toString() };
                }

            }else if( mapOverlays[i].obj_typ == "circle" ){
                tmpOverlay.center = { lat: mapOverlays[i].getCenter().lat(), lng: mapOverlays[i].getCenter().lng() };
                tmpOverlay.radius = mapOverlays[i].radius;

            }else if( mapOverlays[i].obj_typ == "rectangle" ){
                tmpOverlay.bounds = {  sw: {lat: mapOverlays[i].getBounds().getSouthWest().lat(), lng: mapOverlays[i].getBounds().getSouthWest().lng()},
                    ne:     {lat: mapOverlays[i].getBounds().getNorthEast().lat(), lng: mapOverlays[i].getBounds().getNorthEast().lng()}
                };
                
            }else if( mapOverlays[i].obj_typ == "marker" ){
                tmpOverlay.position = { lat: mapOverlays[i].getPosition().lat(), lng: mapOverlays[i].getPosition().lng() };
            }
            tmpMap.overlays.push( tmpOverlay );
        }

        return tmpMap;
    }

    function toKML() {
        var result = mapToObject();
        var xw = new XMLWriter('UTF-8');
        xw.formatting = 'indented';//add indentation and newlines
        xw.indentChar = ' ';//indent with spaces
        xw.indentation = 2;//add 2 spaces per level

        xw.writeStartDocument( );
        xw.writeStartElement( 'kml' );
        xw.writeAttributeString( "xmlns", "http://www.opengis.net/kml/2.2");
		xw.writeAttributeString( "xmlns:gx", "http://www.google.com/kml/ext/2.2");
        xw.writeStartElement('Document');
		xw.writeStartElement('name');
		xw.writeCDATA(activeProjectName);
		xw.writeEndElement();

		xw.writeStartElement('Style');
		xw.writeAttributeString( "id", "white" );
			xw.writeStartElement('IconStyle');
			xw.writeAttributeString( "id", "mystyle" );
				xw.writeStartElement('Icon');
					xw.writeStartElement('href');
						xw.writeCDATA( 'https://raw.githubusercontent.com/cuzcomd/DALUS/master/images/white.png');
					xw.writeEndElement();
					xw.writeStartElement('scale');
						xw.writeCDATA( '.1.0');
					xw.writeEndElement();
				xw.writeEndElement();
			xw.writeEndElement();
		xw.writeEndElement();

		xw.writeStartElement('Style');
		xw.writeAttributeString( "id", "green" );
			xw.writeStartElement('IconStyle');
			xw.writeAttributeString( "id", "mystyle" );
				xw.writeStartElement('Icon');
					xw.writeStartElement('href');
						xw.writeCDATA( 'https://raw.githubusercontent.com/cuzcomd/DALUS/master/images/green.png');
					xw.writeEndElement();
					xw.writeStartElement('scale');
						xw.writeCDATA( '.1.0');
					xw.writeEndElement();
				xw.writeEndElement();
			xw.writeEndElement();
		xw.writeEndElement();

		xw.writeStartElement('Style');
		xw.writeAttributeString( "id", "blue" );
			xw.writeStartElement('IconStyle');
			xw.writeAttributeString( "id", "mystyle" );
				xw.writeStartElement('Icon');
					xw.writeStartElement('href');
						xw.writeCDATA( 'https://raw.githubusercontent.com/cuzcomd/DALUS/master/images/blue.png');
					xw.writeEndElement();
					xw.writeStartElement('scale');
						xw.writeCDATA( '.1.0');
					xw.writeEndElement();
				xw.writeEndElement();
			xw.writeEndElement();
		xw.writeEndElement();

		xw.writeStartElement('Style');
		xw.writeAttributeString( "id", "yellow" );
			xw.writeStartElement('IconStyle');
			xw.writeAttributeString( "id", "mystyle" );
				xw.writeStartElement('Icon');
					xw.writeStartElement('href');
						xw.writeCDATA( 'https://raw.githubusercontent.com/cuzcomd/DALUS/master/images/yellow.png');
					xw.writeEndElement();
					xw.writeStartElement('scale');
						xw.writeCDATA( '.1.0');
					xw.writeEndElement();
				xw.writeEndElement();
			xw.writeEndElement();
		xw.writeEndElement();

		xw.writeStartElement('Style');
		xw.writeAttributeString( "id", "red" );
			xw.writeStartElement('IconStyle');
			xw.writeAttributeString( "id", "mystyle" );
				xw.writeStartElement('Icon');
					xw.writeStartElement('href');
						xw.writeCDATA( 'https://raw.githubusercontent.com/cuzcomd/DALUS/master/images/red.png');
					xw.writeEndElement();
					xw.writeStartElement('scale');
						xw.writeCDATA( '.1.0');
					xw.writeEndElement();
				xw.writeEndElement();
			xw.writeEndElement();
		xw.writeEndElement();

		xw.writeStartElement('Style');
		xw.writeAttributeString( "id", "black" );
			xw.writeStartElement('IconStyle');
			xw.writeAttributeString( "id", "mystyle" );
				xw.writeStartElement('Icon');
					xw.writeStartElement('href');
						xw.writeCDATA( 'https://raw.githubusercontent.com/cuzcomd/DALUS/master/images/black.png');
					xw.writeEndElement();
					xw.writeStartElement('scale');
						xw.writeCDATA( '.1.0');
					xw.writeEndElement();
				xw.writeEndElement();
			xw.writeEndElement();
		xw.writeEndElement();

        xw.writeStartElement('Style');
        xw.writeAttributeString( "id", "polygon1" );
            xw.writeStartElement('PolyStyle');
                xw.writeStartElement('color');
                    xw.writeCDATA( '#7f0000cc');
                xw.writeEndElement();
                xw.writeStartElement('colorMode');
                    xw.writeCDATA( 'normal');
                xw.writeEndElement();
                xw.writeStartElement('fill');
                    xw.writeCDATA( '1');
                xw.writeEndElement();
                xw.writeStartElement('outline');
                    xw.writeCDATA( '1');
                xw.writeEndElement();
            xw.writeEndElement();
        xw.writeEndElement();

        xw.writeStartElement('Style');
        xw.writeAttributeString( "id", "polygon2" );
            xw.writeStartElement('PolyStyle');
                xw.writeStartElement('color');
                    xw.writeCDATA( '#7f00cccc');
                xw.writeEndElement();
                xw.writeStartElement('colorMode');
                    xw.writeCDATA( 'normal');
                xw.writeEndElement();
                xw.writeStartElement('fill');
                    xw.writeCDATA( '1');
                xw.writeEndElement();
                xw.writeStartElement('outline');
                    xw.writeCDATA( '1');
                xw.writeEndElement();
            xw.writeEndElement();
        xw.writeEndElement();

         xw.writeStartElement('Style');
        xw.writeAttributeString( "id", "circle" );
            xw.writeStartElement('PolyStyle');
                xw.writeStartElement('color');
                    xw.writeCDATA( '#7f000000');
                xw.writeEndElement();
                xw.writeStartElement('colorMode');
                    xw.writeCDATA( 'normal');
                xw.writeEndElement();
                xw.writeStartElement('fill');
                    xw.writeCDATA( '1');
                xw.writeEndElement();
                xw.writeStartElement('outline');
                    xw.writeCDATA( '1');
                xw.writeEndElement();
            xw.writeEndElement();
        xw.writeEndElement();

        for( var i = 0; i < result.overlays.length; i++ ){
            xw.writeStartElement('Placemark');
            xw.writeStartElement('name');
            if( result.overlays[i].obj_typ == "marker" ||  result.overlays[i].obj_typ == "met"){
            	xw.writeCDATA( 'Messpunkt '+result.overlays[i].obj_nummer );
            }
            else{
            	xw.writeCDATA( result.overlays[i].title );
       		}
            xw.writeEndElement();
			
			if( result.overlays[i].obj_typ == "marker" ||  result.overlays[i].obj_typ == "met"){
            xw.writeStartElement('styleUrl');	
            switch (result.overlays[i].obj_farbe)
            {
            	case 'white':
            	xw.writeCDATA('#white');
            	break;

            	case 'green':
            	xw.writeCDATA('#green');
            	break;

            	case 'blue':
            	xw.writeCDATA('#blue');
            	break;

            	case 'yellow':
            	xw.writeCDATA('#yellow');
            	break;

            	case 'red':
            	xw.writeCDATA('#red');
            	break;

            	case 'black':
            	xw.writeCDATA('#black');
            	break;

            	default:
            	xw.writeCDATA('');
            	break;
            }	
            xw.writeEndElement();
            }

            if( result.overlays[i].obj_typ == "polygon1"){
                xw.writeStartElement('styleUrl');   
                xw.writeCDATA('#polygon1');  
                xw.writeEndElement();  
            }  
            else if( result.overlays[i].obj_typ == "polygon2"){
                xw.writeStartElement('styleUrl');   
                xw.writeCDATA('#polygon2');  
                xw.writeEndElement();  
            }
            else if( result.overlays[i].obj_typ == "circle" || result.overlays[i].obj_typ == "polygon"){
                xw.writeStartElement('styleUrl');   
                xw.writeCDATA('#circle');  
                xw.writeEndElement();  
            }   
           
            if( result.overlays[i].obj_typ == "marker" ){
            	xw.writeStartElement('description');
            	xw.writeCDATA( 'Messwert: '+result.overlays[i].obj_messwert+' ppm \n Hinweis:\n\n'+ result.overlays[i].obj_hinweis);
            	xw.writeEndElement();

                xw.writeStartElement('Point');
                xw.writeElementString('coordinates', result.overlays[i].obj_lon+","+result.overlays[i].obj_lat+",0");
                xw.writeEndElement();

            }else if( result.overlays[i].obj_typ == "met" ){
            	xw.writeStartElement('description');
            	xw.writeCDATA( 'Adresse: '+result.overlays[i].obj_parameter.adresse+'\n'+
            		'Ausbreitungswinkel: '+result.overlays[i].obj_parameter.ausbreitungswinkel+'°\n'+
            		'Windrichtung: '+result.overlays[i].obj_parameter.windrichtung+'°\n'+
            		'Gefährdung im Inneren von Gebäuden: '+result.overlays[i].obj_parameter.distanz_innen+' m\n'+
            		'Gefährdung im Freien: '+result.overlays[i].obj_parameter.distanz_aussen+' m');
            	xw.writeEndElement();

                xw.writeStartElement('Point');
                xw.writeElementString('coordinates', result.overlays[i].obj_lon+","+result.overlays[i].obj_lat+",2");
                xw.writeEndElement();

            }else if( result.overlays[i].obj_typ == "polygon" || result.overlays[i].obj_typ == "polygon1" || result.overlays[i].obj_typ == "polygon2" || result.overlays[i].obj_typ == "rectangle" || result.overlays[i].obj_typ == "circle" ){
                xw.writeStartElement('Polygon');
                xw.writeElementString('altitudeMode', 'relativeToGround');

                if( result.overlays[i].obj_typ == "rectangle" ){
                    //its a polygon
                    xw.writeStartElement('outerBoundaryIs');
                    xw.writeStartElement('LinearRing');
                    xw.writeStartElement( "coordinates" );
                    xw.writeString( result.overlays[i].bounds.sw.lng + "," + result.overlays[i].bounds.sw.lat + ",2" );
                    xw.writeString( result.overlays[i].bounds.ne.lng + "," + result.overlays[i].bounds.sw.lat + ",2" );
                    xw.writeString( result.overlays[i].bounds.ne.lng + "," + result.overlays[i].bounds.ne.lat + ",2" );
                    xw.writeString( result.overlays[i].bounds.sw.lng + "," + result.overlays[i].bounds.ne.lat + ",2" );
                    xw.writeEndElement();
                    xw.writeEndElement();
                    xw.writeEndElement();
                }else if (result.overlays[i].obj_typ == "circle"){
                    //its a polygon, approximate a circle by a circular 64 sided polygon.
                    xw.writeStartElement('outerBoundaryIs');
                    xw.writeStartElement('LinearRing');
                    xw.writeStartElement( "coordinates" );
                    var d2r = Math.PI / 180;   // degrees to radians
                    var r2d = 180 / Math.PI;   // radians to degrees
                    var earthsradius = 6378137; // 6378137 is the radius of the earth in meters
                    var dir = 1; // clockwise

                    var points = 64;

                    // find the raidus in lat/lon
                    var rlat = (result.overlays[i].radius / earthsradius) * r2d;
                    var rlng = rlat / Math.cos(result.overlays[i].center.lat * d2r);

                    var extp = new Array();
                    if (dir==1)     {var start=0;var end=points+1} // one extra here makes sure we connect the line
                    else            {var start=points+1;var end=0}
                    for (var j=start; (dir==1 ? j < end : j > end); j=j+dir){
                        var theta = Math.PI * (j / (points/2));
                        ey = result.overlays[i].center.lng + (rlng * Math.cos(theta)); // center a + radius x * cos(theta)
                        ex = result.overlays[i].center.lat + (rlat * Math.sin(theta)); // center b + radius y * sin(theta)
                        xw.writeString( ey + "," + ex + ",20" );
                    }
                    xw.writeEndElement();
                    xw.writeEndElement();
                    xw.writeEndElement();
                }else{
                    for( var j=0; j < result.overlays[i].paths.length; j++ ){
                        if( j==0 ){
                            xw.writeStartElement('outerBoundaryIs');
                        }else{
                            xw.writeStartElement('innerBoundaryIs');
                        }
                        xw.writeStartElement('LinearRing');
                        xw.writeStartElement( "coordinates" );
                        for( var k=0; k < result.overlays[i].paths[j].length; k++ ){
                            xw.writeString( result.overlays[i].paths[j][k].lng + "," + result.overlays[i].paths[j][k].lat + ",20" );
                        }
                        xw.writeEndElement();
                        xw.writeEndElement();
                        xw.writeEndElement();
                    }
                }
                xw.writeEndElement();

            }else if( result.overlays[i].obj_typ == "polyline" || result.overlays[i].obj_typ == "polygonCenter" ){
                xw.writeStartElement('LineString');
                xw.writeElementString('altitudeMode', 'relativeToGround');
                xw.writeStartElement( "coordinates" );
                for( var j=0; j < result.overlays[i].path.length; j++ ){
                    xw.writeString( result.overlays[i].path[j].lng + "," + result.overlays[i].path[j].lat + ",2" );
                }
                xw.writeEndElement();
                xw.writeEndElement();

            }

            xw.writeEndElement(); // END PlaceMarker
        }

        xw.writeEndElement();
        xw.writeEndElement();
        xw.writeEndDocument();

        var xml = xw.flush(); //generate the xml string
        xw.close();//clean the writer
        xw = undefined;//don't let visitors use it, it's closed
        //set the xml
        document.getElementById('kmlString').value = xml;
        var downloadLink = document.getElementById('download-link');
        downloadLink.href = "data:;base64," + btoa($('#kmlString').val());
        downloadLink.download = activeProjectName+".kml"; //Erzuegt den Dateinamen des Downloads aus dem aktuellen Projektnamen
    }