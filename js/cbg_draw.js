
function draw() {
    var startTime = new Date().getTime();
    // nur bei Änderungen neu zeichnen
    if (controls.drawing) {
        context.fillStyle = 'black';
        context.fillRect(0, 0, context.canvas.width, context.canvas.height);
        for (var layer = 0; layer < 16; ++layer) {
            drawChunks(layer);
        }
        context.fillStyle = 'rgba(0, 140, 255, 0.5)';
        if (selectedTile)
            drawRect(selectedTile[0] * tileSize, selectedTile[1] * tileSize, tileSize, tileSize);
        hasUpdate = false;
        frameCounter++;
    }
    if (selectedTile)
        $(popup).css(calculatePopupPosition(selectedTile[0], selectedTile[1]));
    var endTime = new Date().getTime();
    gameTime += (endTime - startTime);
    console.log('Required:', (endTime - startTime), 'ms');
    setTimeout('draw()', 1000 / controls.fps);
}

function drawChunks(layer) {
    $(calculateVisibleChunks()).each(function(index, chunk) {
        drawChunk(chunk.x, chunk.y, layer)
    });
}

function drawChunk(x, y, layer) {
    if (!map[y] || !map[y][x])
        return false;
    for (tX = 0; tX < chunkSize; tX++) {
        for (tY = 0; tY < chunkSize; tY++) {
            tile = map[y][x][tY*chunkSize + tX];
            var type = tile[0];
            drawTileLayer(type, layer, x * chunkSize + tX, y * chunkSize + tY);
        }
    }
    return true;
}

function drawTileLayer(tileType, layer, tileX, tileY) {
    // calculate the position
    var x = tileX * tileSize;
    var y = tileY * tileSize;
    var layers = tileTypes[tileType].layers;
    // layer not given
    if (layer >= layers.length)
        return;
    var texture = textures[layers[layer]];
    var width = texture.width ? texture.width : tileSize;
    var height = texture.height ? texture.height : tileSize;
    x += -width + tileSize;
   	y += -height + tileSize;
   	var sourceX = 0;
   	var sourceY = 0;
   	var image = textureImages[texture.texture];
   	// find the correct source position of the texture
    switch (texture.type) {
    	default:
    	case 'static':
    	sourceX = texture.x;
    	sourceY = texture.y;
    	break;
    	case 'animated':
    	switch (texture.direction) {
    		default:
    		case 'h':
    		sourceX = texture.x + calculateFrame(texture.length, texture.delay) * width;
    		sourceY = texture.y;
    		break;
    		case 'v':
    		sourceX = texture.x;
    		sourceY = texture.y + calculateFrame(texture.length, texture.delay) * height;
    		break;
    	}
    	break;
    }
    // draw the texture
    drawImage(image, x, y, width, height, sourceX, sourceY, width, height);
}

function drawRaster(width, heigth) {
    cv_width = context.canvas.width;
    cv_height = context.canvas.height;
    for (x = currentPosition.x - (currentPosition.x % width) - cv_width / 2; x < ntPosition.x + cv_width; x += width) {
        drawLine (x, currentPosition.y - cv_height, x, currentPosition.y + cv_height, true);
    }
    for (y = currentPosition.y - (currentPosition.y % height) - cv_height / 2; y < currentPosition.y + cv_height; y += heigth) {
        drawLine (currentPosition.x - cv_width, y, currentPosition.x + cv_width, y, true);
    }
}

function drawImage(image, x, y, width, height, src_x, src_y, src_width, src_height) {
    if(!width) width = image.width;
    if(!height) height = image.height;
    if(!src_x) src_x = 0;
    if(!src_y) src_y = 0;
    if(!src_width) src_width = width;
    if(!src_height) src_height = height;
    // if outside the drawing area then don't draw it
    if (isInside(x + width, y + height) || isInside(x, y) || isInside(x + width, y) || isInside(x, y + height)) {
        // otherwise draw the image
        context.drawImage(image, src_x, src_y, src_width, src_height, translateX(x), translateY(y), width, height);
        return true;
    } else {
        return false;
    }
}

function drawSingleLine(startX, startY, endX, endY, force) {
    if (isInside(startX, startY) || isInside(endX, endY) || force) {
        context.beginPath();
        context.moveTo(translateX(startX), translateY(startY));
        context.lineTo(translateX(endX), translateY(endY));
        context.stroke();
        context.closePath();
        return true;
    }
    return false;
}

function drawPolygon(points, close) {
    if (points.length == 0)
        return false;
    context.beginPath();
    context.moveTo(translateX(points[0][0]), translateY(points[0][1]));
    for (point in points) {
        context.lineTo(translateX(point[0]), translateY(point[1]));
    }
    if (close) {
        context.lineTo(translateX(points[0][0]), translateY(points[0][1]));
    }
    context.closePath();
    return true;
}

function drawRect(x, y, width, height) {
    if (isInside(x + width, y + height) || isInside(x, y) || isInside(x + width, y) || isInside(x, y + height)) {
        context.fillRect(translateX(x), translateY(y), width, height);
        return true;
    }
    return false;
}
