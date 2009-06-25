function __VectorMath() {
	this.magnitude = function (vect) {
		return Math.sqrt((vect.x * vect.x) + (vect.y * vect.y));
	}
	
	this.normalize = function (vect) {
		var mag = this.magnitude(vect);
		return {
			x: vect.x / mag,
			y: vect.y / mag
		}
	}
};

var VectorMath = new __VectorMath();

function GFontedGlyph(lines, w, h) {
	var draw_data = null;
	var ctx = null;
	
	this.wire = false;
	this.surface = null;
	
	this.end_draw = function() {
		if(this.wire) {
			ctx.stroke();
		} else {
			ctx.fill();
		}
	};

	this.render = function() {
		
		// set up the view
		ctx.scale(w,h);
		ctx.lineWidth = 0.0006;
		
		// draw the circles
		for(var i = 0; i < draw_data.length; i++) {
			ctx.beginPath();
			ctx.arc(draw_data[i].x, draw_data[i].y, draw_data[i].s, 0, 2 * Math.PI, false);
			//ctx.stroke();
			this.end_draw();
		}

		// draw connecting segments
		ctx.beginPath();
		ctx.moveTo(draw_data[0].x, draw_data[0].y);
		for(var i = 1; i < draw_data.length; i++) {
			//var slope = -(draw_data[i].x - draw_data[i-1].x)/(draw_data[i].y - draw_data[i-1].y);
			var normal = {
				x: -(draw_data[i].y - draw_data[i-1].y),
				y: (draw_data[i].x - draw_data[i-1].x)
			}
			
			normal = VectorMath.normalize(normal);
			
			// if two points are the same the vector normalization will
			//     fail (divide by zero).  Skip drawing that line
			//     segment.
			if(normal.x || normal.y) {
				var points = [
					{
						x:draw_data[i-1].x  + (draw_data[i-1].s * -normal.x),
						y: draw_data[i-1].y + (draw_data[i-1].s * -normal.y)
					},
					{
						x:draw_data[i-1].x  + (draw_data[i-1].s * normal.x),
						y: draw_data[i-1].y + (draw_data[i-1].s * normal.y)
					},
					{
						x:draw_data[i].x  + (draw_data[i].s * normal.x),
						y: draw_data[i].y + (draw_data[i].s * normal.y)
					},
					{
						x:draw_data[i].x  + (draw_data[i].s * -normal.x),
						y: draw_data[i].y + (draw_data[i].s * -normal.y)
					}
				];
				
				ctx.moveTo(points[0].x, points[0].y);
				ctx.lineTo(points[1].x, points[1].y);
				ctx.lineTo(points[2].x, points[2].y);
				ctx.lineTo(points[3].x, points[3].y);
				ctx.lineTo(points[0].x, points[0].y);
			}
		}
		//ctx.stroke();
		this.end_draw();
	}
	
	this.load = function(lines, w, h) {
		this.surface = document.createElement("canvas");
		this.surface.width = w;
		this.surface.height = h;
		
		ctx = this.surface.getContext('2d');
		draw_data = lines;
	}
	
	this.load(lines, w, h);
};

jQuery.fn.gfonted_draw_glyph = function (lines, h, wire) {
	return this.each(function(){
		var glyph = new GFontedGlyph(lines, h, h);
		if(wire == 'wire') 
			glyph.wire = true;
		glyph.render();
		jQuery(this).append(glyph.surface);
	});
};

jQuery.fn.gfonted_draw_string = function (lines, h) {
	return this.each(function(){
		var text = jQuery(this).text();
		jQuery(this).text("");
		
		for(var i = 0; i < text.length; i++) {
			var obj = text.charAt(i);
			jQuery(this).gfonted_draw_glyph(lines[obj], h);
		}
		//for( o in lines) {
		//	var glyph = new GFontedGlyph(lines[o], h, h);
		//	glyph.render();
		//}
		//jQuery(this).append(glyph.surface);
	});
};
